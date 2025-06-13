(function(){
  const cfg = window.liveEditorConfig || {};
  const frame = document.getElementById('editorFrame');
  const pageSelect = document.getElementById('pageSelect');
  const saveBtn = document.getElementById('savePage');
  const pageTitle = document.getElementById('pageTitle');
  const widgetBar = document.getElementById('widgetBar');
  const configPanel = document.getElementById('configPanel');
  const bpButtons = document.querySelectorAll('.pb-bp-btn');

  let currentSlug = cfg.slug || '';
  let currentId = cfg.id || 0;
  let currentLayout = cfg.layout || '';
  let activeElement = null;
  let currentBreakpoint = 'desktop';

  function updateBreakpoint(bp){
    currentBreakpoint = bp;
    frame.classList.remove('pb-preview-desktop','pb-preview-tablet','pb-preview-mobile');
    frame.classList.add('pb-preview-'+bp);
    bpButtons.forEach(b=>b.classList.toggle('active',b.dataset.bp===bp));
  }

  async function fetchWidget(name){
    const res = await fetch(`../pagebuilder/fetch_widget.php?name=${encodeURIComponent(name)}`);
    return res.ok ? await res.text() : '';
  }

  async function insertWidget(name){
    const html = await fetchWidget(name);
    if(!html) return;
    const doc = frame.contentDocument;
    const wrapper = doc.createElement('div');
    wrapper.className = 'pb-item';
    wrapper.dataset.widget = name;
    wrapper.innerHTML = html;
    doc.body.appendChild(wrapper);
    makeEditable(doc);
    markDirty();
  }

  function applyConfig(el,cfg){
    cfg = cfg || {};
    el.style.fontSize = cfg.fontSize || '';
    el.style.color = cfg.color || '';
    el.style.background = cfg.background || '';
    el.style.padding = cfg.padding || '';
    el.style.margin = cfg.margin || '';
  }

  function openConfigPanel(el){
    activeElement = el;
    if(!configPanel) return;
    const cfg = el.dataset.config ? JSON.parse(el.dataset.config) : {};
    configPanel.innerHTML = `
      <div class="pb-config-bp flex justify-between items-center mb-2"><span>${currentBreakpoint.toUpperCase()}</span><button type="button" class="pb-close">✕</button></div>
      <label>Schriftgröße <input type="text" name="fontSize" value="${cfg.fontSize||''}"></label>
      <label>Textfarbe <input type="color" name="color" value="${cfg.color||'#000000'}"></label>
      <label>Hintergrund <input type="color" name="background" value="${cfg.background||'#ffffff'}"></label>
      <label>Padding <input type="text" name="padding" value="${cfg.padding||''}"></label>
      <label>Margin <input type="text" name="margin" value="${cfg.margin||''}"></label>
      <div class="pb-config-actions"><button type="button" class="pb-reset">Reset</button></div>`;
    configPanel.classList.add('active');
    configPanel.querySelector('.pb-close').addEventListener('click',()=>configPanel.classList.remove('active'));
    function update(){
      const data = {
        fontSize: configPanel.querySelector('[name="fontSize"]').value,
        color: configPanel.querySelector('[name="color"]').value,
        background: configPanel.querySelector('[name="background"]').value,
        padding: configPanel.querySelector('[name="padding"]').value,
        margin: configPanel.querySelector('[name="margin"]').value
      };
      el.dataset.config = JSON.stringify(data);
      applyConfig(el,data);
      markDirty();
    }
    configPanel.querySelectorAll('input').forEach(inp=>{
      inp.addEventListener('input',update);
      inp.addEventListener('change',update);
    });
    configPanel.querySelector('.pb-reset').addEventListener('click',()=>{
      el.removeAttribute('data-config');
      applyConfig(el,{});
      configPanel.classList.remove('active');
      markDirty();
    });
  }

  function addControls(el,doc){
    if(el.querySelector('.pb-controls')) return;
    const controls = doc.createElement('div');
    controls.className = 'pb-controls';
    controls.innerHTML = '<button type="button" class="pb-edit">✎</button> <button type="button" class="pb-duplicate">⧉</button> <button type="button" class="pb-delete">🗑️</button>';
    controls.querySelector('.pb-edit').addEventListener('click',e=>{e.stopPropagation();openConfigPanel(el);});
    controls.querySelector('.pb-delete').addEventListener('click',e=>{e.stopPropagation();el.remove();markDirty();});
    controls.querySelector('.pb-duplicate').addEventListener('click',e=>{e.stopPropagation();const clone=el.cloneNode(true);const c=clone.querySelector('.pb-controls');if(c) c.remove();el.after(clone);makeEditable(doc);markDirty();});
    el.appendChild(controls);
  }

  function makeEditable(doc){
    doc.querySelectorAll('.pb-item').forEach(el=>{
      addControls(el,doc);
      if(el.dataset.config){
        try{applyConfig(el,JSON.parse(el.dataset.config));}catch(e){}
      }
    });
    doc.querySelectorAll('h1,h2,h3,h4,h5,h6,p,div,li,span').forEach(node=>{
      if(node.children.length===0 && node.tagName!=='IMG') node.setAttribute('contenteditable','true');
    });
    doc.querySelectorAll('img').forEach(img=>{
      img.addEventListener('click',()=>{
        const input=document.createElement('input');
        input.type='file';
        input.accept='image/*';
        input.onchange=()=>{
          const file=input.files[0];
          if(file){
            const reader=new FileReader();
            reader.onload=e=>{img.src=e.target.result;markDirty();};
            reader.readAsDataURL(file);
          }
        };
        input.click();
      });
    });
    new Sortable(doc.body,{animation:150,draggable:'.pb-item',onEnd:markDirty});
    doc.addEventListener('click',e=>{
      const item=e.target.closest('.pb-item');
      if(item && !e.target.closest('.pb-controls')) openConfigPanel(item);
    });
  }

  function markDirty(){
    currentLayout = frame.contentDocument.body.innerHTML;
  }

  async function loadPreview(){
    if(!currentSlug && !currentLayout){ frame.srcdoc=''; return; }
    const res = await fetch('../pagebuilder/render_preview.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({slug:currentSlug,layout:currentLayout})});
    frame.srcdoc = res.ok ? await res.text() : '';
  }

  async function loadPage(slug){
    if(!slug){ currentSlug=''; currentId=0; currentLayout=''; loadPreview(); return; }
    const res = await fetch('../pagebuilder/load_page.php?slug='+encodeURIComponent(slug));
    if(res.ok){
      const data = await res.json();
      currentSlug = data.slug;
      currentId = data.id;
      pageTitle.value = data.title;
      currentLayout = data.layout;
      await loadPreview();
    }
  }

  async function savePage(){
    if(!currentSlug) return;
    currentLayout = frame.contentDocument.body.innerHTML;
    const payload = {id:currentId,title:pageTitle.value,slug:currentSlug,layout:currentLayout};
    const res = await fetch('../pagebuilder/save_page.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
    if(res.ok){ alert('Gespeichert'); } else { alert('Fehler beim Speichern'); }
  }

  frame.addEventListener('load',()=>{
    const doc = frame.contentDocument;
    if(doc) makeEditable(doc);
  });

  pageSelect.addEventListener('change',()=>{ loadPage(pageSelect.value); });
  saveBtn.addEventListener('click',savePage);

  widgetBar.querySelectorAll('[data-widget]').forEach(btn=>{
    btn.addEventListener('click',()=>insertWidget(btn.dataset.widget));
  });

  bpButtons.forEach(btn=>{ btn.addEventListener('click',()=>updateBreakpoint(btn.dataset.bp)); });
  updateBreakpoint(currentBreakpoint);

  loadPreview();
})();
