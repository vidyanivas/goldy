<?php

if (!defined('ABSPATH')) {
	exit;
}

class YPRM_Popup {
  public function __construct() {
    add_action('wp_enqueue_scripts', array($this, 'enqueue'));

    add_action('wp_ajax_share_template', array($this, 'share_template'));
    add_action('wp_ajax_nopriv_share_template', array($this, 'share_template'));

    add_action('wp_ajax_share_by_mail', array($this, 'send_mail'));
    add_action('wp_ajax_nopriv_share_by_mail', array($this, 'send_mail'));
  }

  public function enqueue() {
    wp_enqueue_script('popup-js', YPRM_PLUGINS_HTTP . '/include/popup/script.js', array('jquery', 'gsap'), '1.0.0', true);
    wp_enqueue_style('popup-css', YPRM_PLUGINS_HTTP . '/include/popup/style.css', false, '1.0.0');


    if(function_exists('yprm_get_theme_setting')) {
      wp_localize_script('popup-js', 'yprm_popup_vars',
        array(
          'likes' => esc_html__('likes', 'pt-addons'),
          'like' => esc_html__('like', 'pt-addons'),
          'view_project' => esc_html__('view project', 'pt-addons'),
          'popup_arrows' => yprm_get_theme_setting('popup_arrows'),
          'popup_counter' => yprm_get_theme_setting('popup_counter'),
          'popup_back_to_grid' => yprm_get_theme_setting('popup_back_to_grid'),
          'popup_fullscreen' => yprm_get_theme_setting('popup_fullscreen'),
          'popup_autoplay' => yprm_get_theme_setting('popup_autoplay'),
          'popup_share' => yprm_get_theme_setting('popup_share'),
          'popup_likes' => yprm_get_theme_setting('popup_likes'),
          'popup_project_link' => yprm_get_theme_setting('popup_project_link'),
          'popup_image_title' => yprm_get_theme_setting('popup_image_title'),
          'popup_image_desc' => yprm_get_theme_setting('popup_image_desc'),
        )
      );
    }
  }

  public function send_mail() {
    $mail_content = 'Url - '.$_POST['share_url'];
    $mail = mail($_POST['email'], 'Share Image from "'.get_bloginfo('name').'"', $mail_content);

    echo $mail;
    wp_die();
  }

  public function share_button() { 
    add_action('wp_footer', function() { ?>
      <div class="share-popup-block">
        <?php $this->share_template() ?>
      </div>
    <?php });
  ?>
    <div class="share-popup-button popup-icon-share"></div>
  <?php }

  public function share_template() { 
    if(isset($_POST['share_url'])) {
      $share_url = $_POST['share_url'];
    } else {
      $share_url = get_permalink(get_the_ID());
    }
  ?>
    <div class="share-popup">
      <div class="close popup-icon-close"></div>
      <div class="title"><?php echo esc_html__('Share:', 'pt-addons') ?></div>
      <div class="share-form-block">
        <div class="label"><?php echo esc_html__('Link', 'pt-addons') ?></div>
        <div class="input">
          <input name="copy_url" type="text" value="<?php echo esc_url($share_url) ?>">
        </div>
        <button class="share-button copy-button"><?php echo esc_html__('Copy', 'pt-addons') ?></button>
        <div class="message"><?php echo esc_html__('Link copied to clipboard', 'pt-addons') ?></div>
      </div>
      <div class="label"><?php echo esc_html__('Social', 'pt-addons') ?></div>
      <?php echo yprm_share_buttons($share_url, true) ?>
    </div>
    <?php if(isset($_POST['share_url'])) {
      wp_die();
    } ?>
  <?php }
}

new YPRM_Popup();