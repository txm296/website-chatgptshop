document.addEventListener('DOMContentLoaded', () => {
  const editor=document.getElementById('editor');
  const addText=document.getElementById('addText');
  const addImage=document.getElementById('addImage');
  const addSection=document.getElementById('addSection');
  const contentInput=document.getElementById('contentInput');
  const previewFrame=document.getElementById('previewFrame');
  const stylePanel=document.getElementById('stylePanel');
  const bgInput=document.getElementById('bgInput');
  const padInput=document.getElementById('padInput');
  let selected=null;

  function updatePreview(){
    previewFrame.srcdoc=editor.innerHTML;
  }

  function select(el){
    if(selected) selected.classList.remove('selected');
    selected=el; if(el){el.classList.add('selected');
      bgInput.value=rgbToHex(window.getComputedStyle(el).backgroundColor);
      padInput.value=parseInt(window.getComputedStyle(el).padding)||0;
      stylePanel.hidden=false;
    } else {
      stylePanel.hidden=true;
    }
  }

  function rgbToHex(rgb){
    const res=/rgba?\((\d+),(\d+),(\d+)/.exec(rgb);
    if(!res) return '#ffffff';
    return '#'+res.slice(1,4).map(x=>('0'+parseInt(x).toString(16)).slice(-2)).join('');
  }

  function makeEditable(el){
    el.addEventListener('click',e=>{e.stopPropagation(); select(el);});
  }

  function addWidget(type){
    let el;
    if(type==='text'){
      el=document.createElement('div');
      el.contentEditable=true;
      el.textContent='Text';
      el.className='p-2';
    }else if(type==='image'){
      const url=prompt('Bild URL:'); if(!url) return;
      el=document.createElement('img');
      el.src=url;
      el.className='';
    }else if(type==='section'){
      el=document.createElement('div');
      el.className='p-4 border';
      el.textContent='Bereich';
    }
    el.classList.add('block');
    makeEditable(el);
    editor.appendChild(el);
    updatePreview();
  }

  addText.onclick=()=>addWidget('text');
  addImage.onclick=()=>addWidget('image');
  addSection.onclick=()=>addWidget('section');

  editor.addEventListener('input',updatePreview);
  document.addEventListener('click',()=>select(null));
  bgInput.oninput=()=>{if(selected){selected.style.backgroundColor=bgInput.value; updatePreview();}}
  padInput.oninput=()=>{if(selected){selected.style.padding=padInput.value+'px'; updatePreview();}}

  new Sortable(editor,{animation:150});

  if(contentInput.value.trim()){
    editor.innerHTML=contentInput.value;
    editor.querySelectorAll('*').forEach(makeEditable);
  }
  updatePreview();

  document.getElementById('pageForm').addEventListener('submit',()=>{
    contentInput.value=editor.innerHTML;
  });
});
