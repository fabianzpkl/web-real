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


/// MAPA ///

// CPT: Mapa
add_action('init', function () {
  register_post_type('mapa', [
    'labels' => [
      'name' => 'Mapa',
      'singular_name' => 'Mapa',
    ],
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'supports' => ['title'],
    'menu_icon' => 'dashicons-location-alt',
  ]);
});

// Shortcode: render mapa
add_shortcode('mapa_svg', function ($atts) {
  $atts = shortcode_atts(['id' => 0], $atts);

  $post_id = (int) $atts['id'];
  if (!$post_id) {
    $q = new WP_Query(['post_type' => 'mapa', 'posts_per_page' => 1, 'orderby' => 'date', 'order' => 'DESC']);
    if ($q->have_posts()) { $q->the_post(); $post_id = get_the_ID(); wp_reset_postdata(); }
  }
  if (!$post_id) return 'No hay mapa creado.';

  $svg_url  = get_post_meta($post_id, 'svg_url', true);
  $viewport = get_post_meta($post_id, 'viewport', true);
  $points   = get_post_meta($post_id, 'points', true);

  $viewport = $viewport ? json_decode($viewport, true) : ['x'=>0,'y'=>0,'scale'=>1];
  $points   = $points ? json_decode($points, true) : [];

  // Hoteles por país se consultan al click (AJAX/REST) o precargado por term_id.
  ob_start();
  ?>
  <div class="wp-map" data-map-id="<?php echo esc_attr($post_id); ?>"
       data-viewport='<?php echo esc_attr(wp_json_encode($viewport)); ?>'
       data-points='<?php echo esc_attr(wp_json_encode($points)); ?>'
       data-svg-url="<?php echo esc_url($svg_url); ?>">
    <div class="wp-map__stage"></div>
    <div class="wp-map__tooltip" style="display:none;"></div>
  </div>
  <?php
  return ob_get_clean();
});

// Enqueue scripts
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style('mapa-css', get_template_directory_uri() . '/assets/css/mapa.css', [], '1.0');
  wp_enqueue_script('mapa-front', get_template_directory_uri() . '/assets/js/mapa-front.js', ['jquery'], '1.0', true);

  wp_localize_script('mapa-front', 'MAPA_CFG', [
    'ajax'  => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('mapa_nonce'),
  ]);
});

// AJAX: traer hoteles por term (país)
add_action('wp_ajax_mapa_hoteles_por_pais', 'mapa_hoteles_por_pais');
add_action('wp_ajax_nopriv_mapa_hoteles_por_pais', 'mapa_hoteles_por_pais');
function mapa_hoteles_por_pais() {
  check_ajax_referer('mapa_nonce', 'nonce');

  $term_id = isset($_POST['term_id']) ? (int) $_POST['term_id'] : 0;
  if (!$term_id) wp_send_json_error('term_id inválido');

  $term = get_term($term_id, 'categoria_hoteles');
  if (!$term || is_wp_error($term)) wp_send_json_error('Term no existe');

  $q = new WP_Query([
    'post_type' => 'hoteles',
    'posts_per_page' => -1,
    'tax_query' => [[
      'taxonomy' => 'categoria_hoteles',
      'field' => 'term_id',
      'terms' => [$term_id],
    ]],
  ]);

  $hoteles = [];
  while ($q->have_posts()) {
    $q->the_post();
    $hoteles[] = [
      'title' => get_the_title(),
      'thumb' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
      'link'  => get_field('link_de_reserva'),
    ];
  }
  wp_reset_postdata();

  wp_send_json_success([
    'pais' => $term->name,
    'hoteles' => $hoteles,
  ]);
}
