<?php
require 'inc/db.php';
$active = 'produkte';
$pageTitle = 'Produkte – nezbi';
include 'inc/header.php';
?>
<section class="py-16">
    <h2 class="text-3xl font-bold mb-8 text-center">Unsere Elektronik-Highlights</h2>
    <div class="grid gap-8 grid-cols-1 md:grid-cols-3">
    <?php
    $res = $pdo->query("SELECT * FROM produkte WHERE aktiv=1 ORDER BY id DESC");
    foreach ($res as $prod):
    ?>
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition">
            <img src="assets/<?=htmlspecialchars($prod['bild'])?>" alt="<?=htmlspecialchars($prod['name'])?>" class="w-40 h-40 object-contain mb-4" />
            <div class="font-bold text-lg mb-1"><?=htmlspecialchars($prod['name'])?></div>
            <div class="text-gray-500 mb-2"><?=htmlspecialchars($prod['beschreibung'])?></div>
            <div class="font-mono text-lg mb-4"><?=number_format($prod['preis'],2,',','.')?> €</div>
            <a href="produkt.php?id=<?=$prod['id']?>" class="mt-auto px-5 py-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 text-white font-semibold tracking-wide hover:shadow-lg transition">Details</a>
        </div>
    <?php endforeach; ?>
    </div>
</section>
<?php include 'inc/footer.php'; ?>
