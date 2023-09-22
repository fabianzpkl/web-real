<?php
add_theme_support('post-thumbnails');

function cargar_jquery()
{
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), null, true);
    wp_enqueue_script('materialize', '/assets/js/materialize.min.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'cargar_jquery');


function cargar_estilos()
{
    wp_enqueue_style('estilos-materialize', get_template_directory_uri() . '/assets/css/materialize.min.css');
    wp_enqueue_style('estilos-personalizados', get_template_directory_uri() . '/assets/css/main.css');
}
add_action('wp_enqueue_scripts', 'cargar_estilos');


register_nav_menus(array(
    'primary' => 'Menú principal',
));
