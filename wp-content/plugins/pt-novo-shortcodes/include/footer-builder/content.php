<?php get_header() ?>
  <?php if(class_exists('Elementor\Plugin') && !\Elementor\Plugin::$instance->preview->is_preview_mode()) { ?>
    <div class="empty-screen-space"></div>
  <?php } ?>
<?php get_footer() ?>