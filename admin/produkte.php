<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
require '../inc/db.php';

// Aktionen für Produkte verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO produkte (name, beschreibung, preis, bild, menge, aktiv) VALUES (?,?,?,?,?,?)");
        $menge = isset($_POST['menge']) && $_POST['menge'] !== ''
            ? intval($_POST['menge'])
            : null;
        $stmt->execute([
            $_POST['name'] ?? '',
            $_POST['beschreibung'] ?? '',
            floatval($_POST['preis'] ?? 0),
            $_POST['bild'] ?? '',
            $menge,
            isset($_POST['aktiv']) ? 1 : 0
        ]);
    } elseif ($action === 'update' && isset($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE produkte SET preis=?, menge=? WHERE id=?");
        $menge = isset($_POST['menge']) && $_POST['menge'] !== ''
            ? intval($_POST['menge'])
            : null;
        $stmt->execute([
            floatval($_POST['preis'] ?? 0),
            $menge,
            intval($_POST['id'])
        ]);
    } elseif ($action === 'delete' && isset($_POST['id'])) {
        $stmt = $pdo->prepare("DELETE FROM produkte WHERE id=?");
        $stmt->execute([intval($_POST['id'])]);
    }
}

$produkte = $pdo->query("SELECT * FROM produkte ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Produkte verwalten – nezbi Admin</title>
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
                <a href="dashboard.php" class="hover:text-blue-600">Dashboard</a>
                <a href="produkte.php" class="font-bold text-blue-600">Produkte</a>
                <a href="rabattcodes.php" class="hover:text-blue-600">Rabatte</a>
                <a href="bestellungen.php" class="hover:text-blue-600">Bestellungen</a>
                <a href="insights.php" class="hover:text-blue-600">Insights</a>
            </nav>
            <a href="logout.php" class="inline-block rounded-xl px-4 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 transition">Logout</a>
        </div>
    </header>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8">Produkte</h1>
        <form method="post" class="bg-white shadow rounded-xl p-6 mb-10">
            <input type="hidden" name="action" value="add">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input name="name" class="border px-3 py-2 rounded" placeholder="Name" required>
                <input name="preis" type="number" step="0.01" class="border px-3 py-2 rounded" placeholder="Preis" required>
                <input name="menge" type="number" min="0" class="border px-3 py-2 rounded" placeholder="Verfügbare Menge (optional)">
                <input name="bild" class="border px-3 py-2 rounded md:col-span-2" placeholder="Bildpfad z.B. assets/bild.jpg" required>
                <textarea name="beschreibung" class="border px-3 py-2 rounded md:col-span-2" placeholder="Beschreibung"></textarea>
                <label class="flex items-center gap-2 md:col-span-2"><input type="checkbox" name="aktiv" checked> Aktiv</label>
            </div>
            <button class="mt-4 px-5 py-2 rounded-xl bg-blue-600 text-white">Produkt hinzufügen</button>
        </form>
        <table class="w-full bg-white shadow rounded-xl">
            <thead class="bg-gray-100">
                <tr><th class="p-2 text-left">ID</th><th class="p-2 text-left">Name</th><th class="p-2">Preis</th><th class="p-2">Menge</th><th class="p-2">Aktiv</th><th class="p-2">Aktionen</th></tr>
            </thead>
            <tbody>
                <?php foreach ($produkte as $p): ?>
                <form id="u<?= $p['id'] ?>" method="post"></form>
                <tr class="border-t">
                    <td class="p-2"><?= $p['id'] ?></td>
                    <td class="p-2"><?= htmlspecialchars($p['name']) ?></td>
                    <td class="p-2">
                        <input form="u<?= $p['id'] ?>" type="hidden" name="action" value="update">
                        <input form="u<?= $p['id'] ?>" type="hidden" name="id" value="<?= $p['id'] ?>">
                        <input form="u<?= $p['id'] ?>" name="preis" type="number" step="0.01" value="<?= number_format($p['preis'],2,'.','') ?>" class="border px-2 py-1 rounded w-24 text-right">
                    </td>
                    <td class="p-2">
                        <input form="u<?= $p['id'] ?>" name="menge" type="number" min="0" value="<?= htmlspecialchars($p['menge']) ?>" class="border px-2 py-1 rounded w-20" placeholder="frei">
                    </td>
                    <td class="p-2 text-center"><?= $p['aktiv'] ? 'Ja' : 'Nein' ?></td>
                    <td class="p-2">
                        <button form="u<?= $p['id'] ?>" class="px-3 py-1 bg-blue-600 text-white rounded mr-2">Speichern</button>
                        <form method="post" onsubmit="return confirm('Produkt wirklich l\u00f6schen?')" class="inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button class="px-3 py-1 bg-red-600 text-white rounded">Löschen</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
