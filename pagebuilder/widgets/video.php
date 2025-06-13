<?php
ob_start();
$src = isset($src) ? trim($src) : 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
$preview = isset($preview) ? trim($preview) : '';
if (preg_match('~(youtube\.com/watch\?v=|youtu\.be/)~', $src)) {
    $type = 'youtube';
} elseif (strpos($src, 'vimeo.com') !== false) {
    $type = 'vimeo';
} else {
    $type = 'mp4';
}
?>
<div class="pb-video" data-type="<?= htmlspecialchars($type, ENT_QUOTES) ?>" data-src="<?= htmlspecialchars($src, ENT_QUOTES) ?>"<?php if ($preview): ?> data-preview="<?= htmlspecialchars($preview, ENT_QUOTES) ?>"<?php endif; ?>></div>
<style>
.pb-video{position:relative;max-width:100%;cursor:pointer;}
.pb-video-wrapper{position:relative;width:100%;padding-bottom:56.25%;height:0;overflow:hidden;}
.pb-video-wrapper iframe,.pb-video-wrapper video{position:absolute;top:0;left:0;width:100%;height:100%;}
.pb-video-preview{display:block;width:100%;height:auto;}
.pb-video-play{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(0,0,0,0.6);color:#fff;border:none;border-radius:50%;width:48px;height:48px;font-size:1.5rem;line-height:48px;text-align:center;}
</style>
<script>(function(){
  function init(el){
    var type=el.dataset.type||'youtube';
    var src=el.dataset.src||'';
    var preview=el.dataset.preview||'';
    function createPlayer(){
      var wrapper=document.createElement('div');
      wrapper.className='pb-video-wrapper';
      if(type==='youtube'){
        var id=src.split('v=')[1]||src.split('/').pop();
        var iframe=document.createElement('iframe');
        iframe.src='https://www.youtube.com/embed/'+id;
        iframe.setAttribute('frameborder','0');
        iframe.allowFullscreen=true;
        wrapper.appendChild(iframe);
      }else if(type==='vimeo'){
        var id=src.split('/').pop();
        var iframe=document.createElement('iframe');
        iframe.src='https://player.vimeo.com/video/'+id;
        iframe.setAttribute('frameborder','0');
        iframe.allowFullscreen=true;
        wrapper.appendChild(iframe);
      }else{
        var video=document.createElement('video');
        video.src=src;
        video.controls=true;
        wrapper.appendChild(video);
      }
      el.innerHTML='';
      el.appendChild(wrapper);
      el.classList.remove('pb-video-clickable');
    }
    if(preview){
      var img=document.createElement('img');
      img.src=preview;
      img.className='pb-video-preview';
      var btn=document.createElement('div');
      btn.className='pb-video-play';
      btn.textContent='▶';
      el.innerHTML='';
      el.appendChild(img);
      el.appendChild(btn);
      el.classList.add('pb-video-clickable');
      el.addEventListener('click',function(){createPlayer();},{once:true});
    }else{
      createPlayer();
    }
  }
  document.querySelectorAll('.pb-video').forEach(init);
})();</script>
<?php ob_end_flush(); ?>
