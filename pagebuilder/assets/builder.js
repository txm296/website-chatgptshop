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
