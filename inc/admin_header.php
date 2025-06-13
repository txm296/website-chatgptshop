<?php
function admin_header(string $active = '') {
    require_once __DIR__ . '/settings.php';
    $siteSettings = load_settings();
    ?>
<header class="bg-white border-b shadow-sm">
    <div class="max-w-5xl mx-auto flex justify-between items-center py-6 px-4">
        <span class="text-2xl font-extrabold tracking-tight">nezbi Admin</span>
        <div class="flex items-center">
            <button id="menuBtn" class="md:hidden mr-4 text-gray-600" aria-label="Menü öffnen">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <a href="logout.php" class="inline-block rounded-xl px-4 py-2 accent-bg text-white font-medium hover:opacity-90 transition">Logout</a>
        </div>
    </div>
    <nav id="navLinks" class="hidden flex-col space-y-2 px-4 pb-4 md:flex md:flex-row md:space-y-0 md:space-x-8 md:max-w-5xl md:mx-auto">
        <a href="dashboard.php" class="<?php echo $active==='dashboard'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Dashboard</a>
        <details class="group md:relative">
            <summary class="cursor-pointer list-none flex items-center <?php echo in_array($active,['produkte','kategorien','rabatte'])?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Shop</summary>
            <div class="pl-4 space-y-1 md:absolute md:left-0 md:top-full md:bg-white md:border md:rounded md:shadow md:p-2 md:w-40 md:pl-0">
                <a href="produkte.php" class="block <?php echo $active==='produkte'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Produkte</a>
                <a href="kategorien.php" class="block <?php echo $active==='kategorien'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Kategorien</a>
                <a href="rabattcodes.php" class="block <?php echo $active==='rabatte'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Rabatte</a>
            </div>
        </details>
        <details class="group md:relative">
            <summary class="cursor-pointer list-none flex items-center <?php echo in_array($active,['seiten','builder','templates'])?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Inhalte</summary>
            <div class="pl-4 space-y-1 md:absolute md:left-0 md:top-full md:bg-white md:border md:rounded md:shadow md:p-2 md:w-40 md:pl-0">
                <a href="pages.php" class="block <?php echo $active==='seiten'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Seiten</a>
                <a href="live_builder.php" class="block <?php echo $active==='builder'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Builder</a>
                <a href="templates.php" class="block <?php echo $active==='templates'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Templates</a>
            </div>
        </details>
        <details class="group md:relative">
            <summary class="cursor-pointer list-none flex items-center <?php echo in_array($active,['bestellungen','insights'])?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Auswertung</summary>
            <div class="pl-4 space-y-1 md:absolute md:left-0 md:top-full md:bg-white md:border md:rounded md:shadow md:p-2 md:w-40 md:pl-0">
                <a href="bestellungen.php" class="block <?php echo $active==='bestellungen'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Bestellungen</a>
                <a href="insights.php" class="block <?php echo $active==='insights'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Insights</a>
            </div>
        </details>
        <a href="popup_builder.php" class="md:ml-auto <?php echo $active==='popups'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Popups</a>
        <a href="design.php" class="<?php echo $active==='design'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Design</a>
        <a href="customize.php" class="<?php echo $active==='customize'?'font-bold text-blue-600':'hover:text-blue-600'; ?>">Website bearbeiten</a>
    </nav>
</header>
<script>
    document.addEventListener('DOMContentLoaded',function(){
        var b=document.getElementById('menuBtn');
        var n=document.getElementById('navLinks');
        if(b&&n){
            b.addEventListener('click',function(){n.classList.toggle('hidden');});
        }
    });
</script>
<?php
}
?>
