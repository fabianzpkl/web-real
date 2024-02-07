<?php /* Template Name: TP2B Gastronomía */ ?>
<?php
get_header(); // Incluye el encabezado
?>

<?php
while (have_posts()) :
    the_post();

?>

    <div class="thumbnail_page" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
        <div class="container center-align">
            <div class="cont_title_page">
                <h1><?php the_title(); ?></h1>
                <div class="row">
                    <div class="col s3">

                    </div>
                    <div class="col s6">
                        <p><?php the_field("texto_introductorio"); ?> </p>
                    </div>
                    <div class="col s3">
                    </div>
                </div>

            </div>

        </div>
    </div>

    <section class="cont_page">

        <div class="container">

            <div class="cont-tps2b-rows">
                <?php
                $args = array(
                    'post_type' => 'TP2B',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'categoria_tp2b',
                            'field'    => 'slug',
                            'terms'    => 'gastronomia', // Nombre de la categoría
                        ),
                    ),
                    'posts_per_page' => -1
                );

                $tp2b_query = new WP_Query($args);

                if ($tp2b_query->have_posts()) :
                    while ($tp2b_query->have_posts()) : $tp2b_query->the_post();
                        $full_thumbnail_url = get_the_post_thumbnail_url(); // Tamaño completo
                        $gallery_images = get_field('galeria_de_imagenes_tp2b'); // Reemplaza con el nombre de tu campo de galería
                        //$hoteldata = get_field('hotel_select_tp2b'); // Reemplaza con el nombre de tu campo de galería


                ?>
                        <div class="tp2b-post" data-title="<?php the_title(); ?>" data-thumbnail="<?php echo get_the_post_thumbnail_url(); ?>" data-descripcion="<?php echo get_field('descripcion_tp2b'); ?>" data-logo="<?php echo get_field('logo_tp2b'); ?>" data-gallery="<?php echo esc_attr(json_encode($gallery_images)); ?>">
                            <?php
                            $medium_thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                            if ($medium_thumbnail_url) {
                                echo '<img src="' . esc_url($medium_thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                            } else {
                                echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/default-hotel.jpg') . '" alt="' . esc_attr(get_the_title()) . '">';
                            }
                            ?>
                            <div class="cont-card-hotel">
                                <h6><?php the_title(); ?></h6>
                                <label><?php echo esc_attr(get_field('descripcion_tp2b')); ?></label>
                                <br>
                                <button class="btn">
                                    Ver más
                                </button>
                            </div>
                        </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No hay posts disponibles.</p>';
                endif;
                ?>

            </div>

        </div>

    </section>

<?php
endwhile;
?>

<!-- Modal Structure -->
<div id="tp2b-modal" class="modal">
    <div class="modal-content" id="tp2b-modal-content">
        <!-- Contenido de la modal se llenará con JavaScript -->
    </div>

</div>

<?php
get_footer(); // Incluye el pie de página
?>