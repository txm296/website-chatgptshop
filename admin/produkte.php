<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
require '../inc/db.php';

// Produkt anlegen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO produkte (name, beschreibung, preis, bild, aktiv) VALUES (?,?,?,?,?)");
    $stmt->execute([
        $_POST['name'] ?? '',
        $_POST['beschreibung'] ?? '',
        floatval($_POST['preis'] ?? 0),
        $_POST['bild'] ?? '',
        isset($_POST['aktiv']) ? 1 : 0
    ]);
}

$produkte = $pdo->query("SELECT * FROM produkte ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Produkte verwalten – nezbi Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-900">
    <nav class="bg-white py-4 shadow mb-8">
        <div class="max-w-5xl mx-auto flex gap-8">
            <a href="dashboard.php" class="hover:text-blue-600">Dashboard</a>
            <a href="produkte.php" class="font-bold text-blue-600">Produkte</a>
            <a href="rabattcodes.php">Rabatte</a>
            <a href="bestellungen.php">Bestellungen</a>
            <a href="insights.php">Insights</a>
            <a href="logout.php" class="ml-auto text-gray-400">Logout</a>
        </div>
    </nav>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8">Produkte</h1>
        <form method="post" class="bg-white shadow rounded-xl p-6 mb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input name="name" class="border px-3 py-2 rounded" placeholder="Name" required>
                <input name="preis" type="number" step="0.01" class="border px-3 py-2 rounded" placeholder="Preis" required>
                <input name="bild" class="border px-3 py-2 rounded md:col-span-2" placeholder="Bildpfad z.B. assets/bild.jpg" required>
                <textarea name="beschreibung" class="border px-3 py-2 rounded md:col-span-2" placeholder="Beschreibung"></textarea>
                <label class="flex items-center gap-2 md:col-span-2"><input type="checkbox" name="aktiv" checked> Aktiv</label>
            </div>
            <button class="mt-4 px-5 py-2 rounded-xl bg-blue-600 text-white">Produkt hinzufügen</button>
        </form>
        <table class="w-full bg-white shadow rounded-xl">
            <thead class="bg-gray-100">
                <tr><th class="p-2 text-left">ID</th><th class="p-2 text-left">Name</th><th class="p-2">Preis</th><th class="p-2">Aktiv</th></tr>
            </thead>
            <tbody>
                <?php foreach ($produkte as $p): ?>
                <tr class="border-t">
                    <td class="p-2"><?= $p['id'] ?></td>
                    <td class="p-2"><?= htmlspecialchars($p['name']) ?></td>
                    <td class="p-2 text-right"><?= number_format($p['preis'],2,',','.') ?> €</td>
                    <td class="p-2 text-center"><?= $p['aktiv'] ? 'Ja' : 'Nein' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
