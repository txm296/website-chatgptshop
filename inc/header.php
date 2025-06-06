<?php
if (!isset($pageTitle)) $pageTitle = 'nezbi – Elektronik Onlineshop';
$active = $active ?? '';
$kategorien = [];
if (isset($pdo)) {
    try {
        $kategorien = $pdo->query("SELECT id, name FROM kategorien ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $kategorien = [];
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
          }
        }
      }
    </script>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
    <style>
      body { font-family: 'Inter', sans-serif; }
      .fade-in { animation: fadeIn 0.6s ease-in-out; }
      @keyframes fadeIn { from { opacity:0; transform:translateY(20px);} to { opacity:1; transform:none; } }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
<header class="bg-white border-b shadow-sm">
    <div class="max-w-6xl mx-auto flex justify-between items-center py-6 px-4">
        <span class="text-2xl font-extrabold tracking-tight">nezbi</span>
        <div class="flex items-center">
            <button id="menuBtn" class="md:hidden mr-4 text-gray-600" aria-label="Menü öffnen">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <a href="/warenkorb.php" class="inline-block rounded-xl px-4 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 transition">Warenkorb</a>
        </div>
    </div>
    <nav id="navLinks" class="hidden flex-col space-y-2 px-4 pb-4 md:flex md:flex-row md:space-y-0 md:space-x-8 md:max-w-6xl md:mx-auto">
        <a href="/home.php" class="hover:text-blue-600 <?= $active==='home'? 'font-bold text-blue-600' : '' ?>">Home</a>
        <div class="relative">
            <button id="produkteBtn" class="hover:text-blue-600 <?= $active==='produkte'? 'font-bold text-blue-600' : '' ?> focus:outline-none">Produkte</button>
            <div id="katDropdown" class="absolute left-0 mt-2 hidden bg-white border rounded shadow-md z-10">
                <?php foreach ($kategorien as $k): ?>
                    <a href="/kategorie.php?id=<?= $k['id'] ?>" class="block px-4 py-2 whitespace-nowrap hover:bg-gray-100">
                        <?= htmlspecialchars($k['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <a href="/about.php" class="hover:text-blue-600 <?= $active==='about'? 'font-bold text-blue-600' : '' ?>">Über</a>
    </nav>
</header>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var b=document.getElementById('menuBtn');
  var n=document.getElementById('navLinks');
  var pBtn=document.getElementById('produkteBtn');
  var drop=document.getElementById('katDropdown');
  if(b && n){
    b.addEventListener('click',function(){ n.classList.toggle('hidden'); });
  }
  if(pBtn && drop){
    pBtn.addEventListener('click',function(e){
      e.preventDefault();
      drop.classList.toggle('hidden');
    });
    document.addEventListener('click',function(e){
      if(!pBtn.contains(e.target) && !drop.contains(e.target)){
        drop.classList.add('hidden');
      }
    });
  }
});
</script>
<main class="max-w-6xl mx-auto px-4 fade-in">
