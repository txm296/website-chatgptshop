<?php
require __DIR__ . '/../../inc/db.php';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 6;
$sql = "SELECT * FROM produkte WHERE aktiv=1";
$params = [];
if ($category > 0) { $sql .= " AND kategorie_id=?"; $params[] = $category; }
$sql .= " ORDER BY id DESC LIMIT ?";
$params[] = $limit;
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produkte = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="grid gap-8 grid-cols-1 md:grid-cols-3">
<?php foreach ($produkte as $prod): ?>
    <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition">
        <div class="relative w-40 h-40 mb-4">
            <?php if ($prod['rabatt'] && $prod['rabatt'] < $prod['preis']): ?>
                <span class="absolute top-1 left-1 z-10 bg-red-600 text-white text-xs px-2 py-1 rounded-br">Angebot</span>
            <?php endif; ?>
            <img src="<?=htmlspecialchars($prod['bild'])?>" alt="<?=htmlspecialchars($prod['name'])?>" class="w-full h-full object-contain" />
        </div>
        <div class="font-bold text-lg mb-1 text-center break-words"><?=htmlspecialchars($prod['name'])?></div>
        <div class="font-mono text-lg mb-4">
            <?php if ($prod['rabatt'] && $prod['rabatt'] < $prod['preis']): ?>
                <span class="line-through mr-2 text-gray-500"><?=number_format($prod['preis'],2,',','.')?> €</span>
                <span class="text-red-600 font-bold"><?=number_format($prod['rabatt'],2,',','.')?> €</span>
            <?php else: ?>
                <?=number_format($prod['preis'],2,',','.')?> €
            <?php endif; ?>
        </div>
        <a href="/produkt.php?id=<?=$prod['id']?>" class="mt-auto px-5 py-2 rounded-xl accent-bg text-white font-semibold tracking-wide accent-bg-hover transition">Details</a>
    </div>
<?php endforeach; ?>
</div>
