<div class="pb-accordion">
  <div class="pb-acc-item">
    <button class="pb-acc-header">Eintrag 1</button>
    <div class="pb-acc-content">Inhalt 1</div>
  </div>
  <div class="pb-acc-item">
    <button class="pb-acc-header">Eintrag 2</button>
    <div class="pb-acc-content">Inhalt 2</div>
  </div>
  <div class="pb-acc-item">
    <button class="pb-acc-header">Eintrag 3</button>
    <div class="pb-acc-content">Inhalt 3</div>
  </div>
</div>
<style>
.pb-accordion .pb-acc-header{display:block;width:100%;text-align:left;background:#eee;padding:0.5rem;border:none;cursor:pointer;}
.pb-accordion .pb-acc-content{display:none;padding:0.5rem;border:1px solid #ddd;border-top:0;}
.pb-accordion .pb-acc-item+.pb-acc-item{margin-top:0.25rem;}
.pb-accordion .open .pb-acc-content{display:block;}
</style>
<script>(function(){
  document.querySelectorAll('.pb-accordion').forEach(acc=>{
    acc.addEventListener('click',e=>{
      if(e.target.classList.contains('pb-acc-header')){
        const item=e.target.closest('.pb-acc-item');
        item.classList.toggle('open');
      }
    });
  });
})();</script>
