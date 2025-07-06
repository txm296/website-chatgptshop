<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/db.php';
if(isset($_GET['delete'])){
    $id=intval($_GET['delete']);
    $pdo->prepare('DELETE FROM builder_templates WHERE id=?')->execute([$id]);
    header('Location: layout_library.php');
    exit;
}
$templates=$pdo->query('SELECT id,name,html FROM builder_templates ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Vorlagenbibliothek – nezbi Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 text-gray-900">
<?php admin_header('builder'); ?>
<main class="max-w-5xl mx-auto px-4 py-10 space-y-6">
<h1 class="text-2xl font-bold mb-8">Vorlagenbibliothek</h1>
<?php foreach($templates as $tpl): ?>
    <div class="border rounded-xl p-4 bg-white shadow flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold mb-2"><?=htmlspecialchars($tpl['name'])?></h3>
            <div class="border p-2 mb-2"><?= $tpl['html'] ?></div>
        </div>
        <a href="?delete=<?= $tpl['id'] ?>" class="text-red-600" onclick="return confirm('Vorlage löschen?');">Löschen</a>
    </div>
<?php endforeach; ?>
</main>
</body>
</html>
