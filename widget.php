<?php
// widget.php — Chatuo widget (UI + (optioneel) POST endpoint zonder AI)

// --- (optioneel) API: als je ooit iets wilt loggen, kun je POST hier afhandelen ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => true]); // voorlopig niks doen
    exit;
}

// --- UI renderen (eenmalig) ---
if (!defined('CHATUO_WIDGET_RENDERED')) {
    define('CHATUO_WIDGET_RENDERED', true);
    ?>
    <!-- Chatuo widget -->
    <?php $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); ?>
    <link rel="stylesheet" href="<?= $base ?>/css/style.css">
    <script defer src="<?= $base ?>/js/script.js"></script>
    <div id="cu-chat" class="cu-chat" aria-live="polite">
        <button id="cu-toggle" class="cu-toggle" aria-controls="cu-panel" aria-expanded="false" aria-label="Open chat">
            <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
                <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8z" fill="currentColor"/>
            </svg>
        </button>

        <section id="cu-panel" class="cu-panel" role="dialog" aria-modal="false" aria-label="Chat" hidden>
            <header class="cu-header">
                <div><strong>Chatuo</strong><br><small>Vindt snel je match</small></div>
                <button id="cu-close" class="cu-close" aria-label="Sluit chat">×</button>
            </header>

            <main id="cu-messages" class="cu-messages" tabindex="0" aria-label="Berichten"></main>

            <!-- Tekstveld blijft handig, maar de flow werkt met keuzeknoppen -->
            <form id="cu-form" class="cu-form" autocomplete="off">
                <input id="cu-input" class="cu-input" type="text" placeholder="Of typ iets…" />
                <button class="cu-send" type="submit">Stuur</button>
            </form>
        </section>
    </div>
    <?php
}
