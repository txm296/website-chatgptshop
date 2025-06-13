<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
require '../inc/db.php';
require '../inc/admin_header.php';

$gesamt = $pdo->query("SELECT COUNT(*) AS anzahl, SUM(summe) AS umsatz FROM bestellungen")->fetch(PDO::FETCH_ASSOC);
$tage = $pdo->query("SELECT DATE(zeitstempel) AS tag, COUNT(*) AS anzahl, SUM(summe) AS umsatz FROM bestellungen GROUP BY DATE(zeitstempel) ORDER BY tag DESC LIMIT 7")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Insights – nezbi Admin</title>
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
    <?php admin_header('insights'); ?>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8">Statistiken</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="text-gray-400">Bestellungen gesamt</div>
                <div class="text-3xl font-bold"><?= $gesamt['anzahl'] ?? 0 ?></div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="text-gray-400">Umsatz gesamt</div>
                <div class="text-3xl font-bold"><?= number_format($gesamt['umsatz'] ?? 0,2,',','.') ?> €</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="text-gray-400">Durchschnitt pro Bestellung</div>
                <div class="text-3xl font-bold">
                    <?php
                    $avg = ($gesamt['anzahl'] ?? 0) > 0 ? $gesamt['umsatz']/$gesamt['anzahl'] : 0;
                    echo number_format($avg,2,',','.');
                    ?> €
                </div>
            </div>
        </div>
        <h2 class="text-xl font-bold mb-4">Letzte 7 Tage</h2>
        <div class="overflow-x-auto">
        <table class="w-full bg-white shadow rounded-xl min-w-max">
            <thead class="bg-gray-100">
                <tr><th class="p-2 text-left">Tag</th><th class="p-2">Bestellungen</th><th class="p-2">Umsatz</th></tr>
            </thead>
            <tbody>
                <?php foreach ($tage as $t): ?>
                <tr class="border-t">
                    <td class="p-2"><?= $t['tag'] ?></td>
                    <td class="p-2 text-center"><?= $t['anzahl'] ?></td>
                    <td class="p-2 text-right"><?= number_format($t['umsatz'],2,',','.') ?> €</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </main>
</body>
</html>
