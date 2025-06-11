// JavaScript für den modularen Page Builder
function initBuilder() {
  const canvas = document.getElementById('builderCanvas');
  const widgetBar = document.getElementById('widgetBar');
  const saveBtn = document.getElementById('pbSave');
  const bpButtons = document.querySelectorAll('.pb-bp-btn');
  const titleInput = document.getElementById('pbTitle');
  const slugInput = document.getElementById('pbSlug');

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

  if (loadUrl) {
    fetch(loadUrl)
      .then(r => r.ok ? r.json() : null)
      .then(data => {
        if (data && data.layout) {
          canvas.innerHTML = data.layout;
          canvas.querySelectorAll('.pb-item').forEach(makeEditable);
          localStorage.setItem('pb-builder-content', canvas.innerHTML);
          pageId = data.id || pageId;
          if (titleInput) titleInput.value = data.title || '';
          if (slugInput) slugInput.value = data.slug || '';
        } else restoreLocal();
      })
      .catch(restoreLocal);
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

  function openConfigPanel(el) {
    const cfg = el.dataset.config ? JSON.parse(el.dataset.config) : {};
    const bpCfg = cfg.breakpoints ? cfg.breakpoints[currentBreakpoint] || {} : cfg;
    const overlay = document.createElement('div');
    overlay.className = 'pb-config-overlay';
    let widgetFields = '';
    if (el.dataset.widget === 'product_grid') {
      widgetFields += `<label>Kategorie-ID <input type="number" name="category" value="${cfg.category || ''}"></label>`;
      widgetFields += `<label>Anzahl <input type="number" name="limit" value="${cfg.limit || 6}"></label>`;
    } else if (el.dataset.widget === 'category_list') {
      widgetFields += `<label>Anzahl <input type="number" name="limit" value="${cfg.limit || 10}"></label>`;
    }

    overlay.innerHTML = `<div class="pb-config">
      <div class="pb-config-bp">${currentBreakpoint.toUpperCase()}</div>
      <label>Schriftgr\u00f6\u00dfe <input type="text" name="fontSize" value="${bpCfg.fontSize || ''}"></label>
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
      <label>Ausl\u00f6ser
        <select name="animTrigger">
          <option value="scroll" ${!cfg.animTrigger || cfg.animTrigger==='scroll' ? 'selected' : ''}>Beim Scrollen sichtbar</option>
          <option value="hover" ${cfg.animTrigger==='hover' ? 'selected' : ''}>Hover</option>
          <option value="click" ${cfg.animTrigger==='click' ? 'selected' : ''}>Klick</option>
        </select>
      </label>
      <label><input type="checkbox" name="hideMobile" ${cfg.hideMobile ? 'checked' : ''}> Auf mobilen Ger\u00e4ten ausblenden</label>
      <label><input type="checkbox" name="hideDesktop" ${cfg.hideDesktop ? 'checked' : ''}> Auf Desktops ausblenden</label>
      <div class="pb-config-actions">
        <button type="button" class="pb-cancel">Abbrechen</button>
        <button type="button" class="pb-save">Speichern</button>
      </div>
    </div>`;
    document.body.appendChild(overlay);
    overlay.querySelector('.pb-cancel').addEventListener('click', () => overlay.remove());
    overlay.querySelector('.pb-save').addEventListener('click', () => {
      const data = el.dataset.config ? JSON.parse(el.dataset.config) : {};
      if (!data.breakpoints) data.breakpoints = {};
      data.breakpoints[currentBreakpoint] = {
        fontSize: overlay.querySelector('input[name="fontSize"]').value.trim(),
        color: overlay.querySelector('input[name="color"]').value,
        background: overlay.querySelector('input[name="background"]').value,
        padding: overlay.querySelector('input[name="padding"]').value.trim(),
        margin: overlay.querySelector('input[name="margin"]').value.trim()
      };
      data.hideMobile = overlay.querySelector('input[name="hideMobile"]').checked;
      data.hideDesktop = overlay.querySelector('input[name="hideDesktop"]').checked;
      data.animation = overlay.querySelector('select[name="animation"]').value;
      data.animTrigger = overlay.querySelector('select[name="animTrigger"]').value;
      if (el.dataset.widget === 'product_grid') {
        data.category = overlay.querySelector('input[name="category"]').value.trim();
        data.limit = overlay.querySelector('input[name="limit"]').value.trim();
      } else if (el.dataset.widget === 'category_list') {
        data.limit = overlay.querySelector('input[name="limit"]').value.trim();
      }
      el.dataset.config = JSON.stringify(data);
      applyConfig(el, data);
      save();
      overlay.remove();
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

  canvas.addEventListener('input', save);

  if (saveBtn) saveBtn.addEventListener('click', saveToServer);
}

document.addEventListener('DOMContentLoaded', initBuilder);
