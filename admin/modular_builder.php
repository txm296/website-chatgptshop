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
$pageOptions = [ 'home' => 'Startseite' ];
foreach ($pdo->query('SELECT id, name FROM kategorien ORDER BY name') as $row) {
    $pageOptions['category-' . $row['id']] = 'Kategorie: ' . $row['name'];
}
foreach ($pdo->query('SELECT slug, title FROM pages ORDER BY title') as $row) {
    $pageOptions[$row['slug']] = $row['title'];
}
foreach ($pdo->query('SELECT slug, title FROM builder_pages ORDER BY title') as $row) {
    if (!isset($pageOptions[$row['slug']])) {
        $pageOptions[$row['slug']] = $row['title'];
    }
}
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
<link rel="stylesheet" href="../assets/animations.css">
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
        <a href="popup_builder.php" class="hover:text-blue-600">Popups</a>
        <a href="layout_library.php" class="hover:text-blue-600">Layouts</a>
    </nav>
</header>
<main class="max-w-5xl mx-auto px-4 py-10">
<h1 class="text-2xl font-bold mb-8">Modularer Page Builder</h1>
<div class="mb-6 space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center gap-2">
        <input type="text" id="pbPageSearch" placeholder="Seite suchen..." class="flex-1 border px-2 py-1 rounded">
        <select id="pbPageSelect" class="border px-2 py-1 rounded w-full sm:w-60">
            <option value="">Neue Seite...</option>
            <?php foreach($pageOptions as $slug => $title): ?>
                <option value="<?= htmlspecialchars($slug) ?>" <?= $slug === $page['slug'] ? 'selected' : '' ?>><?= htmlspecialchars($title) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <input type="text" id="pbTitle" value="<?= htmlspecialchars($page['title']) ?>" placeholder="Titel" class="w-full border px-2 py-1 rounded">
        <input type="text" id="pbSlug" value="<?= htmlspecialchars($page['slug']) ?>" placeholder="Slug" class="w-full border px-2 py-1 rounded">
    </div>
    <div class="pb-btn-group">
        <button type="button" id="pbSave" class="pb-btn pb-btn-primary">Speichern</button>
        <button type="button" id="pbOptimizeMobile" class="pb-btn pb-btn-secondary">Für Mobile optimieren</button>
        <button type="button" id="pbUndoMobile" class="pb-btn pb-btn-warning hidden">Undo</button>
    </div>
    <div class="flex justify-center gap-2 mt-4">
        <button type="button" class="pb-bp-btn" data-bp="desktop">Desktop</button>
        <button type="button" class="pb-bp-btn" data-bp="tablet">Tablet</button>
        <button type="button" class="pb-bp-btn" data-bp="mobile">Mobile</button>
    </div>
</div>
<div class="flex flex-col md:flex-row">
    <div class="md:w-60 w-full md:mr-4 mb-4 md:mb-0 space-y-4" id="leftPanel">
        <div id="pbConfigPanel" class="pb-config"></div>
        <div class="space-y-2 text-sm">
            <button type="button" id="pbPaste" class="pb-btn pb-btn-secondary w-full">Einfügen</button>
            <select id="pbTemplateSelect" class="border px-2 py-1 rounded w-full"></select>
            <button type="button" id="pbInsertTemplate" class="pb-btn pb-btn-secondary w-full">Vorlage einfügen</button>
            <a href="layout_library.php" class="block text-center text-blue-600">Vorlagen verwalten</a>
        </div>
        <div class="text-sm space-y-2" id="widgetBar">
            <?php foreach($widgets as $name => $file): ?>
                <button type="button" class="pb-btn pb-btn-secondary w-full" data-widget="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="pb-canvas flex-1 border" id="builderCanvas" data-save-url="../pagebuilder/save_page.php" data-load-url="<?= $id ? '../pagebuilder/load_page.php?id='.$id : '' ?>" data-page-id="<?= $id ?>">
        <?= $id ? '' : $page['layout']; ?>
    </div>
</div>
</main>
<script src="../pagebuilder/assets/builder.js"></script>
<script src="../assets/dynamic-widgets.js"></script>
</body>
</html>
