(function(){
  async function loadProductGrid(el){
    const params=new URLSearchParams();
    if(el.dataset.category) params.append('category', el.dataset.category);
    if(el.dataset.limit) params.append('limit', el.dataset.limit);
    const res=await fetch('/pagebuilder/api/product_grid.php?'+params.toString());
    if(res.ok){
      el.innerHTML=await res.text();
    }else{
      el.textContent='Fehler beim Laden';
    }
  }
  async function loadCategoryList(el){
    const params=new URLSearchParams();
    if(el.dataset.limit) params.append('limit', el.dataset.limit);
    const res=await fetch('/pagebuilder/api/category_list.php?'+params.toString());
    if(res.ok){
      el.innerHTML=await res.text();
    }else{
      el.textContent='Fehler beim Laden';
    }
  }
  function initAnimations(){
    if(window._widgetAnimObserver){window._widgetAnimObserver.disconnect();}
    const observer=new IntersectionObserver(entries=>{
      entries.forEach(en=>{
        if(en.isIntersecting){
          en.target.classList.add('anim-start');
          observer.unobserve(en.target);
        }
      });
    },{threshold:0.1});

    document.querySelectorAll('.pb-item').forEach(el=>{
      el.classList.forEach(c=>{if(c.startsWith('anim-')||c==='anim-start')el.classList.remove(c);});
      const anim=el.dataset.animation; if(!anim) return;
      el.classList.add('anim-'+anim);
      const trig=el.dataset.animTrigger||'scroll';
      if(trig==='scroll') observer.observe(el);
      else if(trig==='hover') el.addEventListener('mouseenter',()=>el.classList.add('anim-start'),{once:true});
      else if(trig==='click') el.addEventListener('click',()=>el.classList.add('anim-start'),{once:true});
    });
    window._widgetAnimObserver=observer;
  }

  document.addEventListener('DOMContentLoaded',function(){
    document.querySelectorAll('.pb-product-grid').forEach(loadProductGrid);
    document.querySelectorAll('.pb-category-list').forEach(loadCategoryList);
    initAnimations();
  });

  window.initWidgetAnimations=initAnimations;
})();
