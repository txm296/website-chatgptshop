<?php
function load_settings(){
    $file = __DIR__.'/../data/settings.json';
    $defaults = [
        'primary_color' => '#2563eb',
        'secondary_color' => '#1e40af',
        'logo_text' => 'nezbi',
        'template' => 1,
        'font_family' => 'Inter',
        'hero_title' => 'Technologie neu erleben',
        'hero_subtitle' => 'Premium Elektronik f\u00fcr deinen Alltag',
        'hero_image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1280&q=80',
        'footer_text' => '\xc2\xa9 2025 nezbi',
        'background_color' => '#f9fafb',
        'custom_css' => '',
        'h1_size' => '2.25rem',
        'h2_size' => '1.875rem',
        'h3_size' => '1.5rem',
        'h4_size' => '1.25rem',
        'h5_size' => '1.125rem',
        'h6_size' => '1rem',
        'body_size' => '1rem',
        'base_spacing' => '1rem',
        'grid_width' => '1200px',
        'border_radius' => '0.5rem'
    ];
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if (is_array($data)) return array_merge($defaults, $data);
    }
    return $defaults;
}

function save_settings($settings){
    $file = __DIR__.'/../data/settings.json';
    if (!is_dir(dirname($file))) {
        mkdir(dirname($file), 0755, true);
    }
    file_put_contents($file, json_encode($settings, JSON_PRETTY_PRINT));
}
?>
