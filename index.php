<?php
get_header(); // Incluye el encabezado
?>

<div id="home_slider">
    <video id="background-video" autoplay loop muted>
        <source src="<?php echo get_template_directory_uri(); ?>/assets/images/video-slider.mp4" type="video/mp4">
    </video>
    <aside id="more-info">

    </aside>
</div>

<section class="cont_page">
    <div class="container">
        <?php
        while (have_posts()) : the_post();

        endwhile;
        ?>
    </div>
</section>

<?php
get_footer(); // Incluye el pie de página
?>