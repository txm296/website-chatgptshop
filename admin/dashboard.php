<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
require '../inc/db.php';
require '../inc/admin_header.php';
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
    <?php admin_header('dashboard'); ?>
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
