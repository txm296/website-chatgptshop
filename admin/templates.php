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
    ],
    [
        'name'=>'Pulse',
        'html'=>'<div class="pulse"></div>',
        'css'=>'.pulse{width:40px;height:40px;border-radius:50%;background:#3498db;animation:pulse 1s infinite}@keyframes pulse{0%{transform:scale(.9);opacity:.7}50%{transform:scale(1);opacity:1}100%{transform:scale(.9);opacity:.7}}'
    ],
    [
        'name'=>'Fade In',
        'html'=>'<div class="fadein">Fade</div>',
        'css'=>'.fadein{opacity:0;animation:fi 1s forwards}@keyframes fi{to{opacity:1}}'
    ],
    [
        'name'=>'Slide In',
        'html'=>'<div class="slidein">Slide</div>',
        'css'=>'.slidein{transform:translateX(-100%);animation:si .5s forwards}@keyframes si{to{transform:none}}'
    ],
    [
        'name'=>'Skeleton Loader',
        'html'=>'<div class="skeleton"></div>',
        'css'=>'.skeleton{width:100%;height:1rem;background:#eee;position:relative;overflow:hidden}.skeleton::after{content:"";position:absolute;inset:0;transform:translateX(-100%);background:linear-gradient(90deg,transparent,rgba(255,255,255,.6),transparent);animation:sk 1.2s infinite}@keyframes sk{to{transform:translateX(100%)}}'
    ],
    [
        'name'=>'Ribbon',
        'html'=>'<span class="ribbon">NEU</span>',
        'css'=>'.ribbon{position:relative;background:#e11d48;color:#fff;padding:0.25rem 0.5rem;font-size:.75rem} .ribbon::before{content:"";position:absolute;left:-0.5rem;top:0;border-width:0.5rem;border-style:solid;border-color:transparent #e11d48 transparent transparent}'
    ],
    [
        'name'=>'Badge',
        'html'=>'<span class="badge">Sale</span>',
        'css'=>'.badge{display:inline-block;background:#2563eb;color:#fff;padding:0.2rem 0.4rem;border-radius:9999px;font-size:.75rem}'
    ],
    [
        'name'=>'Alert Box',
        'html'=>'<div class="alert">Hinweis</div>',
        'css'=>'.alert{background:#fef3c7;border:1px solid #fcd34d;padding:0.5rem;border-radius:0.25rem}'
    ],
    [
        'name'=>'Tooltip',
        'html'=>'<span class="tooltip" data-tip="Info">Hover mich</span>',
        'css'=>'.tooltip{position:relative;cursor:help}.tooltip::after{content:attr(data-tip);position:absolute;left:50%;transform:translateX(-50%);bottom:125%;background:#111;color:#fff;padding:2px 4px;font-size:.75rem;white-space:nowrap;border-radius:2px;opacity:0;transition:.2s}.tooltip:hover::after{opacity:1}'
    ],
    [
        'name'=>'Modal',
        'html'=>'<div class="modal">Inhalt</div>',
        'css'=>'.modal{position:fixed;inset:0;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center} .modal>div{background:#fff;padding:1rem;border-radius:.25rem}'
    ],
    [
        'name'=>'Hover Button',
        'html'=>'<button class="hbtn">Button</button>',
        'css'=>'.hbtn{background:#2563eb;color:#fff;padding:.5rem 1rem;border-radius:.25rem;transition:.2s}.hbtn:hover{background:#1d4ed8}'
    ],
    [
        'name'=>'Progress Bar',
        'html'=>'<div class="pbar"><div style="width:50%"></div></div>',
        'css'=>'.pbar{background:#e5e7eb;border-radius:.25rem;overflow:hidden;width:100%;height:1rem}.pbar div{background:#10b981;height:100%}'
    ],
    [
        'name'=>'Flip Card',
        'html'=>'<div class="flip"><div class="front">Vorn</div><div class="back">Hinten</div></div>',
        'css'=>'.flip{perspective:600px;width:100px;height:100px;position:relative}.flip div{position:absolute;width:100%;height:100%;backface-visibility:hidden;display:flex;align-items:center;justify-content:center;border:1px solid #ccc}.flip .back{transform:rotateY(180deg)}.flip:hover .front{transform:rotateY(180deg)}.flip:hover .back{transform:rotateY(360deg);}'
    ],
    [
        'name'=>'Hero Section',
        'html'=>'<section class="hero"><h2>Titel</h2><p>Text</p></section>',
        'css'=>'.hero{padding:2rem;background:#f3f4f6;text-align:center} .hero h2{font-size:1.5rem;margin-bottom:.5rem}'
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
