document.addEventListener('DOMContentLoaded', () => {
  const editor = document.getElementById('editor');
  const addText = document.getElementById('addText');
  const addImage = document.getElementById('addImage');
  const contentInput = document.getElementById('contentInput');

  function makeDraggable(el) {
    el.draggable = true;
    el.classList.add('cursor-move');
    el.addEventListener('dragstart', e => {
      e.dataTransfer.setData('text/plain', null);
      el.classList.add('opacity-50');
      editor.dragged = el;
    });
    el.addEventListener('dragend', () => {
      el.classList.remove('opacity-50');
      editor.dragged = null;
    });
  }

  editor.addEventListener('dragover', e => {
    e.preventDefault();
    const dragged = editor.dragged;
    if (!dragged) return;
    const target = e.target.closest('.block');
    if (target && target !== dragged) {
      const rect = target.getBoundingClientRect();
      const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;
      editor.insertBefore(dragged, next ? target.nextSibling : target);
    }
  });

  addText.addEventListener('click', () => {
    const div = document.createElement('div');
    div.contentEditable = true;
    div.className = 'block p-2 border my-1';
    div.textContent = 'Text';
    makeDraggable(div);
    editor.appendChild(div);
  });

  addImage.addEventListener('click', () => {
    const url = prompt('Bild URL:');
    if (!url) return;
    const img = document.createElement('img');
    img.src = url;
    img.className = 'block my-1';
    makeDraggable(img);
    editor.appendChild(img);
  });

  // load existing content
  if (contentInput && contentInput.value.trim()) {
    editor.innerHTML = contentInput.value;
    editor.querySelectorAll('.block').forEach(el => makeDraggable(el));
  }

  const form = document.getElementById('pageForm');
  form.addEventListener('submit', () => {
    contentInput.value = editor.innerHTML;
  });
});
