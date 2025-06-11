<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/db.php';
require '../pagebuilder/builder.php';

$builder = new ModularPageBuilder();
$widgets = $builder->loadWidgets(__DIR__ . '/../pagebuilder/widgets');
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Modular Builder – nezbi Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../pagebuilder/assets/builder.css">
<style>body{font-family:'Inter',sans-serif;}</style>
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
        <a href="pages.php" class="hover:text-blue-600">Seiten</a>
        <a href="modular_builder.php" class="font-bold text-blue-600">Builder</a>
    </nav>
</header>
<main class="max-w-5xl mx-auto px-4 py-10">
<h1 class="text-2xl font-bold mb-8">Modularer Page Builder</h1>
<div class="flex">
    <div class="pb-canvas flex-1" id="builderCanvas"></div>
    <div class="ml-4 w-40 text-sm space-y-2" id="widgetBar">
        <?php foreach($widgets as $name => $file): ?>
            <button type="button" class="w-full px-2 py-1 bg-gray-200 rounded" data-widget="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></button>
        <?php endforeach; ?>
    </div>
</div>
</main>
<script src="../pagebuilder/assets/builder.js"></script>
</body>
</html>
