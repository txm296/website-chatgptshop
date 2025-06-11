document.addEventListener('DOMContentLoaded', () => {
  const editor = document.getElementById('editor');
  const addText = document.getElementById('addText');
  const addImage = document.getElementById('addImage');
  const contentInput = document.getElementById('contentInput');
  const previewFrame = document.getElementById('previewFrame');
  const editModeBtn = document.getElementById('editModeBtn');
  const previewModeBtn = document.getElementById('previewModeBtn');
  const publishBtn = document.getElementById('publishBtn');
  const editorSection = document.getElementById('editorSection');
  const editTools = document.getElementById('editTools');
  const previewSection = document.getElementById('previewSection');
  const pageIdInput = document.querySelector('input[name="id"]');
  const pageId = pageIdInput ? pageIdInput.value || 'new' : 'new';
  const draftKey = 'draft-content-' + pageId;

  function updatePreview() {
    if (previewFrame) {
      previewFrame.srcdoc = editor.innerHTML;
    }
    sessionStorage.setItem(draftKey, editor.innerHTML);
  }

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
    updatePreview();
  });

  addImage.addEventListener('click', () => {
    const url = prompt('Bild URL:');
    if (!url) return;
    const img = document.createElement('img');
    img.src = url;
    img.className = 'block my-1';
    makeDraggable(img);
    editor.appendChild(img);
    updatePreview();
  });

  // load existing or draft content
  const savedDraft = sessionStorage.getItem(draftKey);
  if (savedDraft) {
    editor.innerHTML = savedDraft;
  } else if (contentInput && contentInput.value.trim()) {
    editor.innerHTML = contentInput.value;
  }
  editor.querySelectorAll('.block').forEach(el => makeDraggable(el));
  updatePreview();

  const form = document.getElementById('pageForm');
  if (form) {
    form.addEventListener('submit', () => {
      contentInput.value = sessionStorage.getItem(draftKey) || editor.innerHTML;
      sessionStorage.removeItem(draftKey);
    });
  }

  if (publishBtn) {
    publishBtn.addEventListener('click', () => {
      if (form) form.requestSubmit();
    });
  }

  if (previewModeBtn && editModeBtn) {
    previewModeBtn.addEventListener('click', () => {
      updatePreview();
      if (editorSection) editorSection.classList.add('hidden');
      if (editTools) editTools.classList.add('hidden');
      if (previewSection) previewSection.classList.remove('hidden');
    });

    editModeBtn.addEventListener('click', () => {
      if (editorSection) editorSection.classList.remove('hidden');
      if (editTools) editTools.classList.remove('hidden');
      if (previewSection) previewSection.classList.remove('hidden');
    });
  }

  editor.addEventListener('input', updatePreview);
  editor.addEventListener('dragend', updatePreview);
});
