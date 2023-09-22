<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title(); ?></title>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header>
        <div class="cont-header">
            <div class="logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="Real - Hotel & Resourts">
                </a>
            </div>
            <div class="cont-menu">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                ));
                ?>
            </div>
        </div>
    </header>