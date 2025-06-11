<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/db.php';
$action = $_POST['action'] ?? null;
if($action==='delete' && isset($_POST['id'])){
    $stmt=$pdo->prepare('DELETE FROM pages WHERE id=?');
    $stmt->execute([intval($_POST['id'])]);
    header('Location: pages.php');
    exit;
}
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$page = ['title'=>'','slug'=>'','content'=>''];
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
    if($id){
        $stmt=$pdo->prepare('UPDATE pages SET title=?, slug=?, content=? WHERE id=?');
        $stmt->execute([$title,$slug,$content,$id]);
    }else{
        $stmt=$pdo->prepare('INSERT INTO pages (title,slug,content) VALUES (?,?,?)');
        $stmt->execute([$title,$slug,$content]);
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
<header class="bg-white border-b shadow-sm">
    <div class="max-w-5xl mx-auto flex justify-between items-center py-6 px-4">
        <span class="text-2xl font-extrabold tracking-tight">nezbi Admin</span>
        <div class="flex items-center">
            <button id="menuBtn" class="md:hidden mr-4 text-gray-600" aria-label="Menü öffnen">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <a href="logout.php" class="inline-block rounded-xl px-4 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 transition">Logout</a>
        </div>
    </div>
    <nav id="navLinks" class="hidden flex-col space-y-2 px-4 pb-4 md:flex md:flex-row md:space-y-0 md:space-x-8 md:max-w-5xl md:mx-auto">
        <a href="dashboard.php" class="hover:text-blue-600">Dashboard</a>
        <a href="produkte.php" class="hover:text-blue-600">Produkte</a>
        <a href="kategorien.php" class="hover:text-blue-600">Kategorien</a>
        <a href="rabattcodes.php" class="hover:text-blue-600">Rabatte</a>
        <a href="bestellungen.php" class="hover:text-blue-600">Bestellungen</a>
        <a href="insights.php" class="hover:text-blue-600">Insights</a>
        <a href="pages.php" class="text-blue-600 font-bold">Seiten</a>
        <a href="modular_builder.php" class="hover:text-blue-600">Builder</a>
        <a href="customize.php" class="hover:text-blue-600">Website bearbeiten</a>
        <a href="templates.php" class="hover:text-blue-600">Templates</a>
    </nav>
</header>
<script>
document.addEventListener('DOMContentLoaded',function(){var b=document.getElementById('menuBtn');var n=document.getElementById('navLinks');if(b&&n){b.addEventListener('click',function(){n.classList.toggle('hidden');});}});
</script>
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
        <label class="block mb-1 font-medium">Inhalt</label>
        <div class="flex">
            <div id="editor" class="border rounded min-h-[400px] flex-1 p-2"></div>
            <div class="ml-4 space-y-2 w-32">
                <button type="button" id="addText" class="w-full px-2 py-1 bg-gray-200 rounded">Text</button>
                <button type="button" id="addImage" class="w-full px-2 py-1 bg-gray-200 rounded">Bild</button>
            </div>
        </div>
        <input type="hidden" id="contentInput" name="content" value="<?= htmlspecialchars($page['content']) ?>">
    </div>
    <div class="mt-6">
        <label class="block mb-1 font-medium">Vorschau</label>
        <iframe id="previewFrame" class="w-full h-96 border rounded"></iframe>
    </div>
    <button class="px-5 py-2 bg-blue-600 text-white rounded-xl">Speichern</button>
</form>
</main>
</body>
</html>
