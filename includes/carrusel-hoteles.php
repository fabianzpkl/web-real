<div class="filter-hotels">
  <?php
  // Traer términos (países) de la taxonomía categoria_hoteles
  $paises = get_terms([
    'taxonomy'   => 'categoria_hoteles',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
  ]);
  ?>

  <!-- BOTONES (DESKTOP) -->
  <div class="filter-tags-hotels" role="tablist" aria-label="Filtrar hoteles por país">
    <button type="button" class="tag-hotel is-active btn" data-filter="all">
      Ver todos
    </button>

    <?php if (!empty($paises) && !is_wp_error($paises)): ?>
      <?php foreach ($paises as $pais): ?>
        <button
          type="button"
          class="tag-hotel btn"
          data-filter="categoria-<?php echo (int) $pais->term_id; ?>"
        >
          <?php echo esc_html($pais->name); ?>
        </button>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- SELECT (MOBILE) -->
  <div class="filter-select-hotels">
    <select id="filterHotelsSelect" class="filter-hotels btn">
      <option value="all">Ver todos</option>

      <?php if (!empty($paises) && !is_wp_error($paises)): ?>
        <?php foreach ($paises as $pais): ?>
          <option value="categoria-<?php echo (int) $pais->term_id; ?>">
            <?php echo esc_html($pais->name); ?>
          </option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </div>
</div>

<?php
// 1) Traer SOLO IDs (más rápido)
$ids = get_posts([
  'post_type'      => 'hoteles',
  'posts_per_page' => -1,
  'fields'         => 'ids',
  'no_found_rows'  => true,
  'cache_results'  => false,
]);

// 2) Random en PHP
if (!empty($ids)) {
  shuffle($ids);
}

$args = [
  'post_type'      => 'hoteles',
  'posts_per_page' => -1,
  'post__in'       => $ids,
  'orderby'        => 'post__in',
];

$custom_query = new WP_Query($args);
?>

<div class="carrusel-hoteles">
  <?php if ($custom_query->have_posts()): ?>
    <?php while ($custom_query->have_posts()): $custom_query->the_post(); ?>

      <?php
      $terms = get_the_terms(get_the_ID(), 'categoria_hoteles');

      $class_list = [];
      $term_names = [];

      if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $t) {
          $class_list[] = 'categoria-' . (int) $t->term_id;
          $term_names[] = trim($t->name);
        }
      }

      $clase_categoria = implode(' ', $class_list);

      $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium');
      if (!$thumb) {
        $thumb = get_template_directory_uri() . '/assets/images/default-hotel.jpg';
      }

      // Campos ACF
      $telefono   = get_field('telefono');
      $facebook   = get_field('facebook');
      $instagram  = get_field('instagram');
      $twitter_x  = get_field('twitter_x');
      $ubicacion  = get_field('ubicacion');
      $reserva    = get_field('link_de_reserva');
      ?>

      <div class="card-hotel <?php echo esc_attr($clase_categoria); ?>">
        <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">

        <div class="cont-card-hotel">
          <label><?php echo !empty($term_names) ? esc_html(implode(', ', $term_names)) : ''; ?></label>

          <h6><?php the_title(); ?></h6>

          <ul>
            <?php if (!empty($telefono)): ?>
              <li>
                <a href="tel:<?php echo esc_attr($telefono); ?>" target="_blank" rel="noopener noreferrer">
                  <i class="fa-solid fa-phone"></i>
                </a>
              </li>
            <?php endif; ?>

            <?php if (!empty($facebook)): ?>
              <li>
                <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer">
                  <i class="fa-brands fa-facebook-f"></i>
                </a>
              </li>
            <?php endif; ?>

            <?php if (!empty($instagram)): ?>
              <li>
                <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer">
                  <i class="fa-brands fa-instagram"></i>
                </a>
              </li>
            <?php endif; ?>

            <?php if (!empty($twitter_x)): ?>
              <li>
                <a href="<?php echo esc_url($twitter_x); ?>" target="_blank" rel="noopener noreferrer">
                  <i class="fa-brands fa-x-twitter"></i>
                </a>
              </li>
            <?php endif; ?>

            <?php if (!empty($ubicacion)): ?>
              <li>
                <a href="<?php echo esc_url($ubicacion); ?>" target="_blank" rel="noopener noreferrer">
                  <i class="fa-solid fa-location-dot"></i>
                </a>
              </li>
            <?php endif; ?>
          </ul>

          <?php if (!empty($reserva)): ?>
            <a class="btn" href="<?php echo esc_url($reserva); ?>" target="_blank" rel="noopener noreferrer">
              Reservar ahora
            </a>
          <?php endif; ?>
        </div>
      </div>

    <?php endwhile; wp_reset_postdata(); ?>
  <?php else: ?>
    No se encontraron hoteles.
  <?php endif; ?>
</div>


<script>
jQuery(function ($) {
  const $wrap = $('.carrusel-hoteles');
  const $source = $('.hoteles-source');
  const $buttons = $('.filter-tags-hotels .tag-hotel');
  const $select = $('#filterHotelsSelect');

  if (!$wrap.length || !$source.length) return;

  function destroySlick() {
    if ($wrap.hasClass('slick-initialized')) {
      $wrap.slick('unslick');
    }
  }

  function initSlick() {
    // Usa tu función global del main.js
    if (typeof window.initHotelsSlick === 'function') {
      window.initHotelsSlick($wrap);
      $wrap.slick('setPosition');
    } else if (typeof initHotelsSlick === 'function') {
      initHotelsSlick($wrap);
      $wrap.slick('setPosition');
    } else {
      // fallback (por si no existiera)
      $wrap.slick({
        arrows: true,
        dots: false,
        infinite: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        pauseOnFocus: true,
        pauseOnHover: true,
        variableWidth: true,
        swipe: true,
        responsive: [
          { breakpoint: 1024, settings: { slidesToShow: 2 } },
          { breakpoint: 768, settings: { slidesToShow: 1 } }
        ]
      });
    }
  }

  function setActiveByFilter(filter) {
    $buttons.removeClass('is-active');
    $buttons.filter(`[data-filter="${filter}"]`).addClass('is-active');
  }

  function build(filter) {
    // 1) destruir slick
    destroySlick();

    // 2) vaciar carrusel visible
    $wrap.empty();

    // 3) clonar desde fuente (siempre limpio)
    const $all = $source.find('.card-hotel').clone(true, true);

    // 4) filtrar (remover del DOM, NO ocultar)
    const $filtered = (filter === 'all')
      ? $all
      : $all.filter('.' + filter);

    // 5) pintar
    $wrap.append($filtered);

    // 6) reiniciar slick
    initSlick();
  }

  // Inicial
  build('all');

  // Botones
  $buttons.on('click', function () {
    const filter = $(this).data('filter');
    setActiveByFilter(filter);
    if ($select.length) $select.val(filter);
    build(filter);
  });

  // Select
  if ($select.length) {
    $select.on('change', function () {
      const filter = $(this).val();
      setActiveByFilter(filter);
      build(filter);
    });
  }
});
</script>

