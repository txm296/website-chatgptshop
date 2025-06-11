(function(){
  function initPopup(p){
    const overlay=document.createElement('div');
    overlay.className='pb-popup-overlay hidden';
    overlay.innerHTML='<div class="pb-popup-content">'+p.html+'<button class="pb-popup-close" type="button">\u00d7</button></div>';
    document.body.appendChild(overlay);
    const closeBtn=overlay.querySelector('.pb-popup-close');
    closeBtn.addEventListener('click',()=>overlay.classList.add('hidden'));
    function open(){ overlay.classList.remove('hidden'); if(window.initWidgetAnimations) window.initWidgetAnimations(); }
    const t=p.triggers||{};
    if(t.delay){ setTimeout(open, parseInt(t.delay,10)*1000); }
    if(t.exit){ const handler=e=>{ if(e.clientY<=0){ open(); document.removeEventListener('mouseout',handler); } }; document.addEventListener('mouseout',handler); }
    if(t.scroll){ const sc=parseFloat(t.scroll); const sfunc=()=>{ const perc=(window.scrollY+window.innerHeight)/document.documentElement.scrollHeight*100; if(perc>=sc){ open(); window.removeEventListener('scroll',sfunc); } }; window.addEventListener('scroll',sfunc); }
    if(t.button){ document.querySelectorAll(t.button).forEach(btn=>btn.addEventListener('click',open)); }
  }
  async function loadPopups(slug){
    if(!slug) return; const res=await fetch('/popupbuilder/load_popups.php?slug='+encodeURIComponent(slug));
    if(!res.ok) return; const data=await res.json(); data.forEach(initPopup);
  }
  document.addEventListener('DOMContentLoaded',()=>{ if(window.currentSlug!==undefined){ loadPopups(window.currentSlug); } });
})();
