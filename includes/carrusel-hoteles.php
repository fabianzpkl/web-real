<div class="filter-hotels">
    <span>Quiero ver</span>
    <?php echo do_shortcode('[select_categorias_hoteles]'); ?>
</div>
<?php
$args = array(
    'post_type' => 'hoteles',
    'posts_per_page' => -1,
    'orderby' => 'rand',
);

$custom_query = new WP_Query($args);
?>
<div class="carrusel-hoteles">
    <?php
    // Comenzamos el bucle
    if ($custom_query->have_posts()):
        while ($custom_query->have_posts()):
            $custom_query->the_post();

            // Obtenemos las categorías del post
            $categories = get_the_terms(get_the_ID(), 'categoria_hoteles');

            // Inicializamos una variable para la clase de categoría
            $clase_categoria = '';

            if ($categories && !is_wp_error($categories)) {
                $category_ids = array();

                foreach ($categories as $category) {
                    $category_ids[] = 'categoria-' . $category->term_id; // Agregamos "categoria-ID" a la lista de clases
                }

                $clase_categoria = implode(' ', $category_ids);
            }
            ?>
            <div class="card-hotel <?php echo esc_attr($clase_categoria); ?>">
                <?php
                // Obtiene la URL de la imagen destacada en el tamaño "medium"
                $medium_thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');

                // Verifica si la imagen destacada en tamaño "medium" existe
                if ($medium_thumbnail_url) {
                    // Si existe, muestra la imagen destacada en tamaño "medium"
                    echo '<img src="' . esc_url($medium_thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                } else {
                    // Si no existe, muestra una imagen predeterminada
                    echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/default-hotel.jpg') . '" alt="' . esc_attr(get_the_title()) . '">';
                }
                ?>
                <div class="cont-card-hotel">
                    <label>
                        <?php
                        // Obtenemos las categorías del post
                        $categories = get_the_terms(get_the_ID(), 'categoria_hoteles');

                        if ($categories && !is_wp_error($categories)) {
                            $category_names = array();

                            foreach ($categories as $category) {
                                $category_names[] = trim($category->name); // Elimina espacios en blanco al inicio o al final
                            }

                            echo implode(', ', $category_names); // Mostramos las categorías separadas por comas
                        }
                        ?>
                    </label>
                    <h6>
                        <?php the_title(); ?>
                    </h6>
                    <ul>
                        <?php
                        $contact_telefono = get_field("telefono");

                        if (!empty($contact_telefono)) {
                            echo '<li><a target="_blank" href="tel:' . esc_attr($contact_telefono) . '"><i class="fa-solid fa-phone"></i></a></a>';
                        }
                        ?>

                        <?php
                        $contact_facebook = get_field("facebook");

                        if (!empty($contact_facebook)) {
                            echo '<li><a target="_blank" href="' . esc_attr($contact_facebook) . '"><i class="fa-brands fa-facebook-f"></i></a></a>';
                        }
                        ?>

                        <?php
                        $contact_instagram = get_field("instagram");

                        if (!empty($contact_instagram)) {
                            echo '<li><a target="_blank" href="' . esc_attr($contact_instagram) . '"><i class="fa-brands fa-instagram"></i></a></a>';
                        }
                        ?>

                        <?php
                        $contact_twitter_x = get_field("twitter_x");

                        if (!empty($contact_twitter_x)) {
                            echo '<li><a target="_blank" href="' . esc_attr($contact_twitter_x) . '"><i class="fa-brands fa-x-twitter"></i></a></a>';
                        }
                        ?>

                        <?php
                        $contact_ubicacion = get_field("ubicacion");

                        if (!empty($contact_ubicacion)) {
                            echo '<li><a target="_blank" href="' . esc_attr($contact_ubicacion) . '"><i class="fa-solid fa-location-dot"></i></a></a>';
                        }
                        ?>

                    </ul>
                    <a class="btn" href="<?php echo esc_attr(get_field('link_de_reserva')); ?>" target="_blank">Reservar
                        Ahora</a>
                </div>
            </div>
            <?php
        endwhile;
        wp_reset_postdata(); // Restauramos los datos originales
    else:
        echo 'No se encontraron hoteles.';
    endif;
    ?>
</div>