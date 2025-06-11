<div class="pb-slider">
  <div class="pb-slide"><img src="https://via.placeholder.com/600x300?text=Bild+1" alt="Bild 1"></div>
  <div class="pb-slide" style="display:none"><img src="https://via.placeholder.com/600x300?text=Bild+2" alt="Bild 2"></div>
  <div class="pb-slide" style="display:none"><img src="https://via.placeholder.com/600x300?text=Bild+3" alt="Bild 3"></div>
  <button class="pb-slide-prev" type="button">&#8249;</button>
  <button class="pb-slide-next" type="button">&#8250;</button>
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
    let i=0;
    function show(n){
      slides.forEach((s,idx)=>s.style.display=idx===n?'block':'none');
    }
    slider.querySelector('.pb-slide-prev').addEventListener('click',()=>{i=(i-1+slides.length)%slides.length;show(i);});
    slider.querySelector('.pb-slide-next').addEventListener('click',()=>{i=(i+1)%slides.length;show(i);});
    setInterval(()=>{i=(i+1)%slides.length;show(i);},5000);
    show(0);
  });
})();</script>
