(function(){
  const cfg = window.liveEditorConfig || {};
  const frame = document.getElementById('editorFrame');
  const pageSelect = document.getElementById('pageSelect');
  const saveBtn = document.getElementById('savePage');
  const addTextBtn = document.getElementById('addText');
  const addImageBtn = document.getElementById('addImage');
  const pageTitle = document.getElementById('pageTitle');
  let currentSlug = cfg.slug || '';
  let currentId = cfg.id || 0;
  let currentLayout = cfg.layout || '';

  async function loadPreview(){
    if(!currentSlug && !currentLayout){ frame.srcdoc = ''; return; }
    const res = await fetch('../pagebuilder/render_preview.php', {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({slug: currentSlug, layout: currentLayout})
    });
    frame.srcdoc = res.ok ? await res.text() : '';
  }

  function makeEditable(doc){
    doc.querySelectorAll('h1,h2,h3,h4,h5,h6,p,div,li,span').forEach(el=>{
      if(el.children.length===0 && el.tagName!=='IMG'){
        el.setAttribute('contenteditable','true');
      }
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
            reader.onload=e=>{ img.src=e.target.result; markDirty(); };
            reader.readAsDataURL(file);
          }
        };
        input.click();
      });
    });
    new Sortable(doc.body,{animation:150,draggable:'.pb-item',onEnd:markDirty});
  }

  function markDirty(){
    currentLayout = frame.contentDocument.body.innerHTML;
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
    const res = await fetch('../pagebuilder/save_page.php', {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify(payload)
    });
    if(res.ok){ alert('Gespeichert'); }
    else alert('Fehler beim Speichern');
  }

  frame.addEventListener('load',()=>{
    const doc = frame.contentDocument;
    if(doc) makeEditable(doc);
  });

  pageSelect.addEventListener('change',()=>{
    const slug = pageSelect.value;
    loadPage(slug);
  });

  saveBtn.addEventListener('click',savePage);

  addTextBtn.addEventListener('click',()=>{
    const doc=frame.contentDocument; if(!doc) return;
    const div=doc.createElement('div');
    div.className='pb-item';
    div.textContent='Neuer Text';
    doc.body.appendChild(div); makeEditable(doc); markDirty();
  });
  addImageBtn.addEventListener('click',()=>{
    const doc=frame.contentDocument; if(!doc) return;
    const img=doc.createElement('img');
    img.src='';
    const wrap=doc.createElement('div');
    wrap.className='pb-item';
    wrap.appendChild(img); doc.body.appendChild(wrap);
    makeEditable(doc); markDirty();
  });

  loadPreview();
})();
