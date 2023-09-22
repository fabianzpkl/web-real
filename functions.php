<?php
// Habilitar soporte para imágenes destacadas
add_theme_support('post-thumbnails');

function cargar_jquery()
{
    wp_deregister_script('jquery'); // Desregistrar la versión incluida con WordPress
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), null, true); // Cargar la última versión de jQuery desde CDN
}
add_action('wp_enqueue_scripts', 'cargar_jquery');


function cargar_estilos()
{
    // Enlazar una hoja de estilo personalizada
    wp_enqueue_style('estilos-personalizados', get_template_directory_uri() . 'assets/css/main.css');
    // Enlazar una hoja de estilo de Bootstrap desde una CDN
    wp_enqueue_style('estilos-personalizados', get_template_directory_uri() . 'css/main.css');
}
add_action('wp_enqueue_scripts', 'cargar_estilos');

// Registrar el menú de navegación
register_nav_menus(array(
    'primary' => 'Menú principal',
));

// Ejemplo de una función personalizada
function mi_funcion_personalizada()
{
    // Código de la función aquí
}
