<?php
ob_start();
$images = isset($images) && is_array($images)
    ? array_values(array_filter($images, static function ($src) {
        return is_string($src) && trim($src) !== '';
    }))
    : [];
if (count($images) === 0) {
    return;
}
?>
<div class="pb-slider">
  <?php foreach ($images as $idx => $src): ?>
  <div class="pb-slide"<?= $idx > 0 ? ' style="display:none"' : '' ?>><img src="<?= htmlspecialchars($src, ENT_QUOTES) ?>" alt="Slide <?= $idx + 1 ?>"></div>
  <?php endforeach; ?>
  <?php if (count($images) > 1): ?>
  <button class="pb-slide-prev" type="button">&#8249;</button>
  <button class="pb-slide-next" type="button">&#8250;</button>
  <?php endif; ?>
</div>
<style>
.pb-slider{position:relative;overflow:hidden;}
.pb-slide img{display:block;width:100%;}
.pb-slide-prev,.pb-slide-next{position:absolute;top:50%;transform:translateY(-50%);background:rgba(0,0,0,0.5);color:#fff;border:none;padding:0.25rem 0.5rem;cursor:pointer;}
.pb-slide-prev{left:5px;}
.pb-slide-next{right:5px;}
</style>
<script>(function(){
  document.querySelectorAll('.pb-slider').forEach(slider=>{
    const slides=slider.querySelectorAll('.pb-slide');
    if(slides.length===0) return;
    let i=0;
    function show(n){
      slides.forEach((s,idx)=>s.style.display=idx===n?'block':'none');
    }
    if(slides.length>1){
      slider.querySelector('.pb-slide-prev').addEventListener('click',()=>{i=(i-1+slides.length)%slides.length;show(i);});
      slider.querySelector('.pb-slide-next').addEventListener('click',()=>{i=(i+1)%slides.length;show(i);});
      setInterval(()=>{i=(i+1)%slides.length;show(i);},5000);
    }
    show(0);
  });
})();</script>
<?php ob_end_flush(); ?>
