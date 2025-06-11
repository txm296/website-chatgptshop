<div class="pb-countdown" data-date="2030-01-01T00:00:00">
  <span class="pb-countdown-display">00:00:00:00</span>
</div>
<style>
.pb-countdown{font-weight:bold;}
</style>
<script>(function(){
  function update(el){
    const target=new Date(el.dataset.date);
    const disp=el.querySelector('.pb-countdown-display');
    function tick(){
      let diff=(target-new Date())/1000;
      if(diff<0) diff=0;
      const d=Math.floor(diff/86400); diff%=86400;
      const h=Math.floor(diff/3600); diff%=3600;
      const m=Math.floor(diff/60); const s=Math.floor(diff%60);
      disp.textContent=`${String(d).padStart(2,'0')}:${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }
    tick();
    setInterval(tick,1000);
  }
  document.querySelectorAll('.pb-countdown').forEach(update);
})();</script>
