<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
require '../inc/db.php';
require '../inc/admin_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO kategorien (name) VALUES (?)");
        $stmt->execute([$_POST['name'] ?? '']);
    } elseif ($action === 'update' && isset($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE kategorien SET name=? WHERE id=?");
        $stmt->execute([$_POST['name'] ?? '', intval($_POST['id'])]);
    } elseif ($action === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $pdo->prepare("UPDATE produkte SET kategorie_id=NULL WHERE kategorie_id=?")->execute([$id]);
        $pdo->prepare("DELETE FROM kategorien WHERE id=?")->execute([$id]);
    }
}

$kategorien = $pdo->query("SELECT * FROM kategorien ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kategorien – nezbi Admin</title>
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
    <?php admin_header('kategorien'); ?>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8">Kategorien verwalten</h1>
        <form method="post" class="bg-white shadow rounded-xl p-6 mb-10">
            <input type="hidden" name="action" value="add">
            <div class="flex gap-4">
                <input name="name" class="border px-3 py-2 rounded flex-grow" placeholder="Name" required>
                <button class="px-5 py-2 rounded-xl bg-blue-600 text-white">Kategorie hinzufügen</button>
            </div>
        </form>
        <div class="overflow-x-auto">
        <table class="w-full bg-white shadow rounded-xl min-w-max">
            <thead class="bg-gray-100">
                <tr><th class="p-2 text-left">ID</th><th class="p-2 text-left">Name</th><th class="p-2">Aktionen</th></tr>
            </thead>
            <tbody>
                <?php foreach ($kategorien as $k): ?>
                <tr class="border-t">
                    <td class="p-2"><?= $k['id'] ?></td>
                    <td class="p-2">
                        <form id="u<?= $k['id'] ?>" method="post" class="inline">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?= $k['id'] ?>">
                            <input name="name" value="<?= htmlspecialchars($k['name']) ?>" class="border px-2 py-1 rounded">
                        </form>
                    </td>
                    <td class="p-2">
                        <button form="u<?= $k['id'] ?>" class="px-3 py-1 bg-blue-600 text-white rounded mr-2">Speichern</button>
                        <form method="post" onsubmit="return confirm('Kategorie wirklich löschen?')" class="inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $k['id'] ?>">
                            <button class="px-3 py-1 bg-red-600 text-white rounded">Löschen</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </main>
</body>
</html>
