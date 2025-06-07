<?php
if (!isset($pageTitle)) $pageTitle = 'nezbi – Elektronik Onlineshop';
$active = $active ?? '';
require_once __DIR__.'/settings.php';
$siteSettings = load_settings();
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
    <?php
      $template = intval($siteSettings['template'] ?? 1);
      $font = $siteSettings['font_family'] ?? 'Inter';
      $fontLink = str_replace(' ', '+', $font);
    ?>
    <link href="https://fonts.googleapis.com/css?family=<?= $fontLink ?>:400,600&display=swap" rel="stylesheet">
    <style>
      body { font-family: '<?= htmlspecialchars($font) ?>', sans-serif; background-color: var(--body-bg, #f9fafb); color: var(--text-color, #111827); }
      .fade-in { animation: fadeIn 0.6s ease-in-out; }
      @keyframes fadeIn { from { opacity:0; transform:translateY(20px);} to { opacity:1; transform:none; } }
      :root {
        --accent-color: <?= htmlspecialchars($siteSettings['primary_color'] ?? '#2563eb') ?>;
        --secondary-color: <?= htmlspecialchars($siteSettings['secondary_color'] ?? '#1e40af') ?>;
        --body-bg: #f9fafb;
        --header-bg: #ffffff;
        --text-color: #111827;
        --nav-link-color: #374151;
      }
      .accent-bg { background-color: var(--accent-color); }
      .accent-text { color: var(--accent-color); }
      .accent-hover:hover { color: var(--accent-color); }
      .accent-bg-hover:hover { background-color: var(--accent-color); }
      .nav-link { color: var(--nav-link-color); }
      /* Template Styles */
      body.template-2{
        --body-bg:#0f172a;
        --header-bg:#1e293b;
        --text-color:#f8fafc;
        --nav-link-color:#d1d5db;
      }
      body.template-3{
        --body-bg:#fdf2f8;
        --header-bg:#fce7f3;
        --text-color:#6b21a8;
        --nav-link-color:#6b21a8;
      }
      body.template-4{
        --body-bg:#ecfeff;
        --header-bg:#cffafe;
        --text-color:#134e4a;
        --nav-link-color:#134e4a;
      }
      body.template-5{
        --body-bg:#fffbeb;
        --header-bg:#fef3c7;
        --text-color:#374151;
        --nav-link-color:#78350f;
      }
    </style>
</head>
<body class="template-<?= $template ?>">
<header class="border-b shadow-sm" style="background-color: var(--header-bg);">
    <div class="max-w-6xl mx-auto flex justify-between items-center py-6 px-4">
        <span class="text-2xl font-extrabold tracking-tight"><?= htmlspecialchars($siteSettings['logo_text'] ?? 'nezbi') ?></span>
        <div class="flex items-center">
            <button id="menuBtn" class="md:hidden mr-4 text-gray-600" aria-label="Menü öffnen">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <a href="/warenkorb.php" class="inline-block rounded-xl px-4 py-2 accent-bg text-white font-medium hover:opacity-90 transition">Warenkorb</a>
        </div>
    </div>
    <nav id="navLinks" class="hidden flex-col space-y-2 px-4 pb-4 md:flex md:flex-row md:space-y-0 md:space-x-8 md:max-w-6xl md:mx-auto">
        <a href="/index.php" class="nav-link accent-hover <?= $active==='home'? 'font-bold accent-text' : '' ?>">Home</a>
        <div class="relative">
            <a href="/produkte.php" id="produkteBtn" class="nav-link accent-hover <?= $active==='produkte'? 'font-bold accent-text' : '' ?> focus:outline-none">Produkte</a>
            <div id="katDropdown" class="absolute left-0 mt-2 hidden bg-white border rounded shadow-md z-10">
                <?php foreach ($kategorien as $k): ?>
                    <a href="/kategorie.php?id=<?= $k['id'] ?>" class="block px-4 py-2 whitespace-nowrap hover:bg-gray-100">
                        <?= htmlspecialchars($k['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <a href="/about.php" class="nav-link accent-hover <?= $active==='about'? 'font-bold accent-text' : '' ?>">Über</a>
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
      if(drop.classList.contains('hidden')){
        e.preventDefault();
        drop.classList.remove('hidden');
      }
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
