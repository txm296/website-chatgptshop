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
    <header class="bg-white border-b shadow-sm">
        <div class="max-w-5xl mx-auto flex justify-between items-center py-6 px-4">
            <span class="text-2xl font-extrabold tracking-tight">nezbi Admin</span>
            <nav class="space-x-8 hidden md:flex">
                <a href="dashboard.php" class="hover:text-blue-600">Dashboard</a>
                <a href="produkte.php" class="hover:text-blue-600">Produkte</a>
                <a href="rabattcodes.php" class="hover:text-blue-600">Rabatte</a>
                <a href="bestellungen.php" class="font-bold text-blue-600">Bestellungen</a>
                <a href="insights.php" class="hover:text-blue-600">Insights</a>
            </nav>
            <a href="logout.php" class="inline-block rounded-xl px-4 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 transition">Logout</a>
        </div>
    </header>
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
