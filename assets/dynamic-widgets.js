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
  function initVideo(el){
    const type=el.dataset.type||'youtube';
    const src=el.dataset.src||'';
    const preview=el.dataset.preview||'';
    function create(){
      const wrap=document.createElement('div');
      wrap.className='pb-video-wrapper';
      if(type==='youtube'){
        const id=src.split('v=')[1]||src.split('/').pop();
        const ifr=document.createElement('iframe');
        ifr.src='https://www.youtube.com/embed/'+id;
        ifr.setAttribute('frameborder','0');
        ifr.allowFullscreen=true;
        wrap.appendChild(ifr);
      }else if(type==='vimeo'){
        const id=src.split('/').pop();
        const ifr=document.createElement('iframe');
        ifr.src='https://player.vimeo.com/video/'+id;
        ifr.setAttribute('frameborder','0');
        ifr.allowFullscreen=true;
        wrap.appendChild(ifr);
      }else{
        const vid=document.createElement('video');
        vid.src=src;
        vid.controls=true;
        wrap.appendChild(vid);
      }
      el.innerHTML='';
      el.appendChild(wrap);
      el.classList.remove('pb-video-clickable');
    }
    if(preview){
      const img=document.createElement('img');
      img.src=preview;
      img.className='pb-video-preview';
      const btn=document.createElement('div');
      btn.className='pb-video-play';
      btn.textContent='▶';
      el.innerHTML='';
      el.appendChild(img);
      el.appendChild(btn);
      el.classList.add('pb-video-clickable');
      el.addEventListener('click',()=>create(),{once:true});
    }else{
      create();
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
    document.querySelectorAll('.pb-video').forEach(initVideo);
    initAnimations();
  });

  window.initWidgetAnimations=initAnimations;
  window.initVideos=function(){
    document.querySelectorAll('.pb-video').forEach(initVideo);
  };
})();
