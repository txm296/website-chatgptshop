document.addEventListener('DOMContentLoaded',()=>{
  const builder=document.getElementById('builder');
  const addBtn=document.getElementById('addBtn');
  const addSelect=document.getElementById('addType');
  const form=document.getElementById('pageForm');
  const contentInput=document.getElementById('contentInput');

  if(addBtn&&addSelect&&builder){
    addBtn.addEventListener('click',()=>{
      const type=addSelect.value;
      let el;
      switch(type){
        case 'h2':
          el=document.createElement('h2');
          el.textContent='Überschrift';
          break;
        case 'p':
          el=document.createElement('p');
          el.textContent='Text';
          break;
        case 'img':
          el=document.createElement('img');
          const url=prompt('Bild URL');
          if(url) el.src=url;
          break;
        case 'button':
          el=document.createElement('a');
          const btnText=prompt('Button Text','Button');
          const link=prompt('Link URL (z.B. https://example.com oder /seite)','');
          if(link) el.href=link; else el.href='#';
          el.className='px-4 py-2 rounded bg-blue-600 text-white inline-block';
          el.textContent=btnText||'Button';
          break;
        default:
          el=document.createElement('p');
          el.textContent='Text';
      }
      el.contentEditable=type!=='img';
      el.classList.add('editable');
      const wrapper=document.createElement('div');
      wrapper.className='block relative group mb-2';
      const del=document.createElement('button');
      del.type='button';
      del.textContent='✖';
      del.className='absolute -right-2 -top-2 hidden group-hover:block bg-red-600 text-white rounded-full w-5 h-5 text-xs';
      del.addEventListener('click',()=>wrapper.remove());
      wrapper.appendChild(el);
      wrapper.appendChild(del);
      builder.appendChild(wrapper);
    });
  }

  if(builder){
    builder.addEventListener('dblclick',e=>{
      if(e.target.tagName==='A' && e.target.classList.contains('editable')){
        const current=e.target.getAttribute('href')||'';
        const newUrl=prompt('Link URL (z.B. https://example.com oder /seite)',current);
        if(newUrl!==null) e.target.setAttribute('href',newUrl);
      }
    });
  }

  if(form&&contentInput&&builder){
    form.addEventListener('submit',()=>{
      const clones=builder.cloneNode(true);
      clones.querySelectorAll('button').forEach(b=>b.remove());
      contentInput.value=clones.innerHTML;
    });
  }
});
