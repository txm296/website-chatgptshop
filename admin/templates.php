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
<?php admin_header('templates'); ?>
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
