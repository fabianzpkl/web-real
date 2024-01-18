<?php
//Slider TP2B shortcode
function slider_tp2b_shortcode()
{
    ob_start(); // Inicia el almacenamiento en búfer de salida
    include get_template_directory() . '/includes/slide-tp2b.php'; // Ruta al archivo de tu template personalizado
    return ob_get_clean(); // Devuelve el contenido del búfer y lo limpia
}
add_shortcode('slider_tp2b', 'slider_tp2b_shortcode');

//Hoteles shortcode
function carrusel_hoteles_shortcode()
{
    ob_start(); // Inicia el almacenamiento en búfer de salida
    include get_template_directory() . '/includes/carrusel-hoteles.php'; // Ruta al archivo de tu template personalizado
    return ob_get_clean(); // Devuelve el contenido del búfer y lo limpia
}
add_shortcode('carrusel_hoteles', 'carrusel_hoteles_shortcode');

//Post Type Hoteles//
function registrar_tipo_hoteles()
{
    $args = array(
        'public' => false,
        // No se mostrará en la página principal
        'publicly_queryable' => false,
        // No será consultable públicamente
        'show_ui' => true,
        // Mostrar en el área de administración
        'show_in_menu' => true,
        // Mostrar en el menú de administración
        'has_archive' => false,
        // No tendrá una página de archivo
        'supports' => array('title', 'thumbnail'),
        // Solo tendrá título
        'labels' => array(
            'name' => 'Hoteles',
            'singular_name' => 'Hotel',
        ),
        'menu_icon' => 'dashicons-building',
        // Icono para el menú de administración
    );
    register_post_type('hoteles', $args);
}
add_action('init', 'registrar_tipo_hoteles');

// Registra una taxonomía personalizada llamada "categoria_hoteles"
function registrar_taxonomia_categoria_hoteles()
{
    register_taxonomy(
        'categoria_hoteles',
        'hoteles',
        array(
            'label' => 'Países de los hoteles',
            'hierarchical' => true,
        )
    );
}
add_action('init', 'registrar_taxonomia_categoria_hoteles');


// Selector Hoteles
function mostrar_select_categorias_hoteles()
{
    $terms = get_terms(
        array(
            'taxonomy' => 'categoria_hoteles',
            'hide_empty' => false,
            'parent' => 0,
        )
    );

    if (!empty($terms)) {
        echo '<select id="filtro_categorias">';
        echo '<option value="todos" selected>Todos los hoteles</option>';
        foreach ($terms as $term) {
            echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
            // Llama a una función recursiva para mostrar las categorías secundarias
            mostrar_categorias_hijas($term->term_id, 1);
        }
        echo '</select>';
    } else {
        echo 'No se encontraron categorías.';
    }
}

// Función recursiva para mostrar las categorías secundarias
function mostrar_categorias_hijas($parent_id, $depth)
{
    $child_terms = get_terms(
        array(
            'taxonomy' => 'categoria_hoteles',
            'hide_empty' => false,
            'parent' => $parent_id,
        )
    );

    foreach ($child_terms as $child_term) {
        echo '<option value="' . $child_term->term_id . '">';
        // Agrega un nivel de indentación para las categorías secundarias
        echo str_repeat('&nbsp;&nbsp;', $depth) . $child_term->name;
        echo '</option>';
        mostrar_categorias_hijas($child_term->term_id, $depth + 1);
    }
}

add_shortcode('select_categorias_hoteles', 'mostrar_select_categorias_hoteles');


//Post Type TP2B//
function registrar_tipo_tp2b()
{
    $args = array(
        'public' => true,
        // No se mostrará en la página principal
        'publicly_queryable' => true,
        // No será consultable públicamente
        'show_ui' => true,
        // Mostrar en el área de administración
        'show_in_menu' => true,
        // Mostrar en el menú de administración
        'has_archive' => false,
        // No tendrá una página de archivo
        'supports' => array('title'),
        // Solo tendrá título
        'labels' => array(
            'name' => 'TP2B',
            'singular_name' => 'tp2b',
        ),
        'menu_icon' => 'dashicons-buddicons-activity',
        // Icono para el menú de administración
    );
    register_post_type('tp2b', $args);
}
add_action('init', 'registrar_tipo_tp2b');

// Registra una taxonomía personalizada llamada "categoria_tp2b"
function registrar_taxonomia_categoria_tp2b()
{
    register_taxonomy(
        'categoria_tp2b',
        'tp2b',
        array(
            'label' => 'Categoría TP2B',
            'hierarchical' => true,
        )
    );
}
add_action('init', 'registrar_taxonomia_categoria_tp2b');


// thumbnails Posts

add_theme_support('post-thumbnails');

function cargar_jquery()
{
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), null, true);
    wp_enqueue_script('materialize', get_template_directory_uri() . '/assets/js/materialize.min.js', array(), null, true);
    wp_enqueue_script('icons', 'https://kit.fontawesome.com/d70fe1760d.js', array(), null, true);
    wp_enqueue_script('slick', 'https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js', array(), null, true);
    wp_enqueue_script('main', get_template_directory_uri() . '/assets/js/main.min.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'cargar_jquery');


function cargar_estilos()
{
    wp_enqueue_style('estilos-materialize', get_template_directory_uri() . '/assets/css/materialize.min.css');
    wp_enqueue_style('estilos-personalizados', get_template_directory_uri() . '/assets/css/main.css');
}
add_action('wp_enqueue_scripts', 'cargar_estilos');


register_nav_menus(
    array(
        'primary' => 'Menú principal',
    )
);
