<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/db.php';
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
        <a href="pages.php" class="font-bold text-blue-600">Seiten</a>
        <a href="customize.php" class="hover:text-blue-600">Website bearbeiten</a>
        <a href="templates.php" class="hover:text-blue-600">Templates</a>
    </nav>
</header>
<script>
document.addEventListener('DOMContentLoaded',function(){
    var b=document.getElementById('menuBtn');
    var n=document.getElementById('navLinks');
    if(b&&n){b.addEventListener('click',function(){n.classList.toggle('hidden');});}
});
</script>
<main class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-8">Seiten</h1>
    <a href="edit_page.php" class="mb-4 inline-block px-4 py-2 bg-blue-600 text-white rounded">Neue Seite</a>
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
                    <a href="edit_page.php?id=<?= $p['id'] ?>" class="px-3 py-1 bg-blue-600 text-white rounded mr-2">Bearbeiten</a>
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
