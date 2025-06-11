<?php
if (!isset($pageTitle)) $pageTitle = 'nezbi – Elektronik Onlineshop';
$active = $active ?? '';
require_once __DIR__.'/../inc/settings.php';
$siteSettings = load_settings();
$kategorien = [];
$pages = [];
if (isset($pdo)) {
    try {
        $kategorien = $pdo->query("SELECT id, name FROM kategorien ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $kategorien = [];
    }
    try {
        $pages = $pdo->query("SELECT slug, title FROM pages ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $pages = [];
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if(!empty($metaDescription)): ?>
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <?php endif; ?>
    <?php if(!empty($canonicalUrl)): ?>
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">
    <?php endif; ?>
    <?php if(!empty($jsonLd)): ?>
    <script type="application/ld+json">
    <?= $jsonLd ?>
    </script>
    <?php endif; ?>
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
    <link rel="stylesheet" href="/assets/animations.css">
    <style>
      body { font-family: '<?= htmlspecialchars($font) ?>', sans-serif; background-color: var(--body-bg, #f9fafb); color: var(--text-color, #111827); }
      .fade-in { animation: fadeIn 0.6s ease-in-out; }
      @keyframes fadeIn { from { opacity:0; transform:translateY(20px);} to { opacity:1; transform:none; } }
      :root {
        --accent-color: <?= htmlspecialchars($siteSettings['primary_color'] ?? '#2563eb') ?>;
        --secondary-color: <?= htmlspecialchars($siteSettings['secondary_color'] ?? '#1e40af') ?>;
        --body-bg: <?= htmlspecialchars($siteSettings['background_color'] ?? '#f9fafb') ?>;
        --header-bg: #ffffff;
        --text-color: #111827;
        --nav-link-color: #374151;
      }
      .accent-bg { background-color: var(--accent-color); }
      .accent-text { color: var(--accent-color); }
      .accent-hover:hover { color: var(--accent-color); }
      .accent-bg-hover:hover { background-color: var(--accent-color); }
      .nav-link { color: var(--nav-link-color); }
      /* Dark Mode */
      body.dark{
        --body-bg:#1f2937;
        --header-bg:#111827;
        --text-color:#f9fafb;
        --nav-link-color:#d1d5db;
      }
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
      body.template-6{
        --body-bg:#f0f9ff;
        --header-bg:#dbeafe;
        --text-color:#1e3a8a;
        --nav-link-color:#1e3a8a;
      }
      body.template-7{
        --body-bg:#ffffff;
        --header-bg:#e2e8f0;
        --text-color:#1f2937;
        --nav-link-color:#1f2937;
      }
      body.template-8{
        --body-bg:#fef2f2;
        --header-bg:#fee2e2;
        --text-color:#7f1d1d;
        --nav-link-color:#7f1d1d;
      }
      body.template-9{
        --body-bg:#f0fdf4;
        --header-bg:#dcfce7;
        --text-color:#064e3b;
        --nav-link-color:#064e3b;
      }
      body.template-10{
        --body-bg:#faf5ff;
        --header-bg:#ede9fe;
        --text-color:#4c1d95;
        --nav-link-color:#4c1d95;
      }
      body.template-6 nav{order:-1;margin-bottom:0.5rem;text-align:center;}
      body.template-6 header{display:flex;flex-direction:column;align-items:center;}
      body.template-7 header div:first-child{justify-content:center;}
      body.template-7 nav{margin-top:1rem;text-align:center;}
      body.template-7 .logo{margin-bottom:0.5rem;}
      body.template-8 header div:first-child{padding-top:2rem;padding-bottom:2rem;}
      body.template-8 nav{margin-bottom:2rem;}
      body.template-9 nav{text-align:left;}
      body.template-10 header div:first-child{flex-direction:row-reverse;}
      body.template-10 nav a{margin-right:0.5rem;}
      <?= $siteSettings['custom_css'] ?? '' ?>
    </style>
</head>
<body class="template-<?= $template ?>">
<header class="border-b shadow-sm" style="background-color: var(--header-bg);">
    <div class="max-w-6xl mx-auto flex justify-between items-center py-6 px-4">
        <span class="logo text-2xl font-extrabold tracking-tight"><?= htmlspecialchars($siteSettings['logo_text'] ?? 'nezbi') ?></span>
        <div class="flex items-center">
            <button id="menuBtn" class="md:hidden mr-4 text-gray-600" aria-label="Menü öffnen">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <button id="themeToggle" class="mr-4 text-gray-600" aria-label="Theme wechseln">🌙</button>
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
        <?php foreach ($pages as $pg): ?>
            <a href="/<?= urlencode($pg['slug']) ?>" class="nav-link accent-hover <?= $active=== $pg['slug'] ? 'font-bold accent-text' : '' ?>"><?= htmlspecialchars($pg['title']) ?></a>
        <?php endforeach; ?>
    </nav>
</header>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var b=document.getElementById('menuBtn');
  var n=document.getElementById('navLinks');
  var pBtn=document.getElementById('produkteBtn');
  var drop=document.getElementById('katDropdown');
  var themeBtn=document.getElementById('themeToggle');
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
  if(themeBtn){
    if(localStorage.getItem('darkMode')==='1'){
      document.body.classList.add('dark');
    }
    updateThemeIcon();
    themeBtn.addEventListener('click',function(){
      document.body.classList.toggle('dark');
      localStorage.setItem('darkMode',document.body.classList.contains('dark')?'1':'0');
      updateThemeIcon();
    });
  }
  function updateThemeIcon(){
    if(document.body.classList.contains('dark')){
      themeBtn.textContent='☀️';
    }else{
      themeBtn.textContent='🌙';
    }
  }
});
</script>
<main class="max-w-6xl mx-auto px-4 fade-in">
