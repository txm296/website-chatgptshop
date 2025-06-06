<?php
require 'inc/db.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM produkte WHERE id=?");
$stmt->execute([$id]);
$prod = $stmt->fetch();
if (!$prod) { http_response_code(404); exit('Produkt nicht gefunden.'); }
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?=htmlspecialchars($prod['name'])?> – nezbi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php /* ggf. PHP-Header, z. B. require/include usw. */ ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>nezbi – Elektronik Onlineshop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- TailwindCSS offiziell per CDN-JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
          }
        }
      }
    </script>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-900">
<!-- ...restlicher Inhalt bleibt unverändert... -->

    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-900">
<main class="max-w-3xl mx-auto px-4 py-10">
    <div class="flex flex-col md:flex-row gap-8">
        <img src="assets/<?=htmlspecialchars($prod['bild'])?>" alt="<?=htmlspecialchars($prod['name'])?>" class="w-full md:w-80 object-contain rounded-xl bg-white shadow"/>
        <div>
            <h1 class="text-2xl font-bold mb-2"><?=htmlspecialchars($prod['name'])?></h1>
            <div class="mb-3 text-gray-500"><?=htmlspecialchars($prod['beschreibung'])?></div>
            <div class="mb-5 text-lg font-mono"><?=number_format($prod['preis'],2,',','.')?> €</div>
            <form method="post" action="warenkorb.php">
                <input type="hidden" name="produkt_id" value="<?=$prod['id']?>">
                <button type="submit" class="w-full px-5 py-2 mt-4 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">In den Warenkorb</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>
