<?php /* Template Name: Home */ ?>
<?php
get_header(); // Incluye el encabezado
?>

<div id="home_slider">
    <div class="scroll_info">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll.svg">
        <p>Más información abajo. <br>Desplázate para ver más.</p>
    </div>
    <div class="video_info">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gps.svg">
        <p><?php
            $texto_video = get_field('texto_video', 'option');
            echo do_shortcode(wpautop($texto_video));
            ?></p>
    </div>
    <video id="background-video" autoplay loop muted playsinline>
        <source src="<?php echo esc_attr(get_field('video_home', 'option')); ?>" type="video/mp4">
    </video>
    <div class="dec"></div>
    <aside class="more-info">
        <button class="close_bar">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="cont-action">
            <button class="actionbar bar1">
                <?php the_field("icon_1"); ?>
                <?php the_field("texto_boton_de_activacion_1"); ?>
            </button>
            <button class="actionbar bar2">
                <?php the_field("icon_2"); ?>
                <?php the_field('texto_boton_de_activacion_2'); ?>
            </button>
            <button class="actionbar bar3">
                <?php the_field("icon_3"); ?>
                <?php the_field('texto_boton_de_activacion_3'); ?>
            </button>
            <button class="actionbar bar4">
                Ver todos los hoteles
            </button>
        </div>
        <div class="cont-more-info">
            <div class="slide-more-info" style="background:url(' <?php the_field('imagen_de_fondo_1'); ?> ') center center">
                <div class="box-more-info">
                    <label>
                        <?php the_field("titulo_superior_1"); ?>
                    </label>
                    <h5>
                        <?php the_field("titulo_principal_1"); ?>
                    </h5>
                    <p>
                        <?php the_field("descripcion_1"); ?>
                    </p>
                    <a href="<?php the_field('link_del_boton_1'); ?>" target="_blank" class="btn">Ver más</a>
                </div>
            </div>
            <div class="slide-more-info">
                <div class="slide-more-info" style="background:url(' <?php the_field('imagen_de_fondo_2'); ?> ') center center">
                    <div class="box-more-info">
                        <label>
                            <?php the_field("titulo_superior_2"); ?>
                        </label>
                        <h5>
                            <?php the_field("titulo_principal_2"); ?>
                        </h5>
                        <p>
                            <?php the_field("descripcion_2"); ?>
                        </p>
                        <a href="<?php the_field('link_del_boton_2'); ?>" target="_blank" class="btn">Ver más</a>
                    </div>
                </div>
            </div>
            <div class="slide-more-info">
                <div class="slide-more-info" style="background:url(' <?php the_field('imagen_de_fondo_3'); ?> ') center center">
                    <div class="box-more-info">
                        <label>
                            <?php the_field("titulo_superior_3"); ?>
                        </label>
                        <h5>
                            <?php the_field("titulo_principal_3"); ?>
                        </h5>
                        <p>
                            <?php the_field("descripcion_3"); ?>
                        </p>
                        <a href="<?php the_field('link_del_boton_3'); ?>" target="_blank" class="btn">Ver más</a>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</div>

<section class="cont_page">
    <?php
    while (have_posts()) :
        the_post();

    ?>

        <?php the_content(); ?>

    <?php
    endwhile;
    ?>
</section>

<?php
get_footer(); // Incluye el pie de página
?>