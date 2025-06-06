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
    <nav class="bg-white py-4 shadow mb-8">
        <div class="max-w-5xl mx-auto flex gap-8">
            <a href="dashboard.php" class="font-bold text-blue-600">Dashboard</a>
            <a href="produkte.php">Produkte</a>
            <a href="rabattcodes.php">Rabatte</a>
            <a href="bestellungen.php">Bestellungen</a>
            <a href="insights.php">Insights</a>
            <a href="logout.php" class="ml-auto text-gray-400">Logout</a>
        </div>
    </nav>
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
