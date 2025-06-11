<?php
require 'inc/db.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM produkte WHERE id=?");
$stmt->execute([$id]);
$prod = $stmt->fetch();
if (!$prod) { http_response_code(404); exit('Produkt nicht gefunden.'); }
$pageTitle = htmlspecialchars($prod['name']) . ' – nezbi';
$currentSlug = 'product-' . $id;
include 'inc/header.php';
?>
    <div class="flex flex-col md:flex-row gap-8">
        <div class="relative">
            <?php if ($prod['rabatt'] && $prod['rabatt'] < $prod['preis']): ?>
                <span class="absolute top-1 left-1 z-10 bg-red-600 text-white text-xs px-2 py-1 rounded-br">Angebot</span>
            <?php endif; ?>
            <img src="<?=htmlspecialchars($prod['bild'])?>" alt="<?=htmlspecialchars($prod['name'])?>" class="w-full md:w-80 object-contain rounded-xl bg-white shadow"/>
        </div>
        <div>
            <h1 class="text-2xl font-bold mb-2"><?=htmlspecialchars($prod['name'])?></h1>
            <div class="mb-3 text-gray-500"><?=htmlspecialchars($prod['beschreibung'])?></div>
            <div class="mb-5 text-lg font-mono">
                <?php if ($prod['rabatt'] && $prod['rabatt'] < $prod['preis']): ?>
                    <span class="line-through mr-2 text-gray-500"><?=number_format($prod['preis'],2,',','.')?> €</span>
                    <span class="text-red-600 font-bold"><?=number_format($prod['rabatt'],2,',','.')?> €</span>
                <?php else: ?>
                    <?=number_format($prod['preis'],2,',','.')?> €
                <?php endif; ?>
            </div>
            <form method="post" action="warenkorb.php">
                <input type="hidden" name="produkt_id" value="<?=$prod['id']?>">
                <button type="submit" class="w-full px-5 py-2 mt-4 rounded-xl accent-bg text-white font-semibold accent-bg-hover transition">In den Warenkorb</button>
            </form>
        </div>
    </div>
<?php include 'inc/footer.php'; ?>
