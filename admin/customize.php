<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
require '../inc/db.php';
require '../inc/settings.php';
$siteSettings = load_settings();
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siteSettings['primary_color'] = $_POST['primary_color'] ?? '#2563eb';
    $siteSettings['secondary_color'] = $_POST['secondary_color'] ?? '#1e40af';
    $siteSettings['logo_text'] = $_POST['logo_text'] ?? 'nezbi';
    $siteSettings['template'] = intval($_POST['template'] ?? 1);
    $siteSettings['font_family'] = $_POST['font_family'] ?? 'Inter';
    $siteSettings['hero_title'] = $_POST['hero_title'] ?? '';
    $siteSettings['hero_subtitle'] = $_POST['hero_subtitle'] ?? '';
    $siteSettings['hero_image'] = $_POST['hero_image'] ?? '';
    $siteSettings['footer_text'] = $_POST['footer_text'] ?? '';
    save_settings($siteSettings);
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Website bearbeiten – nezbi Admin</title>
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
    <?php $ff = $siteSettings['font_family'] ?? 'Inter'; $ff_link = str_replace(' ', '+', $ff); ?>
    <link href="https://fonts.googleapis.com/css?family=<?= $ff_link ?>:400,600&display=swap" rel="stylesheet">
    <style>
      body { font-family: '<?= htmlspecialchars($ff) ?>', sans-serif; }
      :root { --accent-color: <?= htmlspecialchars($siteSettings['primary_color']) ?>; }
      .accent-bg { background-color: var(--accent-color); }
      .accent-hover:hover { color: var(--accent-color); }
      .accent-bg-hover:hover { background-color: var(--accent-color); }
    </style>
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
                <a href="logout.php" class="inline-block rounded-xl px-4 py-2 accent-bg text-white font-medium hover:opacity-90 transition">Logout</a>
            </div>
        </div>
        <nav id="navLinks" class="hidden flex-col space-y-2 px-4 pb-4 md:flex md:flex-row md:space-y-0 md:space-x-8 md:max-w-5xl md:mx-auto">
            <a href="dashboard.php" class="accent-hover">Dashboard</a>
            <a href="produkte.php" class="accent-hover">Produkte</a>
            <a href="kategorien.php" class="accent-hover">Kategorien</a>
            <a href="rabattcodes.php" class="accent-hover">Rabatte</a>
            <a href="bestellungen.php" class="accent-hover">Bestellungen</a>
            <a href="insights.php" class="accent-hover">Insights</a>
            <a href="customize.php" class="font-bold accent-text">Website bearbeiten</a>
        </nav>
    </header>
    <script>
    document.addEventListener('DOMContentLoaded',function(){
        var b=document.getElementById('menuBtn');
        var n=document.getElementById('navLinks');
        if(b&&n){
            b.addEventListener('click',function(){
                n.classList.toggle('hidden');
            });
        }
    });
    </script>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8">Website bearbeiten</h1>
        <?php if($success): ?><p class="mb-4 text-green-600">Einstellungen gespeichert.</p><?php endif; ?>
        <form method="post" class="bg-white shadow rounded-xl p-6 space-y-4">
            <div>
                <label class="block mb-1 font-medium">Primärfarbe</label>
                <input type="color" name="primary_color" value="<?= htmlspecialchars($siteSettings['primary_color']) ?>" class="w-24 h-10 p-0 border">
            </div>
            <div>
                <label class="block mb-1 font-medium">Sekundärfarbe</label>
                <input type="color" name="secondary_color" value="<?= htmlspecialchars($siteSettings['secondary_color']) ?>" class="w-24 h-10 p-0 border">
            </div>
            <div>
                <label class="block mb-1 font-medium">Logo Text</label>
                <input type="text" name="logo_text" value="<?= htmlspecialchars($siteSettings['logo_text']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Template</label>
                <select name="template" class="border px-3 py-2 rounded">
                    <?php for($i=1;$i<=5;$i++): ?>
                        <option value="<?=$i?>" <?=$siteSettings['template']==$i?'selected':''?>>Template <?=$i?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">Schriftart</label>
                <select name="font_family" class="border px-3 py-2 rounded">
                    <?php $fonts=['Inter','Roboto','Open Sans','Lora','Poppins'];
                    foreach($fonts as $f): ?>
                    <option value="<?=$f?>" <?=$siteSettings['font_family']==$f?'selected':''?>><?=$f?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">Hero Titel</label>
                <input type="text" name="hero_title" value="<?= htmlspecialchars($siteSettings['hero_title']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Hero Untertitel</label>
                <input type="text" name="hero_subtitle" value="<?= htmlspecialchars($siteSettings['hero_subtitle']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Hero Bild-URL</label>
                <input type="text" name="hero_image" value="<?= htmlspecialchars($siteSettings['hero_image']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Footer Text</label>
                <input type="text" name="footer_text" value="<?= htmlspecialchars($siteSettings['footer_text']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
            <button class="px-5 py-2 accent-bg text-white rounded-xl">Speichern</button>
        </form>
    </main>
</body>
</html>
