<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php wp_title(); ?>
    </title>
    <?php wp_head(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body <?php body_class(); ?>>
    <header>
        <div class="logo">
            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="Real - Hotel & Resourts">
            </a>
        </div>
        <div class="cont-menu">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                )
            );
            ?>
        </div>
        <button id="tootgle_menu_mob">
            <i class="fa-solid fa-bars"></i>
        </button>
    </header>

    <aside id="bar-social">
        <ul class="link-top">
            <li>
                <a href="#">
                    <i class="fa-solid fa-location-dot"></i>
                    <label for="">Destinos</label>
                </a>
            </li>
            <li>
                <a href="#" id="toggle-language">
                    ES
                    <label for="">Idioma</label>
                </a>
            </li>
        </ul>
        <ul class="link-bottom">
            <li>
                <a href="<?php echo esc_attr(get_field('linkedin', 'option')); ?>" target="_blank">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo esc_attr(get_field('facebook', 'option')); ?>" target="_blank">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo esc_attr(get_field('instagram', 'option')); ?>" target="_blank">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </li>
        </ul>
    </aside>