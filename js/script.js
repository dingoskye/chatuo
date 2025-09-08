// === Chatuo widget ===
document.addEventListener('DOMContentLoaded', () => {
    const $ = (s) => document.querySelector(s);
    const chat = $('#cu-chat');
    const toggle = $('#cu-toggle');
    const panel = $('#cu-panel');
    const closeBtn = $('#cu-close');
    const messages = $('#cu-messages');
    const form = $('#cu-form');
    const input = $('#cu-input');
    const sendBtn = form.querySelector('button');

    // ---- Guided flow ----
    const FLOW = [
        {
            id: 'category',
            text: 'Waarvoor shop je vandaag?',
            options: ['Laptop', 'Tablet', 'Monitor']
        },
        {
            id: 'budget',
            text: 'Wat is je budget?',
            options: ['< €500', '€500–€1000', '> €1000']
        },
        {
            id: 'size',
            text: 'Gewenste grootte?',
            // Past per categorie, maar voor demo even generiek
            options: ['Klein & licht', 'Middel', 'Groot']
        },
        {
            id: 'use',
            text: 'Belangrijkste gebruik?',
            options: ['Studie/werk', 'Gaming', 'Video/foto', 'Allround']
        }
    ];
    let step = 0;
    const answers = {};
    let started = false;

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

    const askCurrent = () => {
        const q = FLOW[step];
        if (!q) return finish();
        addMsg(q.text);
        addOptions(q.options, (label) => {
            // user pick
            addMsg(label, 'user');
            answers[q.id] = label;
            // remove chips of this question
            messages.querySelectorAll('.cu-quick').forEach((el) => {
                if (el.previousSibling && el.previousSibling.textContent === q.text) el.remove();
            });
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

    const finish = () => {
        // Heel simpele “ranking” op basis van antwoorden (demo)
        const { category, budget, size, use } = answers;
        const picks = [];

        if (category === 'Laptop') {
            if (use === 'Gaming') picks.push('Predator Helios 300');
            else if (use === 'Video/foto') picks.push('MacBook Pro 14" M-serie');
            else if (budget === '< €500') picks.push('Acer Aspire 3');
            else picks.push('Dell XPS 13');
        } else if (category === 'Tablet') {
            if (budget === '< €500') picks.push('iPad 9e gen / Galaxy Tab S6 Lite');
            else picks.push('iPad Air / Galaxy Tab S9');
        } else if (category === 'Monitor') {
            if (use === 'Gaming') picks.push('LG 27GP850 165Hz');
            else if (size === 'Groot') picks.push('LG 34" Ultrawide');
            else picks.push('Dell 24" IPS');
        }

        const summary = `Topkeuze op basis van jouw antwoorden:
- Categorie: ${category}
- Budget: ${budget}
- Grootte: ${size}
- Gebruik: ${use}

Aanbevolen: ${picks.join(', ')}.`;

        addMsg(summary);
        addOptions(['Opnieuw beginnen'], () => {
            step = 0;
            for (const k in answers) delete answers[k];
            startFlow();
        });
    };

    // ---- Open/close ----
    const open = () => {
        chat.dataset.open = 'true';
        panel.hidden = false;
        toggle.setAttribute('aria-expanded', 'true');
        if (!started) startFlow();
    };
    const close = () => {
        chat.dataset.open = 'false';
        toggle.setAttribute('aria-expanded', 'false');
        panel.hidden = true;
    };

    toggle.addEventListener('click', () => (chat.dataset.open === 'true' ? close() : open()));
    closeBtn.addEventListener('click', close);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && chat.dataset.open === 'true') close();
    });

    // tekstveld blijft bruikbaar
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const text = (input.value || '').trim();
        if (!text) return;
        addMsg(text, 'user');
        input.value = '';
    });

    // Debug: als je in de console dit niet ziet, laadt je script niet
    console.log('Chatuo widget loaded');
});
