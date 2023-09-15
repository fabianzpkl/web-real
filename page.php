<?php
get_header(); // Incluye el encabezado

while (have_posts()) : the_post();
    // Contenido de la página
endwhile;

get_footer(); // Incluye el pie de página
