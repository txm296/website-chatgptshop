<?php require 'inc/db.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>nezbi – Elektronik Onlineshop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
<header class="bg-white border-b shadow-sm">
    <div class="max-w-6xl mx-auto flex justify-between items-center py-6 px-4">
        <span class="text-2xl font-extrabold tracking-tight">nezbi</span>
        <nav class="space-x-8 hidden md:flex">
            <a href="/" class="hover:text-blue-600">Home</a>
            <a href="#produkte" class="hover:text-blue-600">Produkte</a>
            <a href="#about" class="hover:text-blue-600">Über</a>
        </nav>
        <a href="warenkorb.php" class="inline-block rounded-xl px-4 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 transition">Warenkorb</a>
    </div>
</header>
<main class="max-w-6xl mx-auto px-4">
    <section class="text-center py-24">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-6">Technologie neu erleben</h1>
        <p class="text-xl md:text-2xl text-gray-600 mb-8">Premium Elektronik f&uuml;r deinen Alltag</p>
        <a href="#produkte" class="px-8 py-3 rounded-xl bg-black text-white hover:bg-gray-800 transition">Jetzt entdecken</a>
    </section>
    <section class="grid grid-cols-1 md:grid-cols-3 gap-8 py-16">
        <div class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h3l3 9v11a1 1 0 01-1 1h-2a1 1 0 01-1-1v-5H5a1 1 0 01-1-1V4z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4h3a1 1 0 011 1v13a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
            <h3 class="text-lg font-semibold mt-4 mb-2">Schneller Versand</h3>
            <p class="text-gray-600">Innerhalb von 24h bei dir zu Hause</p>
        </div>
        <div class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3-1.79 3-4s-1.343-4-3-4-3 1.79-3 4 1.343 4 3 4zM12 14.93v7.07"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.343 14.657A8 8 0 1117.657 3.343"/></svg>
            <h3 class="text-lg font-semibold mt-4 mb-2">Zeitloses Design</h3>
            <p class="text-gray-600">Minimalistisch und ansprechend</p>
        </div>
        <div class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1l3 9H9l3-9zM12 22v-7"/></svg>
            <h3 class="text-lg font-semibold mt-4 mb-2">Sichere Zahlung</h3>
            <p class="text-gray-600">Verschl&uuml;sselte Transaktionen</p>
        </div>
    </section>
    <section id="produkte" class="py-16">
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
    <section id="about" class="py-24 text-center">
        <h2 class="text-3xl font-bold mb-4">&Uuml;ber nezbi</h2>
        <p class="max-w-3xl mx-auto text-gray-600">Dein Onlineshop f&uuml;r ausgew&auml;hlte Technikprodukte. Wir lieben hochwertiges Design und leistungsstarke Hardware.</p>
    </section>
</main>
<footer class="py-10 text-center text-gray-400 text-xs">© 2025 nezbi</footer>
</body>
</html>
