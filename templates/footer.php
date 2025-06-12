<?php
?>
</main>
<?php
require_once __DIR__.'/../inc/settings.php';
$siteSettings = load_settings();
?>
<footer class="py-10 text-center text-gray-400 text-xs"><?= htmlspecialchars($siteSettings['footer_text']) ?></footer>
<script>var currentSlug = '<?= isset($currentSlug) ? htmlspecialchars($currentSlug) : '' ?>';</script>
<script src="/assets/dynamic-widgets.js"></script>
<script src="/assets/popups.js"></script>
</body>
</html>
