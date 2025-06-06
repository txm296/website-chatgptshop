<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: ../login.php'); exit; }
require '../inc/db.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – nezbi Admin</title>
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
        <div class="max-w-5xl mx-auto flex justify-between items-center py-6 px-4">
            <span class="text-2xl font-extrabold tracking-tight">nezbi Admin</span>
            <nav class="space-x-8 hidden md:flex">
                <a href="dashboard.php" class="font-bold text-blue-600">Dashboard</a>
                <a href="produkte.php" class="hover:text-blue-600">Produkte</a>
                <a href="rabattcodes.php" class="hover:text-blue-600">Rabatte</a>
                <a href="bestellungen.php" class="hover:text-blue-600">Bestellungen</a>
                <a href="insights.php" class="hover:text-blue-600">Insights</a>
            </nav>
            <a href="logout.php" class="inline-block rounded-xl px-4 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 transition">Logout</a>
        </div>
    </header>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8">nezbi Admin Dashboard</h1>
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
