<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/db.php';
require '../inc/admin_header.php';
$action = $_POST['action'] ?? null;
if($action==='delete' && isset($_POST['id'])){
    $stmt=$pdo->prepare('DELETE FROM pages WHERE id=?');
    $stmt->execute([intval($_POST['id'])]);
    header('Location: pages.php');
    exit;
}
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
<title>Seite bearbeiten – nezbi Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<script src="custom-editor.js"></script>
<link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 text-gray-900">
<?php admin_header('seiten'); ?>
<main class="max-w-5xl mx-auto px-4 py-10">
<h1 class="text-2xl font-bold mb-8">Seite bearbeiten</h1>
<form method="post" id="pageForm" class="bg-white shadow rounded-xl p-6 space-y-4">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
    <div>
        <label class="block mb-1 font-medium">Titel</label>
        <input type="text" name="title" value="<?= htmlspecialchars($page['title']) ?>" class="w-full border px-3 py-2 rounded" required>
    </div>
    <div>
        <label class="block mb-1 font-medium">Slug (URL)</label>
        <input type="text" name="slug" value="<?= htmlspecialchars($page['slug']) ?>" class="w-full border px-3 py-2 rounded" placeholder="z.B. testseite" required>
        <p class="text-sm text-gray-500 mt-1">Beispiel: <code>testseite</code> ergibt die URL <code>nezbi.de/testseite</code></p>
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
    <div class="flex space-x-2">
        <button type="button" id="editModeBtn" class="px-3 py-1 bg-gray-200 rounded">Entwurf</button>
        <button type="button" id="previewModeBtn" class="px-3 py-1 bg-gray-200 rounded">Vorschau</button>
    </div>
    <div>
        <label class="block mb-1 font-medium">Inhalt</label>
        <div class="flex" id="editorSection">
            <div id="editor" class="border rounded min-h-[400px] flex-1 p-2"></div>
            <div id="editTools" class="ml-4 space-y-2 w-32">
                <button type="button" id="addText" class="w-full px-2 py-1 bg-gray-200 rounded">Text</button>
                <button type="button" id="addImage" class="w-full px-2 py-1 bg-gray-200 rounded">Bild</button>
            </div>
        </div>
        <input type="hidden" id="contentInput" name="content" value="<?= htmlspecialchars($page['content']) ?>">
    </div>
    <div class="mt-6" id="previewSection">
        <label class="block mb-1 font-medium">Vorschau</label>
        <iframe id="previewFrame" class="w-full h-96 border rounded"></iframe>
    </div>
    <button id="publishBtn" class="px-5 py-2 bg-blue-600 text-white rounded-xl">Veröffentlichen</button>
</form>
</main>
</body>
</html>
