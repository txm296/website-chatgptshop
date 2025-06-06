<?php require 'inc/db.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>nezbi – Elektronik Onlineshop</title>
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
    <header class="bg-white border-b shadow-sm">
        <div class="max-w-5xl mx-auto flex justify-between items-center py-6 px-4">
            <span class="text-2xl font-extrabold tracking-tight">nezbi</span>
            <nav class="space-x-8 hidden md:flex">
                <a href="/" class="hover:text-blue-600">Home</a>
                <a href="#produkte" class="hover:text-blue-600">Produkte</a>
                <a href="#about" class="hover:text-blue-600">Über</a>
            </nav>
            <a href="warenkorb.php" class="inline-block rounded-xl px-4 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 transition">Warenkorb</a>
        </div>
    </header>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold mb-8">Unsere Elektronik-Highlights</h1>
        <div class="grid gap-8 grid-cols-1 md:grid-cols-3" id="produkte">
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
    </main>
    <footer class="py-10 text-center text-gray-400 text-xs">© 2025 nezbi</footer>
</body>
</html>
