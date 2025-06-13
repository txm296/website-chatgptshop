<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/db.php';
require '../inc/admin_header.php';
$pages = $pdo->query("SELECT * FROM pages ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Seiten verwalten – nezbi Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 text-gray-900">
<?php admin_header('seiten'); ?>
<main class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-8">Seiten</h1>
    <a href="page_builder.php" class="mb-4 inline-block px-4 py-2 bg-blue-600 text-white rounded">Neue Seite</a>
    <div class="overflow-x-auto">
    <table class="w-full bg-white shadow rounded-xl">
        <thead class="bg-gray-100"><tr><th class="p-2 text-left">ID</th><th class="p-2 text-left">Titel</th><th class="p-2 text-left">Slug</th><th class="p-2">Aktionen</th></tr></thead>
        <tbody>
            <?php foreach($pages as $p): ?>
            <tr class="border-t">
                <td class="p-2"><?= $p['id'] ?></td>
                <td class="p-2"><?= htmlspecialchars($p['title']) ?></td>
                <td class="p-2"><?= htmlspecialchars($p['slug']) ?></td>
                <td class="p-2">
                    <a href="page_builder.php?id=<?= $p['id'] ?>" class="px-3 py-1 bg-blue-600 text-white rounded mr-2">Bearbeiten</a>
                    <form method="post" action="edit_page.php" onsubmit="return confirm('Seite wirklich löschen?')" class="inline">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="action" value="delete">
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
