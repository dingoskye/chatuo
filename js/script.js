// === Chatuo widget (met webservice) ===
document.addEventListener('DOMContentLoaded', () => {
    const $ = (s) => document.querySelector(s);
    const chat = $('#cu-chat');
    const apiUrl = chat?.dataset.api || 'api/products.php';

    const toggle = $('#cu-toggle');
    const panel = $('#cu-panel');
    const closeBtn = $('#cu-close');
    const messages = $('#cu-messages');
    const form = $('#cu-form');
    const input = $('#cu-input');

    // ---- Flow: 1) zoeken -> 2) subjectief -> 3) objectief ----
    const FLOW = [
        { id: 'category', text: 'Waar ben je naar op zoek?', options: ['Laptop', 'Tablet', 'Monitor'], allowFreeText: true },
        { id: 'use', text: 'Waar ga je het vooral voor gebruiken?', options: ['Studie/werk', 'Gaming', 'Video/foto', 'Allround'], group: 'subjective' },
        { id: 'priority', text: 'Wat vind je het belangrijkst?', options: ['Snelheid', 'Draagbaarheid', 'Batterijduur', 'Beeldkwaliteit', 'Prijs'], group: 'subjective' },
        { id: 'budget', text: 'Wat is je budget?', options: ['< €500', '€500–€1000', '> €1000'], group: 'objective' },
        { id: 'size', text: 'Gewenste grootte?', options: [], group: 'objective', dynamic: true },
    ];

    const sizeOptionsFor = (category) => {
        switch ((category || '').toLowerCase()) {
            case 'laptop':  return ['13–14"', '15–16"', '17"'];
            case 'tablet':  return ['8–9"', '10–11"', '12–13"'];
            case 'monitor': return ['24–25"', '27"', '34"+'];
            default:        return ['Klein & licht', 'Middel', 'Groot'];
        }
    };

    const TIER_BY_BUDGET = { '< €500':'low','€500–€1000':'mid','> €1000':'high' };

    // ---- Webservice laden ----
    let CATALOG = [];
    async function ensureCatalog() {
        if (CATALOG.length) return CATALOG;
        const res = await fetch(apiUrl, { headers: { 'Accept':'application/json' }});
        if (!res.ok) throw new Error(`API ${res.status}`);
        CATALOG = await res.json();
        return CATALOG;
    }

    // ---- Scoring & Top 3 ----
    function scoreProduct(p, a) {
        let s = 0; const reasons = [];
        if (p.category?.toLowerCase() === (a.category||'').toLowerCase()) { s+=4; reasons.push(`Categorie ${p.category}`); }
        const wantedTier = TIER_BY_BUDGET[a.budget];
        if (wantedTier && p.tier === wantedTier) { s+=3; reasons.push(`Binnen budget ${a.budget}`); }
        if (a.size && p.size === a.size) { s+=2; reasons.push(`Grootte ${p.size}`); }
        if (a.use && Array.isArray(p.uses) && p.uses.includes(a.use)) { s+=3; reasons.push(`Past bij gebruik “${a.use}”`); }
        if (a.priority && Array.isArray(p.strengths) && p.strengths.includes(a.priority)) { s+=2; reasons.push(`Sterk in ${a.priority.toLowerCase()}`); }
        if (Array.isArray(p.uses) && p.uses.includes('Allround')) s+=1;
        return { score: s, reasons };
    }

    async function top3Products(a) {
        const list = await ensureCatalog();
        return list
            .filter(p => !a.category || p.category?.toLowerCase() === a.category.toLowerCase())
            .map(p => ({ ...p, ...scoreProduct(p, a) }))
            .sort((x, y) => y.score - x.score)
            .slice(0, 3);
    }

    // ---- UI helpers ----
    const addMsg = (text, who = 'bot') => {
        const el = document.createElement('div');
        el.className = `cu-msg ${who}`;
        el.textContent = text;
        messages.appendChild(el);
        messages.scrollTop = messages.scrollHeight;
        return el;
    };

    const addTyping = () => {
        const el = document.createElement('div');
        el.className = 'cu-typing';
        el.innerHTML = 'Chatuo <span class="cu-dots"><span>•</span><span>•</span><span>•</span></span>';
        messages.appendChild(el);
        messages.scrollTop = messages.scrollHeight;
        return el;
    };

    const addOptions = (opts, onPick) => {
        const wrap = document.createElement('div');
        wrap.className = 'cu-quick';
        opts.forEach(label => {
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'cu-chip';
            b.textContent = label;
            b.addEventListener('click', () => onPick(label));
            wrap.appendChild(b);
        });
        messages.appendChild(wrap);
        messages.scrollTop = messages.scrollHeight;
        return wrap;
    };

    function renderResults(items) {
        const wrap = document.createElement('div');
        wrap.className = 'cu-results';

        items.forEach((p, idx) => {
            const card = document.createElement('div');
            card.className = 'cu-card';
            const badgeText = idx === 0 ? 'Beste match' : idx === 1 ? 'Sterke match' : 'Ook passend';
            const hasImg = !!p.image;

            card.innerHTML = `
        <div class="cu-card-img">${hasImg ? `<img src="${p.image}" alt="${p.name}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">` : 'Afbeelding'}</div>
        <div class="cu-badges">
          <span class="cu-badge">${badgeText}</span>
          ${p.category ? `<span class="cu-badge">${p.category}</span>` : ''}
          ${p.size ? `<span class="cu-badge">${p.size}</span>` : ''}
        </div>
        <h4>${p.name}</h4>
        <ul class="cu-why">
          ${(p.reasons||[]).slice(0,2).map(r => `<li>${r}</li>`).join('')}
        </ul>
        <div class="cu-cta">
          <button class="cu-btn">Bekijk</button>
          <button class="cu-btn secondary">Bewaar</button>
        </div>
      `;
            wrap.appendChild(card);
        });

        messages.appendChild(wrap);
        messages.scrollTop = messages.scrollHeight;
    }

    // ---- Flow ----
    let step = 0;
    const answers = {};
    let started = false;

    const askCurrent = () => {
        const q = FLOW[step];
        if (!q) return finish();

        if (q.dynamic && q.id === 'size') q.options = sizeOptionsFor(answers.category);

        addMsg(q.text);
        const chips = addOptions(q.options, (label) => {
            addMsg(label, 'user');
            answers[q.id] = label;
            chips.remove();
            next();
        });
    };

    const next = () => {
        step += 1;
        if (step >= FLOW.length) return finish();
        const t = addTyping();
        setTimeout(() => { t.remove(); askCurrent(); }, 450);
    };

    const startFlow = () => {
        started = true;
        messages.innerHTML = '';
        addMsg('Hoi! Ik ben Chatuo. Ik help je de juiste keuze te vinden.');
        const t = addTyping();
        setTimeout(() => { t.remove(); askCurrent(); }, 600);
    };

    async function finish() {
        try {
            const picks = await top3Products(answers);
            addMsg(`Topkeuzes op basis van jouw antwoorden:
• Zoeken: ${answers.category || '—'}
• Subjectief: gebruik = ${answers.use || '—'}, belangrijkste = ${answers.priority || '—'}
• Objectief: budget = ${answers.budget || '—'}, grootte = ${answers.size || '—'}`);
            renderResults(picks);
        } catch (e) {
            console.error(e);
            addMsg('Kon de productservice niet laden. Probeer later opnieuw.');
        }

        addOptions(['Opnieuw beginnen'], () => {
            step = 0;
            Object.keys(answers).forEach(k => delete answers[k]);
            startFlow();
        });
    }

    // ---- Open/close ----
    const open = () => {
        chat.dataset.open = 'true';
        panel.hidden = false;
        document.querySelector('#cu-toggle')?.setAttribute('aria-expanded','true');
        if (!started) startFlow();
    };
    const close = () => {
        chat.dataset.open = 'false';
        panel.hidden = true;
        document.querySelector('#cu-toggle')?.setAttribute('aria-expanded','false');
    };

    toggle.addEventListener('click', () => (chat.dataset.open === 'true' ? close() : open()));
    closeBtn.addEventListener('click', close);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && chat.dataset.open === 'true') close(); });

    // Free-text voor de eerste stap
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const text = (input.value || '').trim();
        if (!text) return;

        const q = FLOW[step];
        addMsg(text, 'user');
        input.value = '';

        if (q && q.id === 'category' && q.allowFreeText) {
            answers[q.id] = text;
            next();
        }
    });

    console.log('Chatuo widget (webservice) loaded', { apiUrl });
});
