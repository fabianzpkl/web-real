<?php /* Template Name: Home */?>
<?php
get_header(); // Incluye el encabezado
?>

<div id="home_slider">
    <video id="background-video" autoplay loop muted>
        <source src="<?php echo get_template_directory_uri(); ?>/assets/images/video-slider.mp4" type="video/mp4">
    </video>
    <div class="dec"></div>
    <aside id="more-info">
    </aside>
</div>

<section class="cont_page">
    <?php
    while (have_posts()):
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