<div class="filter-hotels">
  <?php
  $paises = get_terms([
    'taxonomy'   => 'categoria_hoteles',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
  ]);
  ?>

  <!-- BOTONES (DESKTOP) -->
  <div class="filter-tags-hotels" role="tablist" aria-label="Filtrar hoteles por país">
    <button type="button" class="tag-hotel is-active btn" data-filter="all">Ver todos</button>

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
    <label class="sr-only" for="filterHotelsSelect">Filtrar hoteles por país</label>
    <select id="filterHotelsSelect" class="select-hotel btn">
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
// Random de posts (IDs) para respetar orden aleatorio
$ids = get_posts([
  'post_type'      => 'hoteles',
  'posts_per_page' => -1,
  'fields'         => 'ids',
  'no_found_rows'  => true,
  'cache_results'  => false,
]);

if (!empty($ids)) shuffle($ids);

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
      if (!$thumb) $thumb = get_template_directory_uri() . '/assets/images/default-hotel.jpg';

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
              <li><a target="_blank" rel="noopener noreferrer" href="tel:<?php echo esc_attr($telefono); ?>"><i class="fa-solid fa-phone"></i></a></li>
            <?php endif; ?>

            <?php if (!empty($facebook)): ?>
              <li><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($facebook); ?>"><i class="fa-brands fa-facebook-f"></i></a></li>
            <?php endif; ?>

            <?php if (!empty($instagram)): ?>
              <li><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($instagram); ?>"><i class="fa-brands fa-instagram"></i></a></li>
            <?php endif; ?>

            <?php if (!empty($twitter_x)): ?>
              <li><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($twitter_x); ?>"><i class="fa-brands fa-x-twitter"></i></a></li>
            <?php endif; ?>

            <?php if (!empty($ubicacion)): ?>
              <li><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($ubicacion); ?>"><i class="fa-solid fa-location-dot"></i></a></li>
            <?php endif; ?>
          </ul>

          <?php if (!empty($reserva)): ?>
            <a class="btn" href="<?php echo esc_url($reserva); ?>" target="_blank" rel="noopener noreferrer">Reservar ahora</a>
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
  const $wrap = $(".carrusel-hoteles");
  const $buttons = $(".filter-tags-hotels .tag-hotel");
  const $select = $("#filterHotelsSelect");

  if (!$wrap.length) return;

  // Init slick UNA vez
  if (!$wrap.hasClass("slick-initialized")) {
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

      // Flechas personalizadas (Font Awesome)
      prevArrow:
        '<button type="button" class="slick-arrow slick-prev custom-slick-arrow" aria-label="Anterior"><i class="fa-solid fa-chevron-left"></i></button>',
      nextArrow:
        '<button type="button" class="slick-arrow slick-next custom-slick-arrow" aria-label="Siguiente"><i class="fa-solid fa-chevron-right"></i></button>',

      responsive: [
        { breakpoint: 1024, settings: { slidesToShow: 2 } },
        { breakpoint: 768, settings: { slidesToShow: 1 } },
      ],
    });
  }

  function setActive(filter) {
    $buttons.removeClass("is-active");
    $buttons.filter(`[data-filter="${filter}"]`).addClass("is-active");
  }

  function applyFilter(filter) {
    // Quita filtros anteriores
    $wrap.slick("slickUnfilter");

    // Aplica filtro nuevo
    if (filter && filter !== "all") {
      $wrap.slick("slickFilter", "." + filter);
    }

    // ✅ SIEMPRE al primer slide (0)
    $wrap.slick("slickGoTo", 0, true);

    // Recalcula layout (clave con variableWidth)
    $wrap.slick("setPosition");
  }

  // Botones
  $buttons.on("click", function () {
    const filter = $(this).data("filter");
    setActive(filter);
    if ($select.length) $select.val(filter);
    applyFilter(filter);
  });

  // Select
  if ($select.length) {
    $select.on("change", function () {
      const filter = $(this).val();
      setActive(filter);
      applyFilter(filter);
    });
  }

  // Estado inicial
  setActive("all");
  applyFilter("all");
});


</script>

<style>
  /* Asegura que el carrusel sea referencia para posicionar las flechas */
.carrusel-hoteles {
  position: relative;
}

/* Flechas custom */
.carrusel-hoteles .custom-slick-arrow {
  position: absolute;
  top: 44%;
  transform: translateY(-50%);
  z-index: 5;

  width: 44px;
  height: 44px;

  display: flex; 
  align-items: center;
  justify-content: center;

  background: #333
  border: 0;
  cursor: pointer;

  transition: transform 0.15s ease, background 0.15s ease, opacity 0.15s ease;
}

.carrusel-hoteles .custom-slick-arrow i {
  font-size: 18px;
  line-height: 1;
  color: #fff;
}

/* Izquierda y derecha */
.carrusel-hoteles .slick-prev.custom-slick-arrow {
  left: 10px;
}

.carrusel-hoteles .slick-next.custom-slick-arrow {
  right: 10px;
}

/* Hover */
.carrusel-hoteles .custom-slick-arrow:hover {
  background: rgba(0, 0, 0, 0.75);
  transform: translateY(-50%) scale(1.06);
}

/* Disabled */
.carrusel-hoteles .custom-slick-arrow.slick-disabled {
  opacity: 0.35;
  cursor: not-allowed;
}

/* Mobile: un poquito más pequeñas */
@media (max-width: 768px) {
  .carrusel-hoteles .custom-slick-arrow {
    width: 40px;
    height: 40px;
  }
}

</style>
