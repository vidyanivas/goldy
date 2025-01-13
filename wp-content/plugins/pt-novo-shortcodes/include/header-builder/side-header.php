<?php 
  $site_scheme = yprm_get_theme_setting('site_color_mode');
  $copyright_text = yprm_get_theme_setting('copyright_text');
  $social_links = yprm_build_social_links('with-label');
  $side_header_socials = yprm_get_theme_setting('side_header_socials');

  $header_color_mode = 'light';

  if($site_scheme == 'light') {
    $header_color_mode = 'dark';
  }
?>

<div class="site-header mobile-type fixed-header <?php echo esc_attr($header_color_mode.'-header') ?>">
  <div class="header-main-block">
    <div class="container-fluid">
      <div class="row">
        <div class="mobile-main-bar-left col-auto">
          <div class="logo-block">
            <div class="logo site-logo-60efd9a885fa8">
              <?php echo yprm_site_logo() ?>
            </div>
          </div>
        </div>
        <div class="mobile-main-bar-right col">
          <div class="butter-button nav-button visible_menu" data-type=".navigation-6013c5023bebc"><div></div></div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="mobile-navigation-block navigation-6013c5023bebc <?php echo esc_attr($header_color_mode.'-header') ?>">
  <nav class="mobile-navigation hover-style2">
    <?php echo wp_nav_menu(array('theme_location' => 'navigation', 'container' => 'ul', 'menu_class' => 'menu container', 'link_before' => '<span>', 'link_after' => '</span>', 'echo' => false)); ?>
  </nav>
</div>
<div class="side-header from-builder main-row <?php echo esc_attr($site_scheme) ?>">
  <div class="logo"><?php echo yprm_site_logo(); ?></div>
  <div class="wrap">
    <div class="cell">
      <nav class="side-navigation"><?php wp_nav_menu( array( 'theme_location' => 'navigation', 'container' => 'ul', 'menu_class' => 'menu', 'link_before' => '<span>', 'link_after' => '</span>', 'walker' => new Child_Wrap(), ) ); ?></nav>
    </div>
  </div>
  <?php if(!empty($copyright_text)) { ?>
    <?php if($side_header_socials == 'true'){ ?>
    <div class="side-social"><?php echo $social_links ?></div>
    <?php  } ?>
    <div class="copyright"><?php echo $copyright_text ?></div>
  <?php } ?>
</div>