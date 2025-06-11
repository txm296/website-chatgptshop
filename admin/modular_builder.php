<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/db.php';
require '../pagebuilder/builder.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$page = ['title' => '', 'slug' => '', 'layout' => ''];
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM builder_pages WHERE id=?');
    $stmt->execute([$id]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $page['title'] = $row['title'];
        $page['slug'] = $row['slug'];
        $d = json_decode($row['layout'], true);
        $page['layout'] = $d['html'] ?? '';
    }
}

$builder = new ModularPageBuilder();
$widgets = $builder->loadWidgets(__DIR__ . '/../pagebuilder/widgets');
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Modular Builder – nezbi Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<link rel="stylesheet" href="../pagebuilder/assets/builder.css">
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
        <a href="modular_builder.php" class="font-bold text-blue-600">Builder</a>
    </nav>
</header>
<main class="max-w-5xl mx-auto px-4 py-10">
<h1 class="text-2xl font-bold mb-8">Modularer Page Builder</h1>
<div class="mb-4 space-y-2">
    <input type="text" id="pbTitle" value="<?= htmlspecialchars($page['title']) ?>" placeholder="Titel" class="w-full border px-2 py-1 rounded">
    <input type="text" id="pbSlug" value="<?= htmlspecialchars($page['slug']) ?>" placeholder="Slug" class="w-full border px-2 py-1 rounded">
    <button type="button" id="pbSave" class="px-4 py-2 bg-blue-600 text-white rounded">Speichern</button>
    <div class="space-x-2 mt-2">
        <button type="button" class="pb-bp-btn px-2 py-1 bg-gray-200 rounded" data-bp="desktop">Desktop</button>
        <button type="button" class="pb-bp-btn px-2 py-1 bg-gray-200 rounded" data-bp="tablet">Tablet</button>
        <button type="button" class="pb-bp-btn px-2 py-1 bg-gray-200 rounded" data-bp="mobile">Mobile</button>
    </div>
</div>
<div class="flex">
    <div class="pb-canvas flex-1" id="builderCanvas" data-save-url="../pagebuilder/save_page.php" data-load-url="<?= $id ? '../pagebuilder/load_page.php?id='.$id : '' ?>" data-page-id="<?= $id ?>">
        <?= $id ? '' : $page['layout']; ?>
    </div>
    <div class="ml-4 w-40 text-sm space-y-2" id="widgetBar">
        <?php foreach($widgets as $name => $file): ?>
            <button type="button" class="w-full px-2 py-1 bg-gray-200 rounded" data-widget="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></button>
        <?php endforeach; ?>
    </div>
</div>
</main>
<script src="../pagebuilder/assets/builder.js"></script>
</body>
</html>
