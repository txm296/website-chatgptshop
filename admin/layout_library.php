<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/db.php';
if(isset($_GET['delete'])){
    $id=intval($_GET['delete']);
    $pdo->prepare('DELETE FROM builder_templates WHERE id=?')->execute([$id]);
    header('Location: layout_library.php');
    exit;
}
$templates=$pdo->query('SELECT id,name,html FROM builder_templates ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Vorlagenbibliothek – nezbi Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 text-gray-900">
<header class="bg-white border-b shadow-sm">
    <div class="max-w-5xl mx-auto flex justify-between items-center py-6 px-4">
        <span class="text-2xl font-extrabold tracking-tight">nezbi Admin</span>
        <div class="flex items-center">
            <a href="logout.php" class="inline-block rounded-xl px-4 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 transition">Logout</a>
        </div>
    </div>
    <nav class="flex space-x-8 max-w-5xl mx-auto px-4 pb-4">
        <a href="dashboard.php" class="hover:text-blue-600">Dashboard</a>
        <a href="pages.php" class="hover:text-blue-600">Seiten</a>
        <a href="live_builder.php" class="hover:text-blue-600">Builder</a>
        <a href="popup_builder.php" class="hover:text-blue-600">Popups</a>
        <a href="layout_library.php" class="font-bold text-blue-600">Layouts</a>
    </nav>
</header>
<main class="max-w-5xl mx-auto px-4 py-10 space-y-6">
<h1 class="text-2xl font-bold mb-8">Vorlagenbibliothek</h1>
<?php foreach($templates as $tpl): ?>
    <div class="border rounded-xl p-4 bg-white shadow flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold mb-2"><?=htmlspecialchars($tpl['name'])?></h3>
            <div class="border p-2 mb-2"><?= $tpl['html'] ?></div>
        </div>
        <a href="?delete=<?= $tpl['id'] ?>" class="text-red-600" onclick="return confirm('Vorlage löschen?');">Löschen</a>
    </div>
<?php endforeach; ?>
</main>
</body>
</html>
