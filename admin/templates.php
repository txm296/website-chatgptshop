<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
$templates=[
    [
        'name'=>'Spinner',
        'html'=>'<div class="loader"></div>',
        'css'=>'.loader{border:4px solid #f3f3f3;border-top:4px solid #3498db;border-radius:50%;width:40px;height:40px;animation:spin 1s linear infinite;}@keyframes spin{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}'
    ],
    [
        'name'=>'Bouncing Dots',
        'html'=>'<div class="dots"><div></div><div></div><div></div></div>',
        'css'=>'.dots{display:flex;gap:4px}.dots div{width:8px;height:8px;background:#3498db;border-radius:50%;animation:bounce .6s infinite alternate}.dots div:nth-child(2){animation-delay:.2s}.dots div:nth-child(3){animation-delay:.4s}@keyframes bounce{to{opacity:.3;transform:translateY(-6px);}}'
    ]
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Templates – nezbi Admin</title>
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
            <button id="menuBtn" class="md:hidden mr-4 text-gray-600" aria-label="Menü öffnen">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
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
        <a href="pages.php" class="hover:text-blue-600">Seiten</a>
        <a href="modular_builder.php" class="hover:text-blue-600">Builder</a>
        <a href="popup_builder.php" class="hover:text-blue-600">Popups</a>
        <a href="customize.php" class="hover:text-blue-600">Website bearbeiten</a>
        <a href="templates.php" class="font-bold text-blue-600">Templates</a>
    </nav>
</header>
<script>document.addEventListener('DOMContentLoaded',function(){var b=document.getElementById('menuBtn');var n=document.getElementById('navLinks');if(b&&n){b.addEventListener('click',function(){n.classList.toggle('hidden');});}});</script>
<main class="max-w-5xl mx-auto px-4 py-10 space-y-6">
    <h1 class="text-2xl font-bold mb-8">CSS Templates</h1>
    <p class="mb-4">Ziehe ein Template auf eine Seite im Editor, um es einzufügen.</p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php foreach($templates as $tpl): ?>
        <div class="border rounded-xl p-4 bg-white shadow" draggable="true" data-template='<?=json_encode($tpl,JSON_HEX_APOS|JSON_HEX_QUOT)?>'>
            <h3 class="font-semibold mb-2"><?=htmlspecialchars($tpl['name'])?></h3>
            <div class="mb-2">
                <style><?= $tpl['css'] ?></style>
                <?= $tpl['html'] ?>
            </div>
            <pre class="bg-gray-100 p-2 text-sm overflow-x-auto whitespace-pre-wrap"><?=htmlspecialchars($tpl['css'])?></pre>
        </div>
    <?php endforeach; ?>
    </div>
</main>
<script>
document.querySelectorAll('[draggable="true"]').forEach(el=>{
    el.addEventListener('dragstart',e=>{
        e.dataTransfer.setData('text/plain', el.getAttribute('data-template'));
    });
});
</script>
</body>
</html>
