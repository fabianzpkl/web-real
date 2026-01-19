<footer>
  <div class="container">
    <div class="row">

      <!-- LOGO -->
      <div class="col s12 m4 l3 center-align left-align-on-large">
        <img class="logo" src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-footer.svg" alt="Real - Hotel & Resourts">
      </div>

      <!-- COLUMNAS -->
      <div class="col s12 m8 l9">
        <div class="row">

          <div class="col s12 m6 l3">
            <?php if (is_active_sidebar('footer_col_1')): ?>
              <?php dynamic_sidebar('footer_col_1'); ?>
            <?php else: ?>
              <h6>Footer Columna 1</h6>
              <ul><li><a href="#">Agrega widgets en Apariencia → Widgets</a></li></ul>
            <?php endif; ?>
          </div>

          <div class="col s12 m6 l3">
            <?php if (is_active_sidebar('footer_col_2')): ?>
              <?php dynamic_sidebar('footer_col_2'); ?>
            <?php else: ?>
              <h6>Footer Columna 2</h6>
              <ul><li><a href="#">Agrega widgets en Apariencia → Widgets</a></li></ul>
            <?php endif; ?>
          </div>

          <div class="col s12 m6 l3">
            <?php if (is_active_sidebar('footer_col_3')): ?>
              <?php dynamic_sidebar('footer_col_3'); ?>
            <?php else: ?>
              <h6>Footer Columna 3</h6>
              <ul><li><a href="#">Agrega widgets en Apariencia → Widgets</a></li></ul>
            <?php endif; ?>
          </div>

          <div class="col s12 m6 l3">
            <?php if (is_active_sidebar('footer_col_4')): ?>
              <?php dynamic_sidebar('footer_col_4'); ?>
            <?php else: ?>
              <h6>Footer Columna 4</h6>
              <ul><li><a href="#">Agrega widgets en Apariencia → Widgets</a></li></ul>
            <?php endif; ?>
          </div>

        </div>

        <!-- BLOQUE LOGOS -->
        <div class="row">
          <div class="col s12">
            <h6>Real hotels & resorts. parte del grupo poma:</h6>
            <br>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logos-dummy.png" class="responsive-img" alt="">
          </div>
        </div>
      </div>
    </div>

    <!-- LEGALES -->
    <div class="row">
      <div class="col s12 center-align left-align-on-large">
        <?php if (is_active_sidebar('footer_legales')): ?>
          <?php dynamic_sidebar('footer_legales'); ?>
        <?php else: ?>
          <p>Footer legales: agrega el texto en Apariencia → Widgets → “Footer Legales”.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
