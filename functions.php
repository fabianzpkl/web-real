<?php

// -----------------------------
// Shortcodes
// -----------------------------
function slider_tp2b_shortcode() {
  ob_start();
  include get_template_directory() . '/includes/slide-tp2b.php';
  return ob_get_clean();
}
add_shortcode('slider_tp2b', 'slider_tp2b_shortcode');

function carrusel_hoteles_shortcode() {
  ob_start();
  include get_template_directory() . '/includes/carrusel-hoteles.php';
  return ob_get_clean();
}
add_shortcode('carrusel_hoteles', 'carrusel_hoteles_shortcode');


// -----------------------------
// CPT: Hoteles + Taxonomía
// -----------------------------
function registrar_tipo_hoteles() {
  $args = array(
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'has_archive' => false,
    'supports' => array('title', 'thumbnail'),
    'labels' => array(
      'name' => 'Hoteles',
      'singular_name' => 'Hotel',
    ),
    'menu_icon' => 'dashicons-building',
  );
  register_post_type('hoteles', $args);
}
add_action('init', 'registrar_tipo_hoteles');

function registrar_taxonomia_categoria_hoteles() {
  register_taxonomy(
    'categoria_hoteles',
    'hoteles',
    array(
      'label' => 'Países de los hoteles',
      'hierarchical' => true,
      'show_ui' => true,
      'show_admin_column' => true,
    )
  );
}
add_action('init', 'registrar_taxonomia_categoria_hoteles');


// -----------------------------
// Shortcode: Select categorías hoteles
// -----------------------------
function mostrar_categorias_hijas($parent_id, $depth) {
  $child_terms = get_terms(array(
    'taxonomy' => 'categoria_hoteles',
    'hide_empty' => false,
    'parent' => $parent_id,
  ));

  if (empty($child_terms) || is_wp_error($child_terms)) return;

  foreach ($child_terms as $child_term) {
    echo '<option value="' . esc_attr($child_term->term_id) . '">';
    echo str_repeat('&nbsp;&nbsp;', (int)$depth) . esc_html($child_term->name);
    echo '</option>';

    mostrar_categorias_hijas($child_term->term_id, $depth + 1);
  }
}

function mostrar_select_categorias_hoteles() {
  $terms = get_terms(array(
    'taxonomy' => 'categoria_hoteles',
    'hide_empty' => false,
    'parent' => 0,
  ));

  if (empty($terms) || is_wp_error($terms)) {
    return 'No se encontraron categorías.';
  }

  ob_start();
  echo '<select id="filtro_categorias">';
  echo '<option value="todos" selected>Todos los hoteles</option>';

  foreach ($terms as $term) {
    echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
    mostrar_categorias_hijas($term->term_id, 1);
  }

  echo '</select>';
  return ob_get_clean();
}
add_shortcode('select_categorias_hoteles', 'mostrar_select_categorias_hoteles');


// -----------------------------
// CPT: TP2B + Taxonomía
// -----------------------------
function registrar_tipo_tp2b() {
  $args = array(
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'has_archive' => false,
    'supports' => array('title', 'thumbnail'),
    'labels' => array(
      'name' => 'TP2B',
      'singular_name' => 'tp2b',
    ),
    'menu_icon' => 'dashicons-buddicons-activity',
  );
  register_post_type('tp2b', $args);
}
add_action('init', 'registrar_tipo_tp2b');

function registrar_taxonomia_categoria_tp2b() {
  register_taxonomy(
    'categoria_tp2b',
    'tp2b',
    array(
      'label' => 'Categoría TP2B',
      'hierarchical' => true,
      'show_ui' => true,
      'show_admin_column' => true,
    )
  );
}
add_action('init', 'registrar_taxonomia_categoria_tp2b');


// -----------------------------
// Theme supports + Menús
// -----------------------------
add_theme_support('post-thumbnails');

register_nav_menus(array(
  'primary' => 'Menú principal',
));


// -----------------------------
// SVG upload
// -----------------------------
add_filter('upload_mimes', function ($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
});

add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
  $filetype = wp_check_filetype($filename, $mimes);

  return array(
    'ext'             => $filetype['ext'],
    'type'            => $filetype['type'],
    'proper_filename' => $data['proper_filename'],
  );
}, 10, 4);


// -----------------------------
// Assets (Scripts + Styles)  ✅ UNA SOLA VEZ, ORDEN CORRECTO
// -----------------------------
add_action('wp_enqueue_scripts', function () {

  // 1) jQuery: usa el que trae WordPress (NO lo desregistres)
  wp_enqueue_script('jquery');

  // 2) CSS
  wp_enqueue_style('estilos-materialize', get_template_directory_uri() . '/assets/css/materialize.min.css', array(), null);
  wp_enqueue_style('estilos-personalizados', get_template_directory_uri() . '/assets/css/main.css', array(), null);

  // Slick CSS (si lo quieres)
  // Si NO tienes estas rutas, cámbialas por tu ruta real o usa CDN
  wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css', array(), '1.8.1');
  wp_enqueue_style('slick-theme-css', 'https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css', array('slick-css'), '1.8.1');

  // 3) JS (en footer)
  wp_enqueue_script('materialize', get_template_directory_uri() . '/assets/js/materialize.min.js', array('jquery'), null, true);

  // Fontawesome kit (no depende de jQuery)
  wp_enqueue_script('icons', 'https://kit.fontawesome.com/d70fe1760d.js', array(), null, true);

  // Slick JS (depende de jQuery)
  wp_enqueue_script('slick', 'https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);

  // Tu main (depende de jquery + slick)
  // IMPORTANTE: usa UNO SOLO (no main.min.js y main.js a la vez)
  wp_enqueue_script('main', get_template_directory_uri() . '/assets/js/main.min.js', array('jquery', 'slick'), null, true);
});


add_action('widgets_init', function () {

  // Footer columnas
  register_sidebar([
    'name'          => 'Footer Columna 1',
    'id'            => 'footer_col_1',
    'description'   => 'Primera columna del footer (título y enlaces).',
    'before_widget' => '<div class="footer-widget footer-widget-1">',
    'after_widget'  => '</div>',
    'before_title'  => '<h6>',
    'after_title'   => '</h6>',
  ]);

  register_sidebar([
    'name'          => 'Footer Columna 2',
    'id'            => 'footer_col_2',
    'description'   => 'Segunda columna del footer (título y enlaces).',
    'before_widget' => '<div class="footer-widget footer-widget-2">',
    'after_widget'  => '</div>',
    'before_title'  => '<h6>',
    'after_title'   => '</h6>',
  ]);

  register_sidebar([
    'name'          => 'Footer Columna 3',
    'id'            => 'footer_col_3',
    'description'   => 'Tercera columna del footer (título y enlaces).',
    'before_widget' => '<div class="footer-widget footer-widget-3">',
    'after_widget'  => '</div>',
    'before_title'  => '<h6>',
    'after_title'   => '</h6>',
  ]);

  register_sidebar([
    'name'          => 'Footer Columna 4',
    'id'            => 'footer_col_4',
    'description'   => 'Cuarta columna del footer (título y enlaces).',
    'before_widget' => '<div class="footer-widget footer-widget-4">',
    'after_widget'  => '</div>',
    'before_title'  => '<h6>',
    'after_title'   => '</h6>',
  ]);

  // Footer legales
  register_sidebar([
    'name'          => 'Footer Legales',
    'id'            => 'footer_legales',
    'description'   => 'Texto legal y notas al pie del footer.',
    'before_widget' => '<div class="footer-legales">',
    'after_widget'  => '</div>',
    'before_title'  => '<h6 class="sr-only">',
    'after_title'   => '</h6>',
  ]);
});
