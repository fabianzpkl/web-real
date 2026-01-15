<div class="filter-hotels">
  <?php
  // Traer términos (países) de la taxonomía categoria_hoteles
  $paises = get_terms([
    'taxonomy'   => 'categoria_hoteles',
    'hide_empty' => true, // solo los que tienen hoteles
    'orderby'    => 'name',
    'order'      => 'ASC',
  ]);
  ?>

  <div class="filter-tags-hotels" role="tablist" aria-label="Filtrar hoteles por país">
    <button type="button" class="tag-hotel is-active" data-filter="all">
      Ver todos
    </button>

    <?php if (!empty($paises) && !is_wp_error($paises)): ?>
      <?php foreach ($paises as $pais): ?>
        <button
          type="button"
          class="tag-hotel"
          data-filter="categoria-<?php echo (int) $pais->term_id; ?>"
        >
          <?php echo esc_html($pais->name); ?>
        </button>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php
// 1) Trae SOLO IDs (rápido)
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
  'orderby'        => 'post__in', // respeta el orden del array (ya random)
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
                <a target="_blank" rel="noopener noreferrer" href="tel:<?php echo esc_attr($telefono); ?>">
                  <i class="fa-solid fa-phone"></i>
                </a>
              </li>
            <?php endif; ?>

            <?php if (!empty($facebook)): ?>
              <li>
                <a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($facebook); ?>">
                  <i class="fa-brands fa-facebook-f"></i>
                </a>
              </li>
            <?php endif; ?>

            <?php if (!empty($instagram)): ?>
              <li>
                <a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($instagram); ?>">
                  <i class="fa-brands fa-instagram"></i>
                </a>
              </li>
            <?php endif; ?>

            <?php if (!empty($twitter_x)): ?>
              <li>
                <a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($twitter_x); ?>">
                  <i class="fa-brands fa-x-twitter"></i>
                </a>
              </li>
            <?php endif; ?>

            <?php if (!empty($ubicacion)): ?>
              <li>
                <a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($ubicacion); ?>">
                  <i class="fa-solid fa-location-dot"></i>
                </a>
              </li>
            <?php endif; ?>
          </ul>

          <?php if (!empty($reserva)): ?>
            <a class="btn" href="<?php echo esc_url($reserva); ?>" target="_blank" rel="noopener noreferrer">
              Reservar Ahora
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
document.addEventListener('DOMContentLoaded', function () {
  const wrap = document.querySelector('.carrusel-hoteles');
  const buttons = document.querySelectorAll('.filter-tags-hotels .tag-hotel');

  if (!wrap || !buttons.length) return;

  const cards = wrap.querySelectorAll('.card-hotel');

  function setActive(btn) {
    buttons.forEach(b => b.classList.remove('is-active'));
    btn.classList.add('is-active');
  }

  function filterCards(filter) {
    cards.forEach(card => {
      if (filter === 'all') {
        card.style.display = '';
        return;
      }
      card.style.display = card.classList.contains(filter) ? '' : 'none';
    });
  }

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const filter = btn.getAttribute('data-filter');
      setActive(btn);
      filterCards(filter);
    });
  });
});
</script>

