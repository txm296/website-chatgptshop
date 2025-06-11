<?php
require __DIR__ . '/../../inc/db.php';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$stmt = $pdo->prepare('SELECT id, name FROM kategorien ORDER BY name LIMIT ?');
$stmt->execute([$limit]);
$cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<ul class="space-y-2">
<?php foreach ($cats as $cat): ?>
    <li><a href="/kategorie.php?id=<?=$cat['id']?>" class="text-blue-600 hover:underline"><?=htmlspecialchars($cat['name'])?></a></li>
<?php endforeach; ?>
</ul>
