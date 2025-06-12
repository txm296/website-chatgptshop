<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/db.php';
$action = $_POST['action'] ?? null;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$page = ['title'=>'','slug'=>'','content'=>'','meta_title'=>'','meta_description'=>'','canonical_url'=>'','jsonld'=>''];
if($id){
    $stmt=$pdo->prepare('SELECT * FROM pages WHERE id=?');
    $stmt->execute([$id]);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row) $page=$row;
}
if($_SERVER['REQUEST_METHOD']==='POST' && !$action){
    $title=$_POST['title']??'';
    $slug=preg_replace('/[^a-z0-9-]/','-', strtolower(trim($_POST['slug']??'')));
    $content=$_POST['content']??'';
    $metaTitle=$_POST['meta_title']??'';
    $metaDesc=$_POST['meta_description']??'';
    $canon=$_POST['canonical_url']??'';
    $jsonld=$_POST['jsonld']??'';
    if($id){
        $stmt=$pdo->prepare('UPDATE pages SET title=?, slug=?, content=?, meta_title=?, meta_description=?, canonical_url=?, jsonld=? WHERE id=?');
        $stmt->execute([$title,$slug,$content,$metaTitle,$metaDesc,$canon,$jsonld,$id]);
    }else{
        $stmt=$pdo->prepare('INSERT INTO pages (title,slug,content,meta_title,meta_description,canonical_url,jsonld) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([$title,$slug,$content,$metaTitle,$metaDesc,$canon,$jsonld]);
        $id=$pdo->lastInsertId();
    }
    header('Location: pages.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Page Builder – nezbi Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif;}#editor .selected{outline:2px dashed #3b82f6;}</style>
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
        <a href="pages.php" class="font-bold text-blue-600">Seiten</a>
        <a href="modular_builder.php" class="hover:text-blue-600">Builder</a>
        <a href="popup_builder.php" class="hover:text-blue-600">Popups</a>
    </nav>
</header>
<main class="pb-builder-container max-w-5xl mx-auto px-4 py-10">
<h1 class="text-2xl font-bold mb-8">Page Builder</h1>
<form method="post" id="pageForm" class="bg-white shadow rounded-xl p-6 space-y-4">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
    <div>
        <label class="block mb-1 font-medium">Titel</label>
        <input type="text" name="title" value="<?= htmlspecialchars($page['title']) ?>" class="w-full border px-3 py-2 rounded" required>
    </div>
    <div>
        <label class="block mb-1 font-medium">Slug (URL)</label>
        <input type="text" name="slug" value="<?= htmlspecialchars($page['slug']) ?>" class="w-full border px-3 py-2 rounded" required>
    </div>
    <div>
        <label class="block mb-1 font-medium">Meta-Titel</label>
        <input type="text" name="meta_title" value="<?= htmlspecialchars($page['meta_title']) ?>" class="w-full border px-3 py-2 rounded">
    </div>
    <div>
        <label class="block mb-1 font-medium">Meta-Beschreibung</label>
        <textarea name="meta_description" class="w-full border px-3 py-2 rounded" rows="2"><?= htmlspecialchars($page['meta_description']) ?></textarea>
    </div>
    <div>
        <label class="block mb-1 font-medium">Canonical URL</label>
        <input type="text" name="canonical_url" value="<?= htmlspecialchars($page['canonical_url']) ?>" class="w-full border px-3 py-2 rounded">
    </div>
    <div>
        <label class="block mb-1 font-medium">JSON-LD</label>
        <textarea name="jsonld" class="w-full border px-3 py-2 rounded" rows="4"><?= htmlspecialchars($page['jsonld']) ?></textarea>
    </div>
    <div class="flex">
        <div id="editor" class="border rounded min-h-[400px] flex-1 p-2 space-y-2"></div>
        <div class="ml-4 space-y-2 w-40 text-sm">
            <button type="button" id="addText" class="w-full px-2 py-1 bg-gray-200 rounded">Text</button>
            <button type="button" id="addImage" class="w-full px-2 py-1 bg-gray-200 rounded">Bild</button>
            <button type="button" id="addSection" class="w-full px-2 py-1 bg-gray-200 rounded">Section</button>
            <div class="border rounded p-2" id="stylePanel" hidden>
                <label class="block text-xs">Hintergrund</label>
                <input type="color" id="bgInput" class="w-full h-6 mb-2">
                <label class="block text-xs">Padding</label>
                <input type="number" id="padInput" class="w-full border mb-2" min="0" step="1">
            </div>
        </div>
    </div>
    <input type="hidden" id="contentInput" name="content" value="<?= htmlspecialchars($page['content']) ?>">
    <div class="mt-6">
        <label class="block mb-1 font-medium">Vorschau</label>
        <iframe id="previewFrame" class="w-full h-96 border rounded"></iframe>
    </div>
    <button class="px-5 py-2 bg-blue-600 text-white rounded-xl">Speichern</button>
</form>
</main>
<script src="builder.js"></script>
</body>
</html>
