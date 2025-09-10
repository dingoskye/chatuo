<?php
// api/products.php — simpele webservice voor producten

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Alleen GET toegestaan.']);
    exit;
}

/**
 * Velden: id, name, category, size, tier(low|mid|high), strengths[], uses[], image(optional), price(optional), why(optional)
 */
$products = [
    // Laptops
    ['id'=>'xps13','name'=>'Dell XPS 13','category'=>'Laptop','tier'=>'mid','size'=>'13–14"','strengths'=>['Draagbaarheid','Batterijduur'],'uses'=>['Studie/werk','Allround'],'image'=>null,'price'=>1299,'why'=>[
        'Extreem draagbaar 13-inch chassis met lange batterijduur',
        'Snelle NVMe-opslag; ideaal voor studie/werk'
    ]],
    ['id'=>'mbp14','name'=>'MacBook Pro 14"','category'=>'Laptop','tier'=>'high','size'=>'15–16"','strengths'=>['Snelheid','Beeldkwaliteit','Batterijduur'],'uses'=>['Video/foto','Studie/werk'],'image'=>null,'price'=>2299,'why'=>[
        'Krachtige M-serie chip voor video/foto-workflows',
        'Prachtig mini-LED beeldscherm met hoge helderheid'
    ]],
    ['id'=>'helios','name'=>'Predator Helios 300','category'=>'Laptop','tier'=>'high','size'=>'15–16"','strengths'=>['Snelheid','Beeldkwaliteit'],'uses'=>['Gaming'],'image'=>null,'price'=>1699,'why'=>[
        'Hoge verversingssnelheid en krachtige GPU voor gaming',
        'Goede koeling; consistente prestaties'
    ]],
    ['id'=>'aspire3','name'=>'Acer Aspire 3','category'=>'Laptop','tier'=>'low','size'=>'15–16"','strengths'=>['Prijs'],'uses'=>['Allround','Studie/werk'],'image'=>null,'price'=>449,'why'=>[
        'Zeer betaalbaar instapmodel voor basis-taken',
        'Eenvoudig te upgraden opslag/geheugen'
    ]],
    ['id'=>'ideapad5','name'=>'Lenovo IdeaPad 5','category'=>'Laptop','tier'=>'mid','size'=>'15–16"','strengths'=>['Allround'],'uses'=>['Allround','Studie/werk'],'image'=>null,'price'=>899,'why'=>[
        'Goede balans tussen prijs, bouwkwaliteit en prestaties',
        'Fijn toetsenbord en degelijke batterij'
    ]],

    // Tablets
    ['id'=>'ipad9','name'=>'iPad (9e gen)','category'=>'Tablet','tier'=>'low','size'=>'10–11"','strengths'=>['Prijs','Batterijduur'],'uses'=>['Allround','Studie/werk'],'image'=>null,'price'=>349,'why'=>[
        'Beste budget-tablet voor media en school',
        'Grote app-keuze en lange ondersteuning'
    ]],
    ['id'=>'ipadair','name'=>'iPad Air','category'=>'Tablet','tier'=>'mid','size'=>'10–11"','strengths'=>['Beeldkwaliteit','Draagbaarheid'],'uses'=>['Video/foto','Allround'],'image'=>null,'price'=>699,'why'=>[
        'Licht en snel; ideaal met Pencil/keyboard',
        'Scherm met accurate kleuren voor creatief werk'
    ]],
    ['id'=>'tabs6','name'=>'Galaxy Tab S6 Lite','category'=>'Tablet','tier'=>'low','size'=>'10–11"','strengths'=>['Prijs','Draagbaarheid'],'uses'=>['Allround','Studie/werk'],'image'=>null,'price'=>299,'why'=>[
        'S-Pen inbegrepen voor notities en schetsen',
        'Prima batterijduur voor dagelijks gebruik'
    ]],
    ['id'=>'tabs9','name'=>'Galaxy Tab S9','category'=>'Tablet','tier'=>'high','size'=>'12–13"','strengths'=>['Snelheid','Beeldkwaliteit'],'uses'=>['Video/foto','Allround'],'image'=>null,'price'=>999,'why'=>[
        'Top-OLED scherm en waterbestendig',
        'Krachtige SoC voor multitask en creatie'
    ]],

    // Monitors
    ['id'=>'dell24','name'=>'Dell 24" IPS','category'=>'Monitor','tier'=>'low','size'=>'24–25"','strengths'=>['Prijs','Beeldkwaliteit'],'uses'=>['Allround','Studie/werk'],'image'=>null,'price'=>179,'why'=>[
        'IPS-paneel met goede kleurweergave voor weinig geld',
        'Dunne randen, fijn voor dual-setup'
    ]],
    ['id'=>'lg27','name'=>'LG 27GP850 165Hz','category'=>'Monitor','tier'=>'mid','size'=>'27"','strengths'=>['Beeldkwaliteit','Snelheid'],'uses'=>['Gaming'],'image'=>null,'price'=>329,'why'=>[
        '165Hz en snelle responstijd – soepel gamen',
        'Uitstekende kleuren na calibratie'
    ]],
    ['id'=>'lg34','name'=>'LG 34" Ultrawide','category'=>'Monitor','tier'=>'high','size'=>'34"+','strengths'=>['Beeldkwaliteit'],'uses'=>['Video/foto','Studie/werk'],'image'=>null,'price'=>799,'why'=>[
        'Ultrawide werkt sneller: meer tijdlijn/vensters',
        'Hoge resolutie en goede uniformiteit'
    ]],
];

// Detail? /api/products.php?id=...
if (isset($_GET['id'])) {
    $id = (string)$_GET['id'];
    foreach ($products as $p) {
        if ($p['id'] === $id) { echo json_encode($p); exit; }
    }
    http_response_code(404);
    echo json_encode(['error'=>'Product niet gevonden.']); exit;
}

echo json_encode($products);
