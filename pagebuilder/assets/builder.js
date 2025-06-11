// JavaScript für den modularen Page Builder
function initBuilder() {
  const canvas = document.getElementById('builderCanvas');
  const widgetBar = document.getElementById('widgetBar');

  if (!canvas || !widgetBar) return;

  const saved = localStorage.getItem('pb-builder-content');
  if (saved) {
    canvas.innerHTML = saved;
    canvas.querySelectorAll('.pb-item').forEach(makeEditable);
  }

  new Sortable(canvas, { animation: 150, onSort: save });

  function save() {
    localStorage.setItem('pb-builder-content', canvas.innerHTML);
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
    el.style.fontSize = cfg.fontSize || '';
    el.style.color = cfg.color || '';
    el.style.background = cfg.background || '';
    el.style.padding = cfg.padding || '';
    el.style.margin = cfg.margin || '';
    if (cfg.hideMobile) el.classList.add('pb-hide-mobile');
    else el.classList.remove('pb-hide-mobile');
    if (cfg.hideDesktop) el.classList.add('pb-hide-desktop');
    else el.classList.remove('pb-hide-desktop');
  }

  function openConfigPanel(el) {
    const cfg = el.dataset.config ? JSON.parse(el.dataset.config) : {};
    const overlay = document.createElement('div');
    overlay.className = 'pb-config-overlay';
    overlay.innerHTML = `<div class="pb-config">
      <label>Schriftgr\u00f6\u00dfe <input type="text" name="fontSize" value="${cfg.fontSize || ''}"></label>
      <label>Textfarbe <input type="color" name="color" value="${cfg.color || '#000000'}"></label>
      <label>Hintergrund <input type="color" name="background" value="${cfg.background || '#ffffff'}"></label>
      <label>Padding <input type="text" name="padding" value="${cfg.padding || ''}"></label>
      <label>Margin <input type="text" name="margin" value="${cfg.margin || ''}"></label>
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
      const data = {
        fontSize: overlay.querySelector('input[name="fontSize"]').value.trim(),
        color: overlay.querySelector('input[name="color"]').value,
        background: overlay.querySelector('input[name="background"]').value,
        padding: overlay.querySelector('input[name="padding"]').value.trim(),
        margin: overlay.querySelector('input[name="margin"]').value.trim(),
        hideMobile: overlay.querySelector('input[name="hideMobile"]').checked,
        hideDesktop: overlay.querySelector('input[name="hideDesktop"]').checked
      };
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
    wrapper.innerHTML = html;
    canvas.appendChild(wrapper);
    makeEditable(wrapper);
    save();
  }

  widgetBar.querySelectorAll('button[data-widget]').forEach(btn => {
    btn.draggable = true;

    btn.addEventListener('dragstart', e => {
      e.dataTransfer.setData('text/plain', btn.dataset.widget);
    });

    btn.addEventListener('click', () => insertWidget(btn.dataset.widget));
  });

  canvas.addEventListener('dragover', e => e.preventDefault());
  canvas.addEventListener('drop', e => {
    e.preventDefault();
    const name = e.dataTransfer.getData('text/plain');
    if (name) insertWidget(name);
  });

  canvas.addEventListener('input', save);
}

document.addEventListener('DOMContentLoaded', initBuilder);
