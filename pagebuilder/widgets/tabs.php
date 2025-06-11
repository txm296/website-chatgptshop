<div class="pb-tabs">
  <div class="pb-tab-headers">
    <button data-tab="0" class="active">Tab 1</button>
    <button data-tab="1">Tab 2</button>
    <button data-tab="2">Tab 3</button>
  </div>
  <div class="pb-tab-bodies">
    <div class="pb-tab-body">Inhalt 1</div>
    <div class="pb-tab-body" style="display:none">Inhalt 2</div>
    <div class="pb-tab-body" style="display:none">Inhalt 3</div>
  </div>
</div>
<style>
.pb-tab-headers{display:flex;gap:0.25rem;margin-bottom:0.5rem;}
.pb-tab-headers button{padding:0.25rem 0.5rem;border:1px solid #ccc;background:#eee;cursor:pointer;}
.pb-tab-headers button.active{background:#ddd;font-weight:bold;}
.pb-tab-body{padding:0.5rem;border:1px solid #ccc;}
</style>
<script>(function(){
  document.querySelectorAll('.pb-tabs').forEach(tabs=>{
    const headers=tabs.querySelectorAll('.pb-tab-headers button');
    const bodies=tabs.querySelectorAll('.pb-tab-body');
    headers.forEach(btn=>{
      btn.addEventListener('click',()=>{
        headers.forEach(b=>b.classList.remove('active'));
        bodies.forEach(b=>b.style.display='none');
        const i=parseInt(btn.dataset.tab,10);
        btn.classList.add('active');
        if(bodies[i]) bodies[i].style.display='block';
      });
    });
  });
})();</script>
