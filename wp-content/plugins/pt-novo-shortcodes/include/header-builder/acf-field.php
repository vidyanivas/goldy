<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('YPRM_ACF_Header_Builder')) {

	class YPRM_ACF_Header_Builder extends acf_field {

		var $settings;

		function __construct($settings) {
			add_action('wp_ajax_get_header_form_fields', array($this, 'get_header_form_fields'));
			add_action('wp_ajax_nopriv_get_header_form_fields', array($this, 'get_header_form_fields'));

			$this->name = 'yprm_header_builder';
			$this->label = esc_html__('Header Builder', 'pt-addons');

			$this->category = 'theme_core';

			$this->defaults = array(
				'font_size' => 14,
			);

			$this->l10n = array(
				'error' => esc_html__('Error! Please enter a higher value', 'pt-addons'),
			);

			$this->settings = $settings;

			parent::__construct();

		}

		public function render_field($field) {
			$value = $field['value'];

			if($value) {
				$value = json_decode($value, true);
			}

			$box_array = array(
				'desktop' => array(
					/* 'desktop_top_bar' => array(
						'title' => esc_html__('Top Bar', 'pt-addons'),
						'cols' => array('desktop_top_bar_left', 'desktop_top_bar_right')
					), */
					'desktop_main_bar' => array(
						'title' => esc_html__('Main Bar', 'pt-addons'),
						'cols' => array('desktop_main_bar_left', 'desktop_main_bar_right')
					),
					'desktop_bottom_bar' => array(
						'title' => esc_html__('Bottom Bar', 'pt-addons'),
						'cols' => array('desktop_bottom_bar_left', 'desktop_bottom_bar_center', 'desktop_bottom_bar_right')
					),
				),
				'mobile' => array(
					/* 'mobile_top_bar' => array(
						'title' => esc_html__('Top Bar', 'pt-addons'),
						'cols' => array('mobile_top_bar_left', 'mobile_top_bar_right')
					), */
					'mobile_main_bar' => array(
						'title' => esc_html__('Main Bar', 'pt-addons'),
						'cols' => array('mobile_main_bar_left', 'mobile_main_bar_right')
					),
					'mobile_bottom_bar' => array(
						'title' => esc_html__('Bottom Bar', 'pt-addons'),
						'cols' => array('mobile_bottom_bar_left', 'mobile_bottom_bar_right')
					),
				)
			); ?>
			<div class="yprm-header-builder-area">
				<div class="head-block">
					<div class="logo"><img src="<?php echo esc_url(YPRM_PLUGINS_HTTP.'/assets/imgs/logo-light.png') ?>" alt="<?php echo esc_attr(get_bloginfo('name')) ?>"></div>
					<div class="h"><?php echo esc_html__('Header Builder', 'pt-addons') ?></div>
					<div class="screen-swither">
						<div class="tab-button current" data-type="desktop"><?php echo esc_html__('Desktop', 'pt-addons') ?></div>
						<div class="tab-button" data-type="mobile"><?php echo esc_html__('Tablet & Mobile', 'pt-addons') ?></div>
					</div>
					<div class="clear-all"><i class="ionicons-close"></i><span><?php echo esc_html__('Clear all', 'pt-addons') ?></span></div>
				</div>
				
				<?php foreach($box_array as $screenType => $box) { ?>
					<div class="header-grid<?php echo $screenType == 'desktop' ? ' current' : '' ?>" data-screen-type="<?php echo esc_attr($screenType) ?>">
						<?php echo $this->edit_box_button($screenType, isset($value[$screenType]['values']) ? $value[$screenType]['values'] : ''); ?>
						<?php foreach($box as $rowType => $box_row) { ?>
							<div class="header-row row" data-row-type="<?php echo esc_attr($rowType) ?>">
								<div class="col-12 row-label">
									<?php echo $box_row['title'] ?>
									<?php echo $this->edit_box_button($rowType, isset($value[$rowType]['values']) ? $value[$rowType]['values'] : ''); ?>
								</div>
								<?php foreach($box_row['cols'] as $box_col) { ?>
									<div class="col-<?php echo 12/count($box_row['cols']) ?>">
										<div class="dots">
											<div></div>
										</div>
										<div class="drop-box<?php echo (isset($value[$box_col]['elements']) && count($value[$box_col]['elements']) > 0) ? ' has-elements' : '' ?>" data-col-type="<?php echo esc_attr($box_col) ?>">
											<?php echo $this->edit_box_button($box_col, isset($value[$box_col]['values']) ? $value[$box_col]['values'] : ''); ?>
										</div>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<div class="header-elements">
					<div class="dots">
						<div></div>
					</div>
					<div class="title"><?php echo esc_html__('Add element', 'pt-addons') ?></div>
					<div class="wrap">
					</div>
				</div>
				<div class="all-els">
					<?php foreach($this->get_header_elements() as $type => $elem) {
						$this->render_element($type, array(), 'add-element');
					} if($value) {
						foreach($value as $key => $box) {
							if(is_array($box['elements']) && count($box['elements']) > 0) {
								foreach($box['elements'] as $elem) {
									echo $this->render_element($elem['type'], $elem['values'], $key);
								}
							}
						}
					} ?>
				</div>
				<input type="hidden" class="header-builder-input" name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($field['value']) ?>">
			</div>
		<?php }

		public function render_element($type, $values = array(), $parent_box = '') {
			$el = $this->get_header_element($type, $values);

      if(!$el) return false;
			?>
			<div class="button-el <?php echo esc_attr('type-'.$el['slug']) ?>" data-header-element="<?php echo esc_attr($el['slug']) ?>" data-title="<?php echo esc_attr($el['title']) ?>" title="<?php echo esc_attr($el['title']) ?>" data-icon="<?php echo esc_attr($el['icon']) ?>" data-fields="<?php echo esc_attr(json_encode($el['params'])) ?>" data-values="<?php echo esc_attr(json_encode($values)) ?>" data-parent-box="<?php echo esc_attr($parent_box) ?>">
				<div>
					<div class="cross">
						<i class="base-icon-close"></i>
					</div>
					<?php if(isset($el['icon'])) { ?>
						<i class="<?php echo esc_attr($el['icon']) ?>"></i>
					<?php } else { ?>
						<i></i>
					<?php } ?>
					<span><?php echo strip_tags($el['title']) ?></span>
				</div>
			</div>
			<?php 
		}

		public function input_admin_enqueue_scripts() {
			yprm_enqueue_fonts();

			wp_register_script('pt-header-builder', $this->settings['url'] . '/js/header-builder.js', array('jquery', 'draggable'), '1.0.0', true);
			wp_enqueue_script('pt-header-builder');
		}

		public function get_header_form_fields() {
      if(!isset($_POST['values'])) $_POST['values'] = [];

			if(is_array($_POST['fields']) && count($_POST['fields']) > 0) {
				foreach($_POST['fields'] as $field) {
					$this->set_form_field_value($field, $_POST['values']);

					apply_filters('header_builder_field_'.$field['type'], $field);
				}
			}

			wp_die();
		}
		
		public function set_form_field_value(&$field, $values) {
			if(is_array($values) && count($values) > 0) {
				foreach($values as $key => $value) {
					if($field['param_name'] == $key) return $field['value'] = $value;
				}
			}
		}

		public function edit_box_button($box, $values = array()) {
			$array = $this->get_box_params($box, $values);

			if(empty($values)) {
			} else {
				$values = json_encode($values);
			}

			if(!$array) {
				$array = array(
					'title' => '',
					'params' => ''
				);
			}
			?>
			<div class="edit-box" data-box-type="<?php echo esc_attr($box) ?>" data-title="<?php echo esc_attr($array['title']) ?>" data-fields="<?php echo esc_attr(json_encode($array['params'])) ?>" data-values="<?php echo esc_attr($values) ?>">
				<i></i>
				<?php if(
					$box == 'desktop_top_bar' ||
					$box == 'desktop_main_bar' ||
					$box == 'desktop_bottom_bar' ||
					$box == 'mobile_top_bar' ||
					$box == 'mobile_main_bar' ||
					$box == 'mobile_bottom_bar'
				) { ?>
					<span><?php echo esc_html__('Settings', 'pt-addons'); ?></span>
				<?php } else if(
					$box == 'desktop' ||
					$box == 'mobile'
				) { ?>
					<span><?php echo esc_html__('General Settings', 'pt-addons'); ?></span>
				<?php } ?>
			</div>
			<?php
		}

		public function get_header_elements($elem = null) {
			$result_array = array();
			$result_array['logo'] = $logo = array(
				'title' => esc_html__('Logo', 'pt-addons'),
				'slug' => 'logo',
				'icon' => '',
				'params' => array(
					array(
						'type' => 'image_picker',
						'heading' => esc_html__('Light Logo', 'pt-addons'),
						'param_name' => 'image_light',
					),
					array(
						'type' => 'image_picker',
						'heading' => esc_html__('Dark Logo', 'pt-addons'),
						'param_name' => 'image_dark',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Logo Text', 'pt-addons'),
						'param_name' => 'text',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Logo Text Font Size', 'pt-addons'),
						'param_name' => 'text_fz',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Logo Image Width', 'pt-addons'),
						'param_name' => 'width',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Logo Image Height', 'pt-addons'),
						'param_name' => 'height',
					),
					array(
						"type" => "switch",
						"heading" => esc_html__("Logo On Center", "pt-addons"),
						"param_name" => "logo_on_center",
						"value" => "no",
						"options" => array(
							"yes" => esc_html__("Yes", "pt-addons"),
							"no" => esc_html__("No", "pt-addons"),
						),
					),
				)
			);

			$result_array['button'] = $button = array(
				'title' => esc_html__('Button', 'pt-addons'),
				'slug' => 'button',
				'icon' => '',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Label", "pt-addons"),
						"param_name" => "label",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Link", "pt-addons"),
						"param_name" => "link",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					/* array(
						"type" => "dropdown",
						"heading" => esc_html__("Icon", "pt-addons"),
						"param_name" => "icon",
						"options" => array(
							esc_html__("None", "pt-addons") => "",
							esc_html__("Calendar", "pt-addons") => "calendar",
							esc_html__("Phone", "pt-addons") => "ionicons-call-outline",
							esc_html__("Download", "pt-addons") => "ionicons-download-outline",
							esc_html__("Help", "pt-addons") => "ionicons-help-buoy-outline",
							esc_html__("Flash", "pt-addons") => "ionicons-flash",
							esc_html__("Heart", "pt-addons") => "ionicons-heart-outline",
							esc_html__("Placeholder", "pt-addons") => "ionicons-location-outline",
							esc_html__("Mail", "pt-addons") => "ionicons-mail-outline",
							esc_html__("Paper Plane", "pt-addons") => "ionicons-paper-plane-outline",
							esc_html__("Person", "pt-addons") => "ionicons-person",
							esc_html__("Clock", "pt-addons") => "ionicons-time-outline",
							esc_html__("Rocket", "pt-addons") => "ionicons-rocket-outline",
						),
					), */
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Style", "pt-addons"),
						"param_name" => "style",
						"options" => array(
							esc_html__("Style 1", "pt-addons") => "style1",
							esc_html__("Style 2", "pt-addons") => "style2",
						),
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color on Hover", "pt-addons"),
						"param_name" => "color_on_hover",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Background Color", "pt-addons"),
						"param_name" => "bg_color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Background Color on Hover", "pt-addons"),
						"param_name" => "bg_color_on_hover",
					),
				)
			);

			$result_array['text'] = $text = array(
				'title' => esc_html__('Text', 'pt-addons'),
				'slug' => 'text',
				'icon' => 'base-icon-',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Text", "pt-addons"),
						"param_name" => "text",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			if(function_exists('pll_the_languages') || function_exists('icl_get_languages')) {
				$result_array['language'] = $language = array(
					'title' => esc_html__('Language', 'pt-addons'),
					'slug' => 'language',
					'icon' => 'base-icon-translate',
					'params' => array(
						array(
							"type" => "textfield",
							"heading" => esc_html__("Font Size", "pt-addons"),
							"param_name" => "font_size",
						),
						array(
							"type" => "dropdown",
							"heading" => esc_html__("With Background", "pt-addons"),
							"param_name" => "with_background",
							"options" => array(
								esc_html__("Yes", "pt-addons") => "yes",
								esc_html__("No", "pt-addons") => "no",
							),
						),
						array(
							"type" => "colorpicker",
							"heading" => esc_html__("Background Color", "pt-addons"),
							"param_name" => "background_color",
							'dependency' => Array('element' => 'with_background', 'value' => 'yes' ),
						),
						array(
							"type" => "colorpicker",
							"heading" => esc_html__("Color", "pt-addons"),
							"param_name" => "color",
						),
						array(
							"type" => "colorpicker",
							"heading" => esc_html__("Color on Hover", "pt-addons"),
							"param_name" => "color_on_hover",
						),
					)
				);
			}

			$result_array['link'] = $link = array(
				'title' => esc_html__('Link', 'pt-addons'),
				'slug' => 'link',
				'icon' => 'base-icon-link',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Label", "pt-addons"),
						"param_name" => "label",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Link", "pt-addons"),
						"param_name" => "link",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Background Color", "pt-addons"),
						"param_name" => "background_color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Hover Color", "pt-addons"),
						"param_name" => "color_on_hover",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Background Hover Color", "pt-addons"),
						"param_name" => "background_color_on_hover",
					),
				)
			);

			$result_array['social'] = $social = array(
				'title' => esc_html__('Social', 'pt-addons'),
				'slug' => 'social',
				'icon' => 'base-icon-twitter',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Icon Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color on Hover", "pt-addons"),
						"param_name" => "color_on_hover",
					),
				)
			);

			$result_array['menu'] = $menu = array(
				'title' => esc_html__('Menu', 'pt-addons'),
				'slug' => 'menu',
				'icon' => 'base-icon-menu',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Navigation", "pt-addons"),
						"param_name" => "navigation",
						"options" => array_merge(
							array(
								esc_html__("Default", "pt-addons") => "default",
							),
							array_flip(
								wp_get_nav_menus(
									array(
										'fields' => 'id=>name'
									)
								)
							)
						),
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Type", "pt-addons"),
						"param_name" => "type",
						"options" => array(
							esc_html__("Visible", "pt-addons") => "visible_menu",
							esc_html__("Hidden", "pt-addons") => "hidden_menu",
							esc_html__("Full Screen", "pt-addons") => "full_screen",
							//esc_html__("Centered with Logo", "pt-addons") => "centered_with_logo",
						),
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Hover Type", "pt-addons"),
						"param_name" => "hover_type",
						"options" => array(
							esc_html__("Style 1", "pt-addons") => "style1",
							esc_html__("Style 2", "pt-addons") => "style2",
							esc_html__("Style 3", "pt-addons") => "style3",
							esc_html__("Style 4", "pt-addons") => "style4",
						),
            //'dependency' => Array('element' => 'type', 'value' => array('visible_menu', 'hidden_menu') ),
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Current Page Color", "pt-addons"),
						"param_name" => "current_page_color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Submenu color", "pt-addons"),
						"param_name" => "submenu_color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Submenu Background color", "pt-addons"),
						"param_name" => "submenu_bg_color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Submenu Border Color", "pt-addons"),
						"param_name" => "submenu_border_color",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Submenu Font Size", "pt-addons"),
						"param_name" => "submenu_font_size",
					),
				)
			);

			$result_array['space'] = $space = array(
				'title' => esc_html__('Space', 'pt-addons'),
				'slug' => 'space',
				'icon' => '',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Width", "pt-addons"),
						"param_name" => "width",
						"options" => array(
							esc_html__("5px", "pt-addons") => "5",
							esc_html__("10px", "pt-addons") => "10",
							esc_html__("15px", "pt-addons") => "15",
							esc_html__("20px", "pt-addons") => "20",
							esc_html__("25px", "pt-addons") => "25",
							esc_html__("30px", "pt-addons") => "30",
							esc_html__("Auto", "pt-addons") => "auto",
							esc_html__("Custom", "pt-addons") => "custom",
						),
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Custom Width", "pt-addons"),
						"param_name" => "custom_width",
						"dependency" => Array("element" => "width", "value" => "custom" ),
					),
				)
			);

			$result_array['divider'] = $divider = array(
				'title' => esc_html__('Divider', 'pt-addons'),
				'slug' => 'divider',
				'icon' => '',
				'params' => array(
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$result_array['address'] = $address = array(
				'title' => esc_html__('Address', 'pt-addons'),
				'slug' => 'address',
				'icon' => 'base-icon-location',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Address", "pt-addons"),
						"param_name" => "address",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$result_array['phone'] = $phone = array(
				'title' => esc_html__('Phone', 'pt-addons'),
				'slug' => 'phone',
				'icon' => 'base-icon-phone-receiver-silhouette',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Phone Number", "pt-addons"),
						"param_name" => "phone",
					),
					array(
						"type" => "switch",
						"heading" => esc_html__("Use as Link", "pt-addons"),
						"param_name" => "is_link",
						"value" => "no",
						"options" => array(
							"yes" => esc_html__("Yes", "pt-addons"),
							"no" => esc_html__("No", "pt-addons"),
						),
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$result_array['search'] = $search = array(
				'title' => esc_html__('Search', 'pt-addons'),
				'slug' => 'search',
				'icon' => 'base-icon-magnifying-glass',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Icon Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color on Hover", "pt-addons"),
						"param_name" => "color_on_hover",
					),
				)
			);

			$result_array['sidebar'] = $sidebar = array(
				'title' => esc_html__('Sidebar', 'pt-addons'),
				'slug' => 'sidebar',
				'icon' => 'base-icon-menu',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Sidebar", "pt-addons"),
						"param_name" => "type",
						"options" => $this->get_registered_sidebars(),
            "value" => "sidebar"
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Icon Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color on Hover", "pt-addons"),
						"param_name" => "color_on_hover",
					),
				)
			);

			if (class_exists('WooCommerce')) {
				$result_array['cart'] = $cart = array(
					'title' => esc_html__('Cart', 'pt-addons'),
					'slug' => 'cart',
					'icon' => 'base-icon-shopping-cart1',
					'params' => array(
						array(
							"type" => "textfield",
							"heading" => esc_html__("Icon Size", "pt-addons"),
							"param_name" => "font_size",
						),
						array(
							"type" => "colorpicker",
							"heading" => esc_html__("Color", "pt-addons"),
							"param_name" => "color",
						),
						array(
							"type" => "colorpicker",
							"heading" => esc_html__("Color on Hover", "pt-addons"),
							"param_name" => "color_on_hover",
						),
					)
				);
			}

			if(class_exists('TInvWL_Public_WishlistCounter')) {
				$result_array['wishlist'] = $wishlist = array(
					'title' => esc_html__('Wishlist', 'pt-addons'),
					'slug' => 'wishlist',
					'icon' => 'base-icon-like',
					'params' => array(
						array(
							"type" => "textfield",
							"heading" => esc_html__("Icon Size", "pt-addons"),
							"param_name" => "font_size",
						),
						array(
							"type" => "colorpicker",
							"heading" => esc_html__("Color", "pt-addons"),
							"param_name" => "color",
						),
						array(
							"type" => "colorpicker",
							"heading" => esc_html__("Color on Hover", "pt-addons"),
							"param_name" => "color_on_hover",
						),
					)
				);
			}

			/* $result_array['login'] = $login = array(
				'title' => esc_html__('Login', 'pt-addons'),
				'slug' => 'login',
				'icon' => 'base-icon-',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Custom Link To Login Page", "pt-addons"),
						"param_name" => "custom_link",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Icon Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color on Hover", "pt-addons"),
						"param_name" => "color_on_hover",
					),
				)
			); */

			$result_array['full_screen'] = $full_screen = array(
				'title' => esc_html__('Full', 'pt-addons'),
				'slug' => 'full_screen',
				'icon' => 'base-icon-expand',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Icon Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color on Hover", "pt-addons"),
						"param_name" => "color_on_hover",
					),
				)
			);

			return $result_array;
		}

		public function get_header_element($elem, $values = array()) {
      if(!isset($this->get_header_elements()[$elem])) return false;

			$element = $this->get_header_elements()[$elem];

			if(count($values) > 0) {
				foreach($element['params'] as $key => $param) {
					if(isset($values[$param['param_name']])) {
						$element['params'][$key]['value'] = $values[$param['param_name']];
					}
				}
			}

			return $element;
		}

		public function get_box_params($area, $values = array()) {

			$desktop = array(
				'title' => esc_html__('Desktop', 'pt-addons'),
				'slug' => 'desktop',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Color Mode", "pt-addons"),
						"param_name" => "color_mode",
						"options" => array(
							esc_html__("Light", "pt-addons") => "light",
							esc_html__("Dark", "pt-addons") => "dark",
						),
						"desc" => __('<b>Light</b> - White text color &amp; Dark background color<br><b>Dark</b> - Black text color &amp; Light background color', 'pt-addons'),
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Container", "pt-addons"),
						"param_name" => "container",
						"options" => array(
							esc_html__("Default", "pt-addons") => "container",
							esc_html__("Stretch row", "pt-addons") => "container-fluid",
						),
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Position", "pt-addons"),
						"param_name" => "position",
						"options" => array(
							esc_html__("Sticky", "pt-addons") => "fixed",
							esc_html__("Default", "pt-addons") => "static",
						),
					),
				)
			);

			$mobile = array(
				'title' => esc_html__('Mobile', 'pt-addons'),
				'slug' => 'mobile',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Color Mode", "pt-addons"),
						"param_name" => "color_mode",
						"options" => array(
							esc_html__("Inherit", "pt-addons") => "",
							esc_html__("Light", "pt-addons") => "light",
							esc_html__("Dark", "pt-addons") => "dark",
						),
						"desc" => __('<b>Light</b> - White text color &amp; Dark background color<br><b>Dark</b> - Black text color &amp; Light background color', 'pt-addons'),
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Container", "pt-addons"),
						"param_name" => "container",
						"options" => array(
							esc_html__("Inherit", "pt-addons") => "",
							esc_html__("Default", "pt-addons") => "container",
							esc_html__("Stretch row", "pt-addons") => "container-fluid",
						),
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Position", "pt-addons"),
						"param_name" => "position",
						"options" => array(
							esc_html__("Sticky", "pt-addons") => "fixed",
							esc_html__("Default", "pt-addons") => "static",
						),
					),
				)
			);

			$desktop_top_bar = array(
				'title' => esc_html__('Desktop Top Bar', 'pt-addons'),
				'slug' => 'desktop_top_bar',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Background Color", "pt-addons"),
						"param_name" => "bg_color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Min Height", "pt-addons"),
						"param_name" => "min_height",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Border Width", "pt-addons"),
						"param_name" => "border_width",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Border Bottom Color", "pt-addons"),
						"param_name" => "border_color",
					),
				)
			);

			$desktop_main_bar = array(
				'title' => esc_html__('Desktop Main Bar', 'pt-addons'),
				'slug' => 'desktop_main_bar',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Background Color", "pt-addons"),
						"param_name" => "bg_color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Sticky Background Color", "pt-addons"),
						"param_name" => "bg_sticky_color",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Min Height", "pt-addons"),
						"param_name" => "min_height",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Min Height On Scroll", "pt-addons"),
						"param_name" => "min_height_on_scroll",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Border Width", "pt-addons"),
						"param_name" => "border_width",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Border Bottom Color", "pt-addons"),
						"param_name" => "border_color",
					),
				)
			);

			$desktop_bottom_bar = array(
				'title' => esc_html__('Desktop Bottom Bar', 'pt-addons'),
				'slug' => 'desktop_bottom_bar',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Background Color", "pt-addons"),
						"param_name" => "bg_color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Min Height", "pt-addons"),
						"param_name" => "min_height",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Border Width", "pt-addons"),
						"param_name" => "border_width",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Border Bottom Color", "pt-addons"),
						"param_name" => "border_color",
					),
				)
			);

			$desktop_top_bar_left = array(
				'title' => esc_html__('Desktop Top Bar Left', 'pt-addons'),
				'slug' => 'desktop_top_bar_left',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "left"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$desktop_top_bar_right = array(
				'title' => esc_html__('Desktop Top Bar Right', 'pt-addons'),
				'slug' => 'desktop_top_bar_right',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "right"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$desktop_main_bar_left = array(
				'title' => esc_html__('Desktop Main Bar Left', 'pt-addons'),
				'slug' => 'desktop_main_bar_left',
				'params' => array(
					/* array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "left"
					), */
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$desktop_main_bar_right = array(
				'title' => esc_html__('Desktop Main Bar Right', 'pt-addons'),
				'slug' => 'desktop_main_bar_right',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "right"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$desktop_bottom_bar_left = array(
				'title' => esc_html__('Desktop Bottom Bar Left', 'pt-addons'),
				'slug' => 'desktop_bottom_bar_left',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "left"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$desktop_bottom_bar_center = array(
				'title' => esc_html__('Desktop Bottom Bar Center', 'pt-addons'),
				'slug' => 'desktop_bottom_bar_center',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "center"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				),
			);

			$desktop_bottom_bar_right = array(
				'title' => esc_html__('Desktop Bottom Bar Right', 'pt-addons'),
				'slug' => 'desktop_bottom_bar_right',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "right"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$mobile_top_bar = array(
				'title' => esc_html__('Mobile Top Bar', 'pt-addons'),
				'slug' => 'mobile_top_bar',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Background Color", "pt-addons"),
						"param_name" => "bg_color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Min Height", "pt-addons"),
						"param_name" => "min_height",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Border Width", "pt-addons"),
						"param_name" => "border_width",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Border Bottom Color", "pt-addons"),
						"param_name" => "border_color",
					),
				)
			);

			$mobile_main_bar = array(
				'title' => esc_html__('Mobile Main Bar', 'pt-addons'),
				'slug' => 'mobile_main_bar',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Background Color", "pt-addons"),
						"param_name" => "bg_color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Sticky Background Color", "pt-addons"),
						"param_name" => "bg_sticky_color",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Min Height", "pt-addons"),
						"param_name" => "min_height",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Min Height On Scroll", "pt-addons"),
						"param_name" => "min_height_on_scroll",
					),
				)
			);

			$mobile_bottom_bar = array(
				'title' => esc_html__('Mobile Bottom Bar', 'pt-addons'),
				'slug' => 'mobile_bottom_bar',
				'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Font Size", "pt-addons"),
						"param_name" => "font_size",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Background Color", "pt-addons"),
						"param_name" => "bg_color",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Min Height", "pt-addons"),
						"param_name" => "min_height",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Border Width", "pt-addons"),
						"param_name" => "border_width",
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Border Bottom Color", "pt-addons"),
						"param_name" => "border_color",
					),
				)
			);

			$mobile_top_bar_left = array(
				'title' => esc_html__('Mobile Top Bar Left', 'pt-addons'),
				'slug' => 'mobile_top_bar_left',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "left"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$mobile_top_bar_right = array(
				'title' => esc_html__('Mobile Top Bar Right', 'pt-addons'),
				'slug' => 'mobile_top_bar_right',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "right"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$mobile_main_bar_left = array(
				'title' => esc_html__('Mobile Main Bar Left', 'pt-addons'),
				'slug' => 'mobile_main_bar_left',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "left"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$mobile_main_bar_right = array(
				'title' => esc_html__('Mobile Main Bar Right', 'pt-addons'),
				'slug' => 'mobile_main_bar_right',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "right"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$mobile_bottom_bar_left = array(
				'title' => esc_html__('Mobile Bottom Bar Left', 'pt-addons'),
				'slug' => 'mobile_bottom_bar_left',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "left"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			$mobile_bottom_bar_right = array(
				'title' => esc_html__('Mobile Bottom Bar Right', 'pt-addons'),
				'slug' => 'mobile_bottom_bar_right',
				'params' => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", "pt-addons"),
						"param_name" => "align",
						"options" => array(
							esc_html__("Left", "pt-addons") => "left",
							esc_html__("Center", "pt-addons") => "center",
							esc_html__("Right", "pt-addons") => "right",
						),
						"value" => "right"
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color", "pt-addons"),
						"param_name" => "color",
					),
				)
			);

			if(is_object($values) && count($values) > 0) {
				foreach($values as $label => $value) {
					foreach($$area['params'] as $key =>$param) {

						if($param['param_name'] == $label) {
							$$area['params'][$key]['value'] = $value;

							continue;
						}
					}
				}
			}

			return $$area;
		}

    public function get_registered_sidebars() {
      $result_array = array();

      if(is_array($GLOBALS['wp_registered_sidebars']) && count($GLOBALS['wp_registered_sidebars']) > 0) {
        foreach($GLOBALS['wp_registered_sidebars'] as $sidebar) {
          $result_array[$sidebar['name']] = $sidebar['id'];
        }
      }

      return $result_array;
    }

	}

	new YPRM_ACF_Header_Builder($this->settings);
}

?>