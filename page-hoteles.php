<?php
/*
Template Name: Hoteles
*/
?>

<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

  <div class="thumbnail_page" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
    <div class="container center-align">
      <div class="cont_title_page">
        <h1><?php the_title(); ?></h1>
        <p><?php the_field("texto_introductorio"); ?></p>
      </div>
    </div>
    <div class="overlay"></div>
  </div>

  <section class="cont_page">

    <?php the_content(); ?>

    <?php
    // ====== SELECT PAISES (TAXONOMÍA) ======
    $paises = get_terms([
      'taxonomy'   => 'categoria_hoteles',
      'hide_empty' => true,
      'orderby'    => 'name',
      'order'      => 'ASC',
    ]);

    // ====== QUERY HOTELES ======
    $args = [
      'post_type'      => 'hoteles',
      'posts_per_page' => -1,
      'orderby'        => 'title',
      'order'          => 'ASC',
    ];
    $custom_query = new WP_Query($args);
    ?>

    <!-- TOP BAR: SELECT izquierda + contador derecha -->
    <div class="hotels-topbar">
      <div class="hotels-filter">
        <label class="sr-only" for="filterHotelsSelect">Filtrar hoteles por país</label>
        <select id="filterHotelsSelect" class="select-hotel btn">
          <option value="all">Ver todos</option>

          <?php if (!empty($paises) && !is_wp_error($paises)) : ?>
            <?php foreach ($paises as $pais) : ?>
              <option value="categoria-<?php echo (int) $pais->term_id; ?>">
                <?php echo esc_html($pais->name); ?>
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>

      <div class="hotels-count">
        Mostrando <strong id="visibleHotelsCount">0</strong> hoteles
      </div>
    </div>

    <!-- LISTADO -->
    <div class="hotels-grid" id="hotelsGrid">
      <?php if ($custom_query->have_posts()) : ?>
        <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>

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

          <div class="card-hotel js-hotel-item <?php echo esc_attr($clase_categoria); ?>">
            <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">

            <div class="cont-card-hotel">
              <label><?php echo !empty($term_names) ? esc_html(implode(', ', $term_names)) : ''; ?></label>
              <h6><?php the_title(); ?></h6>

              <ul>
                <?php if (!empty($telefono)) : ?>
                  <li><a target="_blank" rel="noopener noreferrer" href="tel:<?php echo esc_attr($telefono); ?>"><i class="fa-solid fa-phone"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($facebook)) : ?>
                  <li><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($facebook); ?>"><i class="fa-brands fa-facebook-f"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($instagram)) : ?>
                  <li><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($instagram); ?>"><i class="fa-brands fa-instagram"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($twitter_x)) : ?>
                  <li><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($twitter_x); ?>"><i class="fa-brands fa-x-twitter"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($ubicacion)) : ?>
                  <li><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($ubicacion); ?>"><i class="fa-solid fa-location-dot"></i></a></li>
                <?php endif; ?>
              </ul>

              <?php if (!empty($reserva)) : ?>
                <a class="btn" href="<?php echo esc_url($reserva); ?>" target="_blank" rel="noopener noreferrer">Reservar ahora</a>
              <?php endif; ?>
            </div>
          </div>

        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <p>No se encontraron hoteles.</p>
      <?php endif; ?>
    </div>

    <script>
      jQuery(function($) {
        const $items = $(".js-hotel-item");
        const $select = $("#filterHotelsSelect");
        const $count = $("#visibleHotelsCount");

        function updateCount() {
          $count.text($items.filter(":visible").length);
        }

        function applyFilter(filter) {
          if (!filter || filter === "all") {
            $items.show();
            updateCount();
            return;
          }

          $items.hide();
          $items.filter("." + filter).show();
          updateCount();
        }

        // Init
        applyFilter("all");

        // Change
        $select.on("change", function() {
          applyFilter($(this).val());
        });
      });
    </script>

    <style>
      /* Topbar */
      .hotels-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin: 20px 0 18px;
      }

      .hotels-count {
        font-size: 14px;
        opacity: 0.85;
      }

      /* Grid */
      .hotels-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
      }

      @media (max-width: 1024px) {
        .hotels-grid { grid-template-columns: repeat(2, 1fr); }
      }

      @media (max-width: 768px) {
        .hotels-topbar {
          flex-direction: column;
          align-items: stretch;
        }
        .hotels-grid { grid-template-columns: 1fr; }
        .hotels-count { text-align: left; }
      }
    </style>

  </section>

<?php endwhile; ?>

<?php get_footer(); ?>
