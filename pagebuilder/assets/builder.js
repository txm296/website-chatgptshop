// JavaScript für den modularen Page Builder
function initBuilder() {
  const canvas = document.getElementById('builderCanvas');
  const widgetBar = document.getElementById('widgetBar');
  const configPanel = document.getElementById('pbConfigPanel');
  const saveBtn = document.getElementById('pbSave');
  const pasteBtn = document.getElementById('pbPaste');
  const templateSelect = document.getElementById('pbTemplateSelect');
  const insertTemplateBtn = document.getElementById('pbInsertTemplate');
  const bpButtons = document.querySelectorAll('.pb-bp-btn');
  const titleInput = document.getElementById('pbTitle');
  const slugInput = document.getElementById('pbSlug');
  const optimizeBtn = document.getElementById('pbOptimizeMobile');
  const undoBtn = document.getElementById('pbUndoMobile');
  const pageSelect = document.getElementById('pbPageSelect');
  const pageSearch = document.getElementById('pbPageSearch');
  const previewFrame = document.getElementById('pbPreviewFrame');

  const saveUrl = canvas ? canvas.dataset.saveUrl : null;
  const loadUrl = canvas && canvas.dataset.loadUrl ? canvas.dataset.loadUrl : null;
  let pageId = canvas ? canvas.dataset.pageId : 0;
  let currentBreakpoint = 'desktop';
  let prevLayout = null;
  let previewTimer = null;

  if (!canvas || !widgetBar) return;

  function restoreLocal() {
    const saved = localStorage.getItem('pb-builder-content');
    if (saved) {
      canvas.innerHTML = saved;
      canvas.querySelectorAll('.pb-item').forEach(makeEditable);
    }
  }

  async function loadPage(url, defTitle = '', defSlug = '') {
    console.log('loadPage', url);
    if (!url) {
      canvas.innerHTML = '';
      pageId = 0;
      canvas.dataset.pageId = 0;
      localStorage.removeItem('pb-builder-content');
      if (titleInput) titleInput.value = defTitle;
      if (slugInput) slugInput.value = defSlug;
      if (pageSelect) pageSelect.value = defSlug;
      updatePreview();
      return;
    }
    try {
      console.log('fetch', url);
      const res = await fetch(url);
      if (res.ok) {
        const data = await res.json();
        canvas.innerHTML = data.layout || '';
        canvas.querySelectorAll('.pb-item').forEach(makeEditable);
        localStorage.setItem('pb-builder-content', canvas.innerHTML);
        pageId = data.id || 0;
        canvas.dataset.pageId = pageId;
        if (titleInput) titleInput.value = data.title || defTitle;
        if (slugInput) slugInput.value = data.slug || defSlug;
        if (pageSelect) pageSelect.value = slugInput.value;
        updatePreview();
      } else {
        canvas.innerHTML = '';
        pageId = 0;
        canvas.dataset.pageId = 0;
        localStorage.removeItem('pb-builder-content');
        if (titleInput) titleInput.value = defTitle;
        if (slugInput) slugInput.value = defSlug;
        if (pageSelect) pageSelect.value = defSlug;
      }
    } catch (e) {
      console.error(e);
      restoreLocal();
      updatePreview();
    }
  }

  if (loadUrl) {
    loadPage(loadUrl, titleInput ? titleInput.value : '', slugInput ? slugInput.value : '');
  } else {
    loadPage('', titleInput ? titleInput.value : '', slugInput ? slugInput.value : '');
  }

  new Sortable(canvas, { animation: 150, onSort: save });

  function updateBreakpoint(bp) {
    currentBreakpoint = bp;
    canvas.classList.remove('pb-preview-desktop', 'pb-preview-tablet', 'pb-preview-mobile');
    canvas.classList.add('pb-preview-' + bp);
    bpButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.bp === bp));
    updateAllConfigs();
    if (window.initWidgetAnimations) window.initWidgetAnimations();
    if (window.initVideos) window.initVideos();
  }

  function updateAllConfigs() {
    canvas.querySelectorAll('.pb-item').forEach(el => {
      if (el.dataset.config) {
        try {
          const c = JSON.parse(el.dataset.config);
          applyConfig(el, c);
        } catch (e) {
          console.error('Config Fehler', e);
        }
      }
    });
  }

  async function updatePreview() {
    if (!previewFrame) return;
    const slug = slugInput ? slugInput.value : '';
    try {
      const res = await fetch('../pagebuilder/render_preview.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ slug, layout: canvas.innerHTML })
      });
      if (res.ok) {
        const html = await res.text();
        previewFrame.srcdoc = html;
      }
    } catch (e) {
      console.error(e);
    }
  }

  function save() {
    localStorage.setItem('pb-builder-content', canvas.innerHTML);
    if (previewTimer) clearTimeout(previewTimer);
    previewTimer = setTimeout(updatePreview, 300);
  }

  async function saveToServer() {
    save();
    if (!saveUrl) return;
    const payload = {
      id: parseInt(pageId || 0, 10),
      title: titleInput ? titleInput.value : '',
      slug: slugInput ? slugInput.value : '',
      layout: canvas.innerHTML
    };
    console.log('save payload', payload);
    const res = await fetch(saveUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    console.log('save response', res.status);
    if (res.ok) {
      const data = await res.json();
      pageId = data.id || pageId;
      canvas.dataset.pageId = pageId;
      alert('Gespeichert');
    } else {
      alert('Fehler beim Speichern');
    }
  }

  function makeEditable(el) {
    el.querySelectorAll('h1,h2,h3,h4,h5,h6,p,div,li,span').forEach(node => {
      if (node.children.length === 0 && node.tagName !== 'IMG') {
        node.setAttribute('contenteditable', 'true');
      }
    });
    el.addEventListener('input', save);

    // Hover Controls hinzufügen
    if (!el.querySelector('.pb-controls')) {
      const controls = document.createElement('div');
      controls.className = 'pb-controls';
      controls.innerHTML =
        '<button type="button" class="pb-edit" title="Bearbeiten">✎</button>' +
        ' <button type="button" class="pb-duplicate" title="Duplizieren">⧉</button>' +
        ' <button type="button" class="pb-delete" title="Löschen">🗑️</button>';

      controls.querySelector('.pb-edit').addEventListener('click', (e) => {
        e.stopPropagation();
        openConfigPanel(el);
      });
      controls.querySelector('.pb-delete').addEventListener('click', (e) => {
        e.stopPropagation();
        deleteItem(el);
      });
      controls.querySelector('.pb-duplicate').addEventListener('click', (e) => {
        e.stopPropagation();
        duplicateItem(el);
      });

      el.appendChild(controls);
    }

    // vorhandene Konfiguration anwenden
    if (el.dataset.config) {
      try {
        const cfg = JSON.parse(el.dataset.config);
        applyConfig(el, cfg);
      } catch (e) {
        console.error('Config Fehler', e);
      }
    }
  }

  function applyConfig(el, cfg) {
    if (!cfg) return;
    const bp = cfg.breakpoints ? cfg.breakpoints[currentBreakpoint] || {} : cfg;
    const mob = currentBreakpoint === 'mobile' && cfg.style_mobile ? cfg.style_mobile : {};
    el.style.fontSize = mob.fontSize || bp.fontSize || '';
    el.style.color = mob.color || bp.color || '';
    el.style.background = mob.background || bp.background || '';
    el.style.padding = mob.padding || bp.padding || '';
    el.style.margin = mob.margin || bp.margin || '';
    el.style.width = mob.width || bp.width || '';
    el.style.flexDirection = mob.flexDirection || '';
    el.style.gridTemplateColumns = mob.gridTemplateColumns || '';
    if (cfg.hideMobile) el.classList.add('pb-hide-mobile');
    else el.classList.remove('pb-hide-mobile');
    if (cfg.hideDesktop) el.classList.add('pb-hide-desktop');
    else el.classList.remove('pb-hide-desktop');

    if (el.dataset.widget === 'product_grid') {
      if (cfg.category !== undefined) el.dataset.category = cfg.category;
      if (cfg.limit !== undefined) el.dataset.limit = cfg.limit;
    }
    if (el.dataset.widget === 'category_list') {
      if (cfg.limit !== undefined) el.dataset.limit = cfg.limit;
    }
    if (el.dataset.widget === 'video') {
      if (cfg.videoType !== undefined) el.dataset.type = cfg.videoType;
      if (cfg.videoSrc !== undefined) el.dataset.src = cfg.videoSrc;
      if (cfg.videoPreview !== undefined) {
        if (cfg.videoPreview) el.dataset.preview = cfg.videoPreview;
        else delete el.dataset.preview;
      }
    }
    if (cfg.animation) el.dataset.animation = cfg.animation; else delete el.dataset.animation;
    if (cfg.animTrigger) el.dataset.animTrigger = cfg.animTrigger; else delete el.dataset.animTrigger;
    if (window.initWidgetAnimations) window.initWidgetAnimations();
    if (window.initVideos) window.initVideos();
  }

  let activeElement = null;

  function copyItem(el) {
    const html = el.outerHTML;
    localStorage.setItem('pb-clipboard', html);
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(html).catch(() => {});
    }
  }

  function deleteItem(el) {
    el.remove();
    save();
  }

  function duplicateItem(el) {
    const clone = el.cloneNode(true);
    const oldControls = clone.querySelector('.pb-controls');
    if (oldControls) oldControls.remove();
    el.after(clone);
    makeEditable(clone);
    save();
    if (window.initWidgetAnimations) window.initWidgetAnimations();
    if (window.initVideos) window.initVideos();
  }

  async function pasteFromClipboard() {
    let html = localStorage.getItem('pb-clipboard') || '';
    if (!html && navigator.clipboard && navigator.clipboard.readText) {
      try { html = await navigator.clipboard.readText(); } catch (e) {}
    }
    if (html) insertHTML(html);
  }

  async function saveTemplate(el) {
    const name = prompt('Name der Vorlage?');
    if (!name) return;
    const res = await fetch('../pagebuilder/save_template.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name, html: el.outerHTML })
    });
    if (res.ok) loadTemplates();
  }

  function insertHTML(html) {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    tmp.querySelectorAll('.pb-item').forEach(it => {
      canvas.appendChild(it);
      makeEditable(it);
    });
    save();
    if (window.initWidgetAnimations) window.initWidgetAnimations();
    if (window.initVideos) window.initVideos();
  }

  async function insertSelectedTemplate() {
    if (!templateSelect || !templateSelect.value) return;
    const res = await fetch(`../pagebuilder/get_template.php?id=${templateSelect.value}`);
    if (res.ok) {
      const data = await res.json();
      insertHTML(data.html);
    }
  }

  async function loadTemplates() {
    if (!templateSelect) return;
    const res = await fetch('../pagebuilder/list_templates.php');
    if (res.ok) {
      const list = await res.json();
      templateSelect.innerHTML = '<option value="">Vorlage wählen</option>';
      list.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t.id;
        opt.textContent = t.name;
        templateSelect.appendChild(opt);
      });
    }
  }

  function halfValue(val, factor = 0.5) {
    const m = String(val).match(/([\d.]+)([a-z%]+)/);
    if (!m) return '';
    return (parseFloat(m[1]) * factor) + m[2];
  }

  function generateMobileStyle(el) {
    const s = window.getComputedStyle(el);
    const style = { width: '100%' };
    style.padding = halfValue(s.paddingTop);
    style.margin = halfValue(s.marginTop);
    const textNode = el.querySelector('h1,h2,h3,h4,h5,h6,p,span,div,li');
    if (textNode) {
      const fs = window.getComputedStyle(textNode).fontSize;
      style.fontSize = halfValue(fs, 0.8);
    }
    if (el.classList.contains('pb-column')) {
      style.gridTemplateColumns = '1fr';
    }
    el.querySelectorAll('img').forEach(img => {
      img.style.maxWidth = '100%';
      img.style.height = 'auto';
    });
    return style;
  }

  function optimizeMobile() {
    prevLayout = canvas.innerHTML;
    canvas.querySelectorAll('.pb-item').forEach(el => {
      const cfg = el.dataset.config ? JSON.parse(el.dataset.config) : {};
      cfg.style_mobile = generateMobileStyle(el);
      el.dataset.config = JSON.stringify(cfg);
      if (currentBreakpoint === 'mobile') applyConfig(el, cfg);
    });
    save();
    updateBreakpoint('mobile');
    if (undoBtn) undoBtn.style.display = 'inline-block';
  }

  function undoOptimize() {
    if (!prevLayout) return;
    canvas.innerHTML = prevLayout;
    canvas.querySelectorAll('.pb-item').forEach(makeEditable);
    prevLayout = null;
    save();
    if (undoBtn) undoBtn.style.display = 'none';
  }

  function openConfigPanel(el) {
    activeElement = el;
    if (!configPanel) return;
    const cfg = el.dataset.config ? JSON.parse(el.dataset.config) : {};
    const bpCfg = cfg.breakpoints ? cfg.breakpoints[currentBreakpoint] || {} : cfg;
    let widgetFields = '';
    if (el.dataset.widget === 'product_grid') {
      widgetFields += `<label>Kategorie-ID <input type="number" name="category" value="${cfg.category || ''}"></label>`;
      widgetFields += `<label>Anzahl <input type="number" name="limit" value="${cfg.limit || 6}"></label>`;
    } else if (el.dataset.widget === 'category_list') {
      widgetFields += `<label>Anzahl <input type="number" name="limit" value="${cfg.limit || 10}"></label>`;
    } else if (el.dataset.widget === 'video') {
      widgetFields += `<label>Typ
        <select name="videoType">
          <option value="youtube" ${!cfg.videoType || cfg.videoType==='youtube' ? 'selected' : ''}>YouTube</option>
          <option value="vimeo" ${cfg.videoType==='vimeo' ? 'selected' : ''}>Vimeo</option>
          <option value="mp4" ${cfg.videoType==='mp4' ? 'selected' : ''}>MP4</option>
        </select>
      </label>`;
      widgetFields += `<label>Quelle/URL <input type="text" name="videoSrc" value="${cfg.videoSrc || ''}"></label>`;
      widgetFields += `<label>Vorschaubild <input type="text" name="videoPreview" value="${cfg.videoPreview || ''}"></label>`;
    }

    configPanel.innerHTML = `<div class="pb-config-bp flex justify-between items-center mb-2"><span>${currentBreakpoint.toUpperCase()}</span><button type="button" class="pb-close">✕</button></div>
      <label>Schriftgröße <input type="text" name="fontSize" value="${bpCfg.fontSize || ''}"></label>
      <label>Textfarbe <input type="color" name="color" value="${bpCfg.color || '#000000'}"></label>
      <label>Hintergrund <input type="color" name="background" value="${bpCfg.background || '#ffffff'}"></label>
      <label>Padding <input type="text" name="padding" value="${bpCfg.padding || ''}"></label>
      <label>Margin <input type="text" name="margin" value="${bpCfg.margin || ''}"></label>
      ${widgetFields}
      <label>Animation
        <select name="animation">
          <option value="" ${!cfg.animation ? 'selected' : ''}>Keine</option>
          <option value="fade" ${cfg.animation==='fade' ? 'selected' : ''}>Fade In</option>
          <option value="slide-up" ${cfg.animation==='slide-up' ? 'selected' : ''}>Slide Up</option>
          <option value="slide-left" ${cfg.animation==='slide-left' ? 'selected' : ''}>Slide Left</option>
          <option value="zoom" ${cfg.animation==='zoom' ? 'selected' : ''}>Zoom In</option>
        </select>
      </label>
      <label>Auslöser
        <select name="animTrigger">
          <option value="scroll" ${!cfg.animTrigger || cfg.animTrigger==='scroll' ? 'selected' : ''}>Beim Scrollen sichtbar</option>
          <option value="hover" ${cfg.animTrigger==='hover' ? 'selected' : ''}>Hover</option>
          <option value="click" ${cfg.animTrigger==='click' ? 'selected' : ''}>Klick</option>
        </select>
      </label>
      <label><input type="checkbox" name="hideMobile" ${cfg.hideMobile ? 'checked' : ''}> Auf mobilen Geräten ausblenden</label>
      <label><input type="checkbox" name="hideDesktop" ${cfg.hideDesktop ? 'checked' : ''}> Auf Desktops ausblenden</label>
      <div class="text-right"><button type="button" class="pb-reset bg-gray-200 rounded px-2 py-1 mt-2">Zurücksetzen</button></div>`;
    configPanel.classList.add('active');

    const closeBtn = configPanel.querySelector('.pb-close');
    if (closeBtn) closeBtn.addEventListener('click', () => configPanel.classList.remove('active'));

    function updateConfig() {
      const data = el.dataset.config ? JSON.parse(el.dataset.config) : {};
      if (!data.breakpoints) data.breakpoints = {};
      data.breakpoints[currentBreakpoint] = {
        fontSize: configPanel.querySelector('input[name="fontSize"]').value.trim(),
        color: configPanel.querySelector('input[name="color"]').value,
        background: configPanel.querySelector('input[name="background"]').value,
        padding: configPanel.querySelector('input[name="padding"]').value.trim(),
        margin: configPanel.querySelector('input[name="margin"]').value.trim()
      };
      data.hideMobile = configPanel.querySelector('input[name="hideMobile"]').checked;
      data.hideDesktop = configPanel.querySelector('input[name="hideDesktop"]').checked;
      data.animation = configPanel.querySelector('select[name="animation"]').value;
      data.animTrigger = configPanel.querySelector('select[name="animTrigger"]').value;
      if (el.dataset.widget === 'product_grid') {
        data.category = configPanel.querySelector('input[name="category"]').value.trim();
        data.limit = configPanel.querySelector('input[name="limit"]').value.trim();
      } else if (el.dataset.widget === 'category_list') {
        data.limit = configPanel.querySelector('input[name="limit"]').value.trim();
      } else if (el.dataset.widget === 'video') {
        data.videoType = configPanel.querySelector('select[name="videoType"]').value;
        data.videoSrc = configPanel.querySelector('input[name="videoSrc"]').value.trim();
        data.videoPreview = configPanel.querySelector('input[name="videoPreview"]').value.trim();
      }
      el.dataset.config = JSON.stringify(data);
      applyConfig(el, data);
      save();
    }

   configPanel.querySelectorAll('input,select').forEach(inp => {
     inp.addEventListener('input', updateConfig);
     inp.addEventListener('change', updateConfig);
   });

    const resetBtn = configPanel.querySelector('.pb-reset');
    if (resetBtn) resetBtn.addEventListener('click', () => {
      el.removeAttribute('data-config');
      el.style.fontSize = '';
      el.style.color = '';
      el.style.background = '';
      el.style.padding = '';
      el.style.margin = '';
      el.classList.remove('pb-hide-mobile','pb-hide-desktop');
      delete el.dataset.category;
      delete el.dataset.limit;
      delete el.dataset.type;
      delete el.dataset.src;
      delete el.dataset.preview;
      delete el.dataset.animation;
      delete el.dataset.animTrigger;
      applyConfig(el, {});
      save();
      configPanel.classList.remove('active');
    });
  }

  async function fetchWidget(name) {
    const res = await fetch(`../pagebuilder/fetch_widget.php?name=${encodeURIComponent(name)}`);
    return res.ok ? await res.text() : '';
  }

  async function insertWidget(name) {
    const html = await fetchWidget(name);
    if (!html) return;
    const wrapper = document.createElement('div');
    wrapper.className = 'pb-item';
    wrapper.dataset.widget = name;
    wrapper.innerHTML = html;
    canvas.appendChild(wrapper);
    makeEditable(wrapper);
    save();
    if (window.initWidgetAnimations) window.initWidgetAnimations();
    if (window.initVideos) window.initVideos();
  }

  widgetBar.querySelectorAll('button[data-widget]').forEach(btn => {
    btn.draggable = true;

    btn.addEventListener('dragstart', e => {
      e.dataTransfer.setData('text/plain', btn.dataset.widget);
    });

    btn.addEventListener('click', () => insertWidget(btn.dataset.widget));
  });

  bpButtons.forEach(btn => {
    btn.addEventListener('click', () => updateBreakpoint(btn.dataset.bp));
  });

  updateBreakpoint(currentBreakpoint);

  canvas.addEventListener('dragover', e => e.preventDefault());
  canvas.addEventListener('drop', e => {
    e.preventDefault();
    const name = e.dataTransfer.getData('text/plain');
    if (name) insertWidget(name);
  });

  canvas.addEventListener('click', e => {
    const item = e.target.closest('.pb-item');
    if (item && !e.target.closest('.pb-controls')) openConfigPanel(item);
  });

  canvas.addEventListener('input', save);

  if (saveBtn) saveBtn.addEventListener('click', saveToServer);

  if (pasteBtn) pasteBtn.addEventListener('click', pasteFromClipboard);
  if (insertTemplateBtn) insertTemplateBtn.addEventListener('click', insertSelectedTemplate);
  if (optimizeBtn) optimizeBtn.addEventListener('click', optimizeMobile);
  if (undoBtn) undoBtn.addEventListener('click', undoOptimize);
  loadTemplates();

  if (pageSelect) {
    pageSelect.addEventListener('change', () => {
      const slug = pageSelect.value;
      const title = pageSelect.options[pageSelect.selectedIndex].textContent;
      console.log('page change', slug);
      if (slug) {
        loadPage(`../pagebuilder/load_page.php?slug=${encodeURIComponent(slug)}`, title, slug);
      } else {
        loadPage('', title, slug);
      }
    });
  }

  if (pageSearch && pageSelect) {
    pageSearch.addEventListener('input', () => {
      const q = pageSearch.value.toLowerCase();
      pageSelect.querySelectorAll('option').forEach(opt => {
        const t = opt.textContent.toLowerCase();
        const v = opt.value.toLowerCase();
        opt.hidden = q && !t.includes(q) && !v.includes(q);
      });
    });
  }
}

document.addEventListener('DOMContentLoaded', initBuilder);
