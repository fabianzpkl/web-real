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
                <p><?php the_field("texto_introductorio"); ?> </p>
            </div>

        </div>
    </div>

    <section class="cont_page">


        <?php the_content(); ?>


    </section>

<?php
endwhile;
?>

<?php
get_footer(); // Incluye el pie de página
?>