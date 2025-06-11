<?php
/**
 * Einfacher Modularer Page Builder
 *
 * Dieses Grundgerüst lädt Widgets, Layouts und Styles ohne weitere Abhängigkeiten.
 */

class ModularPageBuilder {
    public function loadWidgets($dir) {
        $widgets = [];
        foreach (glob(rtrim($dir, '/') . '/*.php') as $file) {
            $widgets[basename($file, '.php')] = $file;
        }
        return $widgets;
    }

    public function renderWidget($widget, $data = []) {
        if (!file_exists($widget)) {
            return '';
        }
        ob_start();
        extract($data, EXTR_SKIP);
        include $widget;
        return ob_get_clean();
    }
}
?>
