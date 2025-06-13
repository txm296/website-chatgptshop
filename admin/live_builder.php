<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/db.php';
require_once '../inc/admin_header.php';
require_once '../pagebuilder/builder.php';

$builder = new ModularPageBuilder();
$widgets = $builder->loadWidgets(__DIR__ . '/../pagebuilder/widgets');

$slug = $_GET['slug'] ?? '';
$title = '';
$id = 0;
$layout = '';
if($slug){
    $stmt = $pdo->prepare('SELECT * FROM builder_pages WHERE slug=?');
    $stmt->execute([$slug]);
    if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $title = $row['title'];
        $id = $row['id'];
        $data = json_decode($row['layout'], true);
        $layout = $data['html'] ?? '';
    }
}

$pageOptions = [];
foreach($pdo->query('SELECT slug,title FROM pages ORDER BY title') as $row){
    $pageOptions[$row['slug']] = $row['title'];
}
foreach($pdo->query('SELECT slug,title FROM builder_pages ORDER BY title') as $row){
    if(!isset($pageOptions[$row['slug']])) $pageOptions[$row['slug']] = $row['title'];
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Live Editor – nezbi Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<link rel="stylesheet" href="../assets/live-editor.css">
<style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 text-gray-900">
<?php admin_header('builder'); ?>
<main class="max-w-full mx-auto p-4">
<div class="flex h-screen overflow-hidden">
    <aside class="w-64 pr-4 space-y-4" id="editorSidebar">
        <select id="pageSelect" class="w-full border px-2 py-1 rounded">
            <option value="">Seite wählen...</option>
            <?php foreach($pageOptions as $pslug=>$ptitle): ?>
                <option value="<?= htmlspecialchars($pslug) ?>" <?= $pslug===$slug?'selected':'' ?>><?= htmlspecialchars($ptitle) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" id="pageTitle" class="w-full border px-2 py-1 rounded" placeholder="Titel" value="<?= htmlspecialchars($title) ?>">
        <div class="space-y-2" id="widgetBar">
            <?php foreach($widgets as $wname=>$wfile): ?>
                <button type="button" class="pb-widget-btn" data-widget="<?= htmlspecialchars($wname) ?>"><?= htmlspecialchars($wname) ?></button>
            <?php endforeach; ?>
        </div>
        <div id="configPanel" class="pb-config"></div>
        <div class="space-y-2">
            <button type="button" id="savePage" class="pb-btn pb-btn-primary w-full">Speichern</button>
            <div class="flex justify-center gap-2">
                <button type="button" class="pb-bp-btn" data-bp="desktop">Desktop</button>
                <button type="button" class="pb-bp-btn" data-bp="tablet">Tablet</button>
                <button type="button" class="pb-bp-btn" data-bp="mobile">Mobile</button>
            </div>
        </div>
    </aside>
    <iframe id="editorFrame" class="flex-1 border" src="" data-slug="<?= htmlspecialchars($slug) ?>" data-id="<?= $id ?>"></iframe>
</div>
</main>
<script src="../assets/live-editor.js"></script>
<script>
window.liveEditorConfig = {
    slug: <?= json_encode($slug) ?>,
    id: <?= json_encode($id) ?>,
    layout: <?= json_encode($layout) ?>
};
</script>
</body>
</html>
