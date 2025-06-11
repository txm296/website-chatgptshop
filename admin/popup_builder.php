<?php
session_start();
if(!isset($_SESSION['admin'])){header('Location: ../login.php');exit;}
require '../inc/db.php';
require '../pagebuilder/builder.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$popup = ['title'=>'','slug'=>'','layout'=>'','triggers'=>['delay'=>'','exit'=>0,'scroll'=>'','button'=>''],'pages'=>[]];
if($id){
    $stmt=$pdo->prepare('SELECT * FROM builder_popups WHERE id=?');
    $stmt->execute([$id]);
    if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        $popup['title']=$row['title'];
        $popup['slug']=$row['slug'];
        $d=json_decode($row['layout'],true);$popup['layout']=$d['html']??'';
        $popup['triggers']=$row['triggers']?json_decode($row['triggers'],true):[];
        $popup['pages']=array_filter(explode(',',trim($row['pages'],',')));
    }
}

$builder=new ModularPageBuilder();
$widgets=$builder->loadWidgets(__DIR__.'/../pagebuilder/widgets');
$popupList=[];
foreach($pdo->query('SELECT id,title FROM builder_popups ORDER BY id') as $row){
    $popupList[$row['id']]=$row['title'];
}
$pageOptions=['home'=>'Startseite'];
foreach($pdo->query('SELECT id,name FROM kategorien ORDER BY name') as $row){
    $pageOptions['category-'.$row['id']]='Kategorie: '.$row['name'];
}
foreach($pdo->query('SELECT slug,title FROM pages ORDER BY title') as $row){
    $pageOptions[$row['slug']]=$row['title'];
}
foreach($pdo->query('SELECT slug,title FROM builder_pages ORDER BY title') as $row){
    if(!isset($pageOptions[$row['slug']]))$pageOptions[$row['slug']]=$row['title'];
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Popup Builder – nezbi Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<link rel="stylesheet" href="../pagebuilder/assets/builder.css">
<link rel="stylesheet" href="../assets/animations.css">
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
        <a href="modular_builder.php" class="hover:text-blue-600">Builder</a>
        <a href="popup_builder.php" class="font-bold text-blue-600">Popups</a>
    </nav>
</header>
<main class="max-w-5xl mx-auto px-4 py-10">
<h1 class="text-2xl font-bold mb-8">Popup Builder</h1>
<div class="mb-4 space-y-2">
    <div class="flex space-x-2">
        <input type="text" id="pbPopupSearch" placeholder="Popup suchen..." class="flex-1 border px-2 py-1 rounded">
        <select id="pbPopupSelect" class="border px-2 py-1 rounded w-60">
            <?php foreach($popupList as $pid=>$title): ?>
                <option value="<?= $pid ?>" <?= $pid==$id ? 'selected' : '' ?>><?= htmlspecialchars($title) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <input type="text" id="pbTitle" value="<?= htmlspecialchars($popup['title']) ?>" placeholder="Titel" class="w-full border px-2 py-1 rounded">
    <input type="text" id="pbSlug" value="<?= htmlspecialchars($popup['slug']) ?>" placeholder="Slug" class="w-full border px-2 py-1 rounded">
    <label class="block">Verzögerung (Sek.) <input type="number" id="pbDelay" value="<?= htmlspecialchars($popup['triggers']['delay'] ?? '') ?>" class="border px-2 py-1 rounded w-full"></label>
    <label class="block"><input type="checkbox" id="pbExit" <?= !empty($popup['triggers']['exit']) ? 'checked' : '' ?>> Exit-Intent</label>
    <label class="block">Scroll % <input type="number" id="pbScroll" value="<?= htmlspecialchars($popup['triggers']['scroll'] ?? '') ?>" class="border px-2 py-1 rounded w-full"></label>
    <input type="text" id="pbButton" value="<?= htmlspecialchars($popup['triggers']['button'] ?? '') ?>" placeholder="Button-Selector" class="w-full border px-2 py-1 rounded">
    <label class="block">Seiten
        <select id="pbPages" multiple size="5" class="border px-2 py-1 rounded w-full">
            <?php foreach($pageOptions as $slug=>$title): ?>
                <option value="<?= htmlspecialchars($slug) ?>" <?= in_array($slug,$popup['pages'])?'selected':'' ?>><?= htmlspecialchars($title) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="button" id="pbSave" class="px-4 py-2 bg-blue-600 text-white rounded">Speichern</button>
    <div class="space-x-2 mt-2">
        <button type="button" class="pb-bp-btn px-2 py-1 bg-gray-200 rounded" data-bp="desktop">Desktop</button>
        <button type="button" class="pb-bp-btn px-2 py-1 bg-gray-200 rounded" data-bp="tablet">Tablet</button>
        <button type="button" class="pb-bp-btn px-2 py-1 bg-gray-200 rounded" data-bp="mobile">Mobile</button>
    </div>
</div>
<div class="flex">
    <div class="w-60 mr-4 space-y-4" id="leftPanel">
        <div id="pbConfigPanel" class="pb-config"></div>
        <div class="text-sm space-y-2" id="widgetBar">
            <?php foreach($widgets as $name=>$file): ?>
                <button type="button" class="w-full px-2 py-1 bg-gray-200 rounded" data-widget="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="pb-canvas flex-1 border" id="builderCanvas" data-save-url="../popupbuilder/save_popup.php" data-load-url="<?= $id ? '../popupbuilder/load_popup.php?id='.$id : '' ?>" data-page-id="<?= $id ?>">
        <?= $id ? '' : $popup['layout']; ?>
    </div>
</div>
</main>
<script src="../pagebuilder/assets/builder.js"></script>
<script src="../assets/dynamic-widgets.js"></script>
<script>
document.addEventListener('DOMContentLoaded',()=>{
  const sel=document.getElementById('pbPopupSelect');
  if(sel){
    sel.addEventListener('change',()=>{ const id=sel.value; window.location='popup_builder.php?id='+id; });
  }
  const saveBtn=document.getElementById('pbSave');
  if(saveBtn){
    saveBtn.addEventListener('click',async()=>{
      const canvas=document.getElementById('builderCanvas');
      const payload={
        id:parseInt(canvas.dataset.pageId||0,10),
        title:document.getElementById('pbTitle').value,
        slug:document.getElementById('pbSlug').value,
        layout:canvas.innerHTML,
        triggers:{delay:document.getElementById('pbDelay').value,exit:document.getElementById('pbExit').checked?1:0,scroll:document.getElementById('pbScroll').value,button:document.getElementById('pbButton').value},
        pages:Array.from(document.getElementById('pbPages').selectedOptions).map(o=>o.value)
      };
      const res=await fetch('../popupbuilder/save_popup.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
      if(res.ok){ const data=await res.json(); canvas.dataset.pageId=data.id; alert('Gespeichert'); } else { alert('Fehler beim Speichern'); }
    });
  }
});
</script>
</body>
</html>
