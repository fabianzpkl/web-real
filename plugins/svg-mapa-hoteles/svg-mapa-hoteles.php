<?php
/**
 * Plugin Name: SVG Mapa Hoteles
 * Description: Editor de mapa SVG con puntos anclados por coordenadas del SVG y relación a países (categoria_hoteles).
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) exit;

class SVG_Mapa_Hoteles {
  const CPT = 'mapa';
  const NONCE = 'svg_mapa_nonce';

  public function __construct() {
    add_action('init', [$this, 'register_cpt']);
    add_action('add_meta_boxes', [$this, 'add_metabox']);
    add_action('save_post', [$this, 'save_post']);

    add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
    add_action('wp_enqueue_scripts', [$this, 'front_assets']);

    add_shortcode('mapa_svg', [$this, 'shortcode']);

    add_action('wp_ajax_svg_mapa_hoteles', [$this, 'ajax_hoteles']);
    add_action('wp_ajax_nopriv_svg_mapa_hoteles', [$this, 'ajax_hoteles']);
  }

  public function register_cpt() {
    register_post_type(self::CPT, [
      'labels' => ['name' => 'Mapa', 'singular_name' => 'Mapa'],
      'public' => false,
      'show_ui' => true,
      'show_in_menu' => true,
      'supports' => ['title'],
      'menu_icon' => 'dashicons-location-alt',
    ]);
  }

  public function add_metabox() {
    add_meta_box(
      'svg_mapa_editor',
      'Editor del Mapa (SVG)',
      [$this, 'render_metabox'],
      self::CPT,
      'normal',
      'high'
    );
  }

  public function render_metabox($post) {
    wp_nonce_field(self::NONCE, self::NONCE);

    $svg_url  = get_post_meta($post->ID, 'svg_url', true);
    $viewport = get_post_meta($post->ID, 'viewport', true);
    $points   = get_post_meta($post->ID, 'points', true);

    $viewport = $viewport ? $viewport : wp_json_encode(['x'=>0,'y'=>0,'scale'=>1]);
    $points   = $points ? $points : wp_json_encode([]);

    // Países (terms)
    $terms = get_terms([
      'taxonomy' => 'categoria_hoteles',
      'hide_empty' => false,
      'parent' => 0
    ]);

    ?>
    <style>
      .svgmap-row{display:flex; gap:18px; align-items:flex-start; flex-wrap:wrap;}
      .svgmap-left{flex: 1 1 680px; min-width: 320px;}
      .svgmap-right{flex: 0 0 320px; width:320px;}
      .svgmap-stage{border:1px solid #d0d7de; border-radius:10px; background:#fff; height:520px; position:relative; overflow:hidden;}
      .svgmap-toolbar{display:flex; gap:10px; margin:10px 0;}
      .svgmap-toolbar button{padding:6px 10px;}
      .svgmap-help{font-size:12px; color:#57606a;}
      .svgmap-point-list{max-height:360px; overflow:auto; border:1px solid #d0d7de; border-radius:10px; padding:10px; background:#fff;}
      .svgmap-point-item{display:flex; gap:8px; align-items:center; justify-content:space-between; padding:6px 0; border-bottom:1px solid #f0f0f0;}
      .svgmap-point-item:last-child{border-bottom:none;}
      .svgmap-inline{display:flex; gap:8px; align-items:center;}
      .svgmap-inline select{max-width:180px;}
      .svgmap-badge{background:#ffdd57; border-radius:999px; padding:2px 8px; font-size:12px;}
    </style>

    <p>
      <label><strong>URL del SVG</strong> (ideal: un archivo .svg en tu media library)</label><br>
      <input type="url" name="svg_url" value="<?php echo esc_attr($svg_url); ?>" style="width:100%;" placeholder="https://tusitio.com/wp-content/uploads/mapa.svg">
      <span class="svgmap-help">Tip: tu SVG debe tener <code>viewBox</code> para que las coordenadas sean estables.</span>
    </p>

    <div class="svgmap-toolbar">
      <button type="button" class="button" id="svgmap-reset">Reset vista</button>
      <button type="button" class="button button-primary" id="svgmap-save">Guardar mapa</button>
      <span class="svgmap-help">Zoom: rueda • Pan: arrastrar • Punto: click</span>
    </div>

    <div class="svgmap-row">
      <div class="svgmap-left">
        <div class="svgmap-stage" id="svgmap-stage"
          data-viewport='<?php echo esc_attr($viewport); ?>'
          data-points='<?php echo esc_attr($points); ?>'>
        </div>
      </div>

      <div class="svgmap-right">
        <div class="svgmap-point-list" id="svgmap-point-list"></div>

        <p class="svgmap-help" style="margin-top:10px;">
          Shortcode: <code>[mapa_svg id="<?php echo (int)$post->ID; ?>"]</code>
        </p>

        <template id="svgmap-term-options">
          <?php if ($terms && !is_wp_error($terms)): ?>
            <?php foreach ($terms as $t): ?>
              <option value="<?php echo (int)$t->term_id; ?>"><?php echo esc_html($t->name); ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </template>

        <input type="hidden" name="viewport" id="svgmap-viewport" value="<?php echo esc_attr($viewport); ?>">
        <input type="hidden" name="points" id="svgmap-points" value="<?php echo esc_attr($points); ?>">
      </div>
    </div>

    <script>
      window.SVGMAP_ADMIN = {
        postId: <?php echo (int)$post->ID; ?>,
        nonce: "<?php echo esc_js(wp_create_nonce(self::NONCE)); ?>"
      };
    </script>
    <?php
  }

  public function save_post($post_id) {
    if (get_post_type($post_id) !== self::CPT) return;
    if (!isset($_POST[self::NONCE]) || !wp_verify_nonce($_POST[self::NONCE], self::NONCE)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['svg_url'])) update_post_meta($post_id, 'svg_url', esc_url_raw($_POST['svg_url']));
    if (isset($_POST['viewport'])) update_post_meta($post_id, 'viewport', wp_unslash($_POST['viewport']));
    if (isset($_POST['points'])) update_post_meta($post_id, 'points', wp_unslash($_POST['points']));
  }

  public function admin_assets($hook) {
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== self::CPT) return;

    wp_enqueue_script('svgmap-admin', plugin_dir_url(__FILE__) . 'svgmap-admin.js', [], '1.0.0', true);
  }

  public function front_assets() {
    wp_enqueue_style('svgmap-front', plugin_dir_url(__FILE__) . 'svgmap-front.css', [], '1.0.0');
    wp_enqueue_script('svgmap-front', plugin_dir_url(__FILE__) . 'svgmap-front.js', ['jquery'], '1.0.0', true);

    wp_localize_script('svgmap-front', 'SVGMAP_FRONT', [
      'ajax' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce(self::NONCE),
    ]);
  }

  public function shortcode($atts) {
    $atts = shortcode_atts(['id' => 0], $atts);
    $id = (int)$atts['id'];
    if (!$id) return 'Falta id del mapa.';

    $svg_url  = get_post_meta($id, 'svg_url', true);
    $viewport = get_post_meta($id, 'viewport', true);
    $points   = get_post_meta($id, 'points', true);

    if (!$svg_url) return 'Este mapa no tiene SVG asignado.';

    $viewport = $viewport ? $viewport : wp_json_encode(['x'=>0,'y'=>0,'scale'=>1]);
    $points   = $points ? $points : wp_json_encode([]);

    ob_start(); ?>
      <div class="svgmap"
        data-map-id="<?php echo esc_attr($id); ?>"
        data-svg-url="<?php echo esc_url($svg_url); ?>"
        data-viewport='<?php echo esc_attr($viewport); ?>'
        data-points='<?php echo esc_attr($points); ?>'>
        <div class="svgmap__stage"></div>
        <div class="svgmap__tooltip" style="display:none;"></div>
      </div>
    <?php return ob_get_clean();
  }

  public function ajax_hoteles() {
    check_ajax_referer(self::NONCE, 'nonce');

    $term_id = isset($_POST['term_id']) ? (int)$_POST['term_id'] : 0;
    if (!$term_id) wp_send_json_error('term_id inválido');

    $term = get_term($term_id, 'categoria_hoteles');
    if (!$term || is_wp_error($term)) wp_send_json_error('País no válido');

    $q = new WP_Query([
      'post_type' => 'hoteles',
      'posts_per_page' => -1,
      'tax_query' => [[
        'taxonomy' => 'categoria_hoteles',
        'field' => 'term_id',
        'terms' => [$term_id]
      ]]
    ]);

    $items = [];
    while ($q->have_posts()) {
      $q->the_post();
      $items[] = [
        'title' => get_the_title(),
        'thumb' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
        'link'  => get_field('link_de_reserva'),
      ];
    }
    wp_reset_postdata();

    wp_send_json_success([
      'pais' => $term->name,
      'hoteles' => $items
    ]);
  }
}

new SVG_Mapa_Hoteles();
