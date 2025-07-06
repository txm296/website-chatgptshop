<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/settings.php';
$settings = load_settings();
if($_SERVER['REQUEST_METHOD']==='POST'){
  foreach([
    'primary_color','secondary_color','font_family','h1_size','h2_size','h3_size','h4_size','h5_size','h6_size','body_size','base_spacing','grid_width','border_radius'] as $key){
      if(isset($_POST[$key])) $settings[$key] = trim($_POST[$key]);
  }
  save_settings($settings);
  header('Location: design.php?saved=1');
  exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Designsystem – nezbi Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 text-gray-900">
<?php admin_header('design'); ?>
<main class="max-w-5xl mx-auto px-4 py-10">
<h1 class="text-2xl font-bold mb-8">Globales Designsystem</h1>
<?php if(isset($_GET['saved'])): ?><div class="mb-4 text-green-600">Gespeichert!</div><?php endif; ?>
<form method="post" class="space-y-4 bg-white shadow rounded-xl p-6">
  <div><label class="block mb-1">Prim&auml;rfarbe</label><input type="color" name="primary_color" value="<?=htmlspecialchars($settings['primary_color'])?>" class="border p-1 rounded"></div>
  <div><label class="block mb-1">Sekund&auml;rfarbe</label><input type="color" name="secondary_color" value="<?=htmlspecialchars($settings['secondary_color'])?>" class="border p-1 rounded"></div>
  <div><label class="block mb-1">Schriftfamilie</label><input type="text" name="font_family" value="<?=htmlspecialchars($settings['font_family'])?>" class="border p-1 rounded w-full"></div>
  <div class="grid grid-cols-2 gap-4">
    <div><label class="block mb-1">H1 Gr&ouml;&szlig;e</label><input type="text" name="h1_size" value="<?=htmlspecialchars($settings['h1_size'])?>" class="border p-1 rounded w-full"></div>
    <div><label class="block mb-1">H2 Gr&ouml;&szlig;e</label><input type="text" name="h2_size" value="<?=htmlspecialchars($settings['h2_size'])?>" class="border p-1 rounded w-full"></div>
    <div><label class="block mb-1">H3 Gr&ouml;&szlig;e</label><input type="text" name="h3_size" value="<?=htmlspecialchars($settings['h3_size'])?>" class="border p-1 rounded w-full"></div>
    <div><label class="block mb-1">H4 Gr&ouml;&szlig;e</label><input type="text" name="h4_size" value="<?=htmlspecialchars($settings['h4_size'])?>" class="border p-1 rounded w-full"></div>
    <div><label class="block mb-1">H5 Gr&ouml;&szlig;e</label><input type="text" name="h5_size" value="<?=htmlspecialchars($settings['h5_size'])?>" class="border p-1 rounded w-full"></div>
    <div><label class="block mb-1">H6 Gr&ouml;&szlig;e</label><input type="text" name="h6_size" value="<?=htmlspecialchars($settings['h6_size'])?>" class="border p-1 rounded w-full"></div>
  </div>
  <div><label class="block mb-1">Body Schriftgr&ouml;&szlig;e</label><input type="text" name="body_size" value="<?=htmlspecialchars($settings['body_size'])?>" class="border p-1 rounded w-full"></div>
  <div><label class="block mb-1">Basis-Abstand</label><input type="text" name="base_spacing" value="<?=htmlspecialchars($settings['base_spacing'])?>" class="border p-1 rounded w-full"></div>
  <div><label class="block mb-1">Grid-Breite</label><input type="text" name="grid_width" value="<?=htmlspecialchars($settings['grid_width'])?>" class="border p-1 rounded w-full"></div>
  <div><label class="block mb-1">Border-Radius</label><input type="text" name="border_radius" value="<?=htmlspecialchars($settings['border_radius'])?>" class="border p-1 rounded w-full"></div>
  <button class="px-5 py-2 bg-blue-600 text-white rounded-xl">Speichern</button>
</form>
</main>
</body>
</html>
