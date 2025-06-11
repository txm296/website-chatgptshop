<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
require '../inc/db.php';
require '../inc/settings.php';
$siteSettings = load_settings();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – nezbi Admin</title>
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
    <style>
      body { font-family: 'Inter', sans-serif; }
      :root { --accent-color: <?= htmlspecialchars($siteSettings['primary_color'] ?? '#2563eb') ?>; }
      .accent-bg { background-color: var(--accent-color); }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <header class="bg-white border-b shadow-sm">
        <div class="max-w-5xl mx-auto flex justify-between items-center py-6 px-4">
            <span class="text-2xl font-extrabold tracking-tight">nezbi Admin</span>
            <div class="flex items-center">
                <button id="menuBtn" class="md:hidden mr-4 text-gray-600" aria-label="Menü öffnen">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="logout.php" class="inline-block rounded-xl px-4 py-2 accent-bg text-white font-medium hover:opacity-90 transition">Logout</a>
            </div>
        </div>
        <nav id="navLinks" class="hidden flex-col space-y-2 px-4 pb-4 md:flex md:flex-row md:space-y-0 md:space-x-8 md:max-w-5xl md:mx-auto">
            <a href="dashboard.php" class="font-bold text-blue-600">Dashboard</a>
            <a href="produkte.php" class="hover:text-blue-600">Produkte</a>
            <a href="kategorien.php" class="hover:text-blue-600">Kategorien</a>
            <a href="rabattcodes.php" class="hover:text-blue-600">Rabatte</a>
            <a href="bestellungen.php" class="hover:text-blue-600">Bestellungen</a>
            <a href="insights.php" class="hover:text-blue-600">Insights</a>
            <a href="pages.php" class="hover:text-blue-600">Seiten</a>
        <a href="modular_builder.php" class="hover:text-blue-600">Builder</a>
            <a href="customize.php" class="hover:text-blue-600">Website bearbeiten</a>
            <a href="templates.php" class="hover:text-blue-600">Templates</a>
        </nav>
    </header>
    <script>
    document.addEventListener('DOMContentLoaded',function(){
        var b=document.getElementById('menuBtn');
        var n=document.getElementById('navLinks');
        if(b&&n){
            b.addEventListener('click',function(){
                n.classList.toggle('hidden');
            });
        }
    });
    </script>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-4">nezbi Admin Dashboard</h1>
        <a href="customize.php" class="inline-block mb-8 px-4 py-2 accent-bg text-white rounded-xl">Website bearbeiten</a>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Hier können Kacheln für Umsätze, Bestellungen, Produkte etc. dynamisch ergänzt werden -->
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="text-gray-400">Produkte</div>
                <div class="text-3xl font-bold">
                    <?php $res = $pdo->query("SELECT COUNT(*) FROM produkte"); echo $res->fetchColumn(); ?>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="text-gray-400">Bestellungen</div>
                <div class="text-3xl font-bold">
                    <?php $res = $pdo->query("SELECT COUNT(*) FROM bestellungen"); echo $res->fetchColumn(); ?>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="text-gray-400">Gesamtumsatz</div>
                <div class="text-3xl font-bold">
                    <?php $res = $pdo->query("SELECT SUM(summe) FROM bestellungen"); echo number_format($res->fetchColumn(),2,',','.'); ?> €
                </div>
            </div>
        </div>
    </main>
</body>
</html>
