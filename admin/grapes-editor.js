// Advanced page editor using GrapesJS
// Provides drag and drop interface with styling options
// Loads existing HTML from hidden field #contentInput and updates it on save

document.addEventListener('DOMContentLoaded', () => {
  const existing = document.getElementById('contentInput');
  const editor = grapesjs.init({
    container: '#gjs',
    height: '600px',
    fromElement: false,
    storageManager: false,
    plugins: ['gjs-preset-webpage'],
  });

  if (existing && existing.value.trim()) {
    editor.setComponents(existing.value);
  }

  const form = document.getElementById('pageForm');
  if (form && existing) {
    form.addEventListener('submit', () => {
      existing.value = editor.getHtml();
    });
  }
});
