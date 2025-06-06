<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
require '../inc/db.php';

$bestellungen = $pdo->query("SELECT * FROM bestellungen ORDER BY zeitstempel DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bestellungen – nezbi Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-900">
    <nav class="bg-white py-4 shadow mb-8">
        <div class="max-w-5xl mx-auto flex gap-8">
            <a href="dashboard.php">Dashboard</a>
            <a href="produkte.php">Produkte</a>
            <a href="rabattcodes.php">Rabatte</a>
            <a href="bestellungen.php" class="font-bold text-blue-600">Bestellungen</a>
            <a href="insights.php">Insights</a>
            <a href="logout.php" class="ml-auto text-gray-400">Logout</a>
        </div>
    </nav>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8">Bestellungen</h1>
        <table class="w-full bg-white shadow rounded-xl">
            <thead class="bg-gray-100">
                <tr><th class="p-2 text-left">ID</th><th class="p-2">Summe</th><th class="p-2">Zeit</th></tr>
            </thead>
            <tbody>
                <?php foreach ($bestellungen as $b): ?>
                <tr class="border-t">
                    <td class="p-2"><?= $b['id'] ?></td>
                    <td class="p-2 text-right"><?= number_format($b['summe'],2,',','.') ?> €</td>
                    <td class="p-2 text-right"><?= $b['zeitstempel'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
