document.addEventListener('DOMContentLoaded',function(){
  const btn=document.getElementById('themeToggle');
  const apply=()=>{
    const dark=localStorage.getItem('darkMode')==='1';
    document.body.classList.toggle('dark', dark);
    if(btn) btn.textContent = dark ? '\u2600\ufe0f' : '\u{1F319}';
  };
  apply();
  if(btn){
    btn.addEventListener('click',function(){
      const now=!document.body.classList.contains('dark');
      document.body.classList.toggle('dark', now);
      localStorage.setItem('darkMode', now?'1':'0');
      apply();
    });
  }
});
