<?php
if (!isset($pageTitle)) $pageTitle = 'nezbi – Elektronik Onlineshop';
$active = $active ?? '';
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
        <nav class="space-x-8 hidden md:flex">
            <a href="/home.php" class="hover:text-blue-600 <?= $active==='home'? 'font-bold text-blue-600' : '' ?>">Home</a>
            <a href="/index.php" class="hover:text-blue-600 <?= $active==='produkte'? 'font-bold text-blue-600' : '' ?>">Produkte</a>
            <a href="/about.php" class="hover:text-blue-600 <?= $active==='about'? 'font-bold text-blue-600' : '' ?>">Über</a>
        </nav>
        <a href="/warenkorb.php" class="inline-block rounded-xl px-4 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 transition">Warenkorb</a>
    </div>
</header>
<main class="max-w-6xl mx-auto px-4 fade-in">
