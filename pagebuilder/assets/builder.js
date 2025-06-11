// JavaScript für den modularen Page Builder
function initBuilder() {
  const canvas = document.getElementById('builderCanvas');
  const widgetBar = document.getElementById('widgetBar');
  const configPanel = document.getElementById('pbConfigPanel');
  const saveBtn = document.getElementById('pbSave');
  const bpButtons = document.querySelectorAll('.pb-bp-btn');
  const titleInput = document.getElementById('pbTitle');
  const slugInput = document.getElementById('pbSlug');
  const pageSelect = document.getElementById('pbPageSelect');
  const pageSearch = document.getElementById('pbPageSearch');

  const saveUrl = canvas ? canvas.dataset.saveUrl : null;
  const loadUrl = canvas && canvas.dataset.loadUrl ? canvas.dataset.loadUrl : null;
  let pageId = canvas ? canvas.dataset.pageId : 0;
  let currentBreakpoint = 'desktop';

  if (!canvas || !widgetBar) return;

  function restoreLocal() {
    const saved = localStorage.getItem('pb-builder-content');
    if (saved) {
      canvas.innerHTML = saved;
      canvas.querySelectorAll('.pb-item').forEach(makeEditable);
    }
  }

  async function loadPage(url, defTitle = '', defSlug = '') {
    if (!url) { restoreLocal(); return; }
    try {
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
    }
  }

  if (loadUrl) {
    loadPage(loadUrl, titleInput ? titleInput.value : '', slugInput ? slugInput.value : '');
  } else {
    restoreLocal();
  }

  new Sortable(canvas, { animation: 150, onSort: save });

  function updateBreakpoint(bp) {
    currentBreakpoint = bp;
    canvas.classList.remove('pb-preview-desktop', 'pb-preview-tablet', 'pb-preview-mobile');
    canvas.classList.add('pb-preview-' + bp);
    bpButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.bp === bp));
    updateAllConfigs();
    if (window.initWidgetAnimations) window.initWidgetAnimations();
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

  function save() {
    localStorage.setItem('pb-builder-content', canvas.innerHTML);
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
    const res = await fetch(saveUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
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

    // Einstellungs-Button hinzufügen
    if (!el.querySelector('.pb-controls')) {
      const controls = document.createElement('div');
      controls.className = 'pb-controls';
      controls.innerHTML = '<button type="button">Einstellungen</button>';
      controls.querySelector('button').addEventListener('click', (e) => {
        e.stopPropagation();
        openConfigPanel(el);
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
    el.style.fontSize = bp.fontSize || '';
    el.style.color = bp.color || '';
    el.style.background = bp.background || '';
    el.style.padding = bp.padding || '';
    el.style.margin = bp.margin || '';
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
    if (cfg.animation) el.dataset.animation = cfg.animation; else delete el.dataset.animation;
    if (cfg.animTrigger) el.dataset.animTrigger = cfg.animTrigger; else delete el.dataset.animTrigger;
    if (window.initWidgetAnimations) window.initWidgetAnimations();
  }

  let activeElement = null;

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

  if (pageSelect) {
    pageSelect.addEventListener('change', () => {
      const slug = pageSelect.value;
      const title = pageSelect.options[pageSelect.selectedIndex].textContent;
      loadPage(`../pagebuilder/load_page.php?slug=${encodeURIComponent(slug)}`, title, slug);
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
