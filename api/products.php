<?php
// api/products.php — simpele webservice voor producten

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Alleen GET toegestaan.']);
    exit;
}

/**
 * Dummy dataset – vervang dit met je eigen bron
 * of laad vanuit include/database.php en map naar deze structuur.
 * Benodigde velden voor de widget:
 * - id, name, category, size, tier(low|mid|high), strengths[], uses[], image(optional), price(optional)
 */
$products = [
    // Laptops
    ['id'=>'xps13','name'=>'Dell XPS 13','category'=>'Laptop','tier'=>'mid','size'=>'13–14"','strengths'=>['Draagbaarheid','Batterijduur'],'uses'=>['Studie/werk','Allround'],'image'=>null,'price'=>1299],
    ['id'=>'mbp14','name'=>'MacBook Pro 14"','category'=>'Laptop','tier'=>'high','size'=>'15–16"','strengths'=>['Snelheid','Beeldkwaliteit','Batterijduur'],'uses'=>['Video/foto','Studie/werk'],'image'=>null,'price'=>2299],
    ['id'=>'helios','name'=>'Predator Helios 300','category'=>'Laptop','tier'=>'high','size'=>'15–16"','strengths'=>['Snelheid','Beeldkwaliteit'],'uses'=>['Gaming'],'image'=>null,'price'=>1699],
    ['id'=>'aspire3','name'=>'Acer Aspire 3','category'=>'Laptop','tier'=>'low','size'=>'15–16"','strengths'=>['Prijs'],'uses'=>['Allround','Studie/werk'],'image'=>null,'price'=>449],
    ['id'=>'ideapad5','name'=>'Lenovo IdeaPad 5','category'=>'Laptop','tier'=>'mid','size'=>'15–16"','strengths'=>['Allround'],'uses'=>['Allround','Studie/werk'],'image'=>null,'price'=>899],

    // Tablets
    ['id'=>'ipad9','name'=>'iPad (9e gen)','category'=>'Tablet','tier'=>'low','size'=>'10–11"','strengths'=>['Prijs','Batterijduur'],'uses'=>['Allround','Studie/werk'],'image'=>null,'price'=>349],
    ['id'=>'ipadair','name'=>'iPad Air','category'=>'Tablet','tier'=>'mid','size'=>'10–11"','strengths'=>['Beeldkwaliteit','Draagbaarheid'],'uses'=>['Video/foto','Allround'],'image'=>null,'price'=>699],
    ['id'=>'tabs6','name'=>'Galaxy Tab S6 Lite','category'=>'Tablet','tier'=>'low','size'=>'10–11"','strengths'=>['Prijs','Draagbaarheid'],'uses'=>['Allround','Studie/werk'],'image'=>null,'price'=>299],
    ['id'=>'tabs9','name'=>'Galaxy Tab S9','category'=>'Tablet','tier'=>'high','size'=>'12–13"','strengths'=>['Snelheid','Beeldkwaliteit'],'uses'=>['Video/foto','Allround'],'image'=>null,'price'=>999],

    // Monitors
    ['id'=>'dell24','name'=>'Dell 24" IPS','category'=>'Monitor','tier'=>'low','size'=>'24–25"','strengths'=>['Prijs','Beeldkwaliteit'],'uses'=>['Allround','Studie/werk'],'image'=>null,'price'=>179],
    ['id'=>'lg27','name'=>'LG 27GP850 165Hz','category'=>'Monitor','tier'=>'mid','size'=>'27"','strengths'=>['Beeldkwaliteit','Snelheid'],'uses'=>['Gaming'],'image'=>null,'price'=>329],
    ['id'=>'lg34','name'=>'LG 34" Ultrawide','category'=>'Monitor','tier'=>'high','size'=>'34"+','strengths'=>['Beeldkwaliteit'],'uses'=>['Video/foto','Studie/werk'],'image'=>null,'price'=>799],
];

// Detail? /api/products.php?id=mbp14
if (isset($_GET['id'])) {
    $id = (string)$_GET['id'];
    foreach ($products as $p) {
        if ($p['id'] === $id) { echo json_encode($p); exit; }
    }
    http_response_code(404);
    echo json_encode(['error'=>'Product niet gevonden.']);
    exit;
}

// Zonder id: geef de volledige lijst (handig voor ranking)
echo json_encode($products);
