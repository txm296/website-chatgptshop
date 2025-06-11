(function(){
  async function loadProductGrid(el){
    const params=new URLSearchParams();
    if(el.dataset.category) params.append('category', el.dataset.category);
    if(el.dataset.limit) params.append('limit', el.dataset.limit);
    const res=await fetch('/pagebuilder/api/product_grid.php?'+params.toString());
    if(res.ok){
      el.innerHTML=await res.text();
    }else{
      el.textContent='Fehler beim Laden';
    }
  }
  async function loadCategoryList(el){
    const params=new URLSearchParams();
    if(el.dataset.limit) params.append('limit', el.dataset.limit);
    const res=await fetch('/pagebuilder/api/category_list.php?'+params.toString());
    if(res.ok){
      el.innerHTML=await res.text();
    }else{
      el.textContent='Fehler beim Laden';
    }
  }
  document.addEventListener('DOMContentLoaded',function(){
    document.querySelectorAll('.pb-product-grid').forEach(loadProductGrid);
    document.querySelectorAll('.pb-category-list').forEach(loadCategoryList);
  });
})();
