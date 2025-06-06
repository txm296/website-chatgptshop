<?php
session_start();
require 'inc/db.php';
$active = '';
$pageTitle = 'Warenkorb – nezbi';
if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['produkt_id'])) {
        $id = intval($_POST['produkt_id']);
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
        header('Location: warenkorb.php');
        exit;
    } elseif (isset($_POST['update'])) {
        foreach ($_POST['qty'] as $id => $qty) {
            $qty = max(0, intval($qty));
            if ($qty > 0) {
                $_SESSION['cart'][$id] = $qty;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
        header('Location: warenkorb.php');
        exit;
    } elseif (isset($_POST['clear'])) {
        $_SESSION['cart'] = [];
        header('Location: warenkorb.php');
        exit;
    } elseif (isset($_POST['checkout'])) {
        $cart = $_SESSION['cart'];
        if ($cart) {
            $ids = implode(',', array_map('intval', array_keys($cart)));
            $stmt = $pdo->query("SELECT id, preis FROM produkte WHERE id IN ($ids)");
            $preisliste = [];
            foreach ($stmt as $row) { $preisliste[$row['id']] = $row['preis']; }
            $summe = 0;
            foreach ($cart as $cid => $qty) { $summe += ($preisliste[$cid] ?? 0) * $qty; }
            $pdo->prepare("INSERT INTO bestellungen (warenkorb, summe) VALUES (?,?)")
                ->execute([json_encode($cart), $summe]);
            $_SESSION['cart'] = [];
            $bestellt = true;
        }
    }
}

$items = [];
$total = 0;
$cart = $_SESSION['cart'];
if ($cart) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $stmt = $pdo->query("SELECT * FROM produkte WHERE id IN ($ids)");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $qty = $cart[$row['id']];
        $sub = $row['preis'] * $qty;
        $total += $sub;
        $items[] = ['data'=>$row,'qty'=>$qty,'sub'=>$sub];
    }
}

include 'inc/header.php';
?>
<h1 class="text-2xl font-bold mb-8">Warenkorb</h1>
<?php if(($bestellt ?? false)): ?>
<p class="mb-6 bg-green-100 text-green-800 p-4 rounded">Vielen Dank f&uuml;r deine Bestellung!</p>
<?php endif; ?>
<?php if(!$items): ?>
<p>Dein Warenkorb ist leer.</p>
<?php else: ?>
<form method="post">
<div class="overflow-x-auto">
<table class="w-full mb-6 min-w-max">
    <thead class="border-b font-semibold text-left">
        <tr><th class="p-2">Produkt</th><th class="p-2">Menge</th><th class="p-2 text-right">Preis</th></tr>
    </thead>
    <tbody>
    <?php foreach($items as $it): $p=$it['data']; ?>
        <tr class="border-b">
            <td class="p-2 flex items-center gap-3">
                <img src="<?=htmlspecialchars($p['bild'])?>" alt="" class="w-12 h-12 object-contain">
                <?=htmlspecialchars($p['name'])?>
            </td>
            <td class="p-2">
                <input type="number" name="qty[<?=$p['id']?>]" value="<?=$it['qty']?>" min="0" class="w-20 border rounded px-2 py-1">
            </td>
            <td class="p-2 text-right"><?=number_format($it['sub'],2,',','.')?> €</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<div class="text-right font-bold mb-6">Summe: <?=number_format($total,2,',','.')?> €</div>
<div class="flex justify-between gap-2">
    <button name="update" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Aktualisieren</button>
    <button name="clear" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Leeren</button>
    <button name="checkout" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Bestellen</button>
</div>
</form>
<?php endif; ?>
<?php include 'inc/footer.php'; ?>
