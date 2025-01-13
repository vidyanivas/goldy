<?php 

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('YPRM_Build_Header')) {
	#[AllowDynamicProperties]
	class YPRM_Build_Header {
		

		var $header_id;
		var $header_css;
		var $params;
		var $container;
		var $color_mode;

		function __construct($id) {
      
      if($id == 'side') {

        include dirname(__FILE__).'/side-header.php';

        return false;
      }

			$id = intval($id);

			if(!is_numeric($id)) return false;

			$this->header_id = $id;
			$this->header_css = 'header-'.$id;
			$this->params = json_decode(get_field('header_builder', $id), false);

			$this->box_array = array(
				//'desktop_top_bar' => array('desktop_top_bar_left', 'desktop_top_bar_right'),
				'desktop_main_bar' => array('desktop_main_bar_left', 'desktop_main_bar_right'),
				'desktop_bottom_bar' => array('desktop_bottom_bar_left', 'desktop_bottom_bar_center', 'desktop_bottom_bar_right'),
				//'mobile_top_bar' => array('mobile_top_bar_left', 'mobile_top_bar_right'),
				'mobile_main_bar' => array('mobile_main_bar_left', 'mobile_main_bar_right'),
				'mobile_bottom_bar' => array('mobile_bottom_bar_left', 'mobile_bottom_bar_right'),
			);

			$this->colorMode();
			$this->includeTemplates();

			$this->render();
		}

		private function colorMode() {
			$color_mode = 'light';
			if(get_field('header_color_mode') && get_field('header_color_mode') != 'default') {
				$color_mode = get_field('header_color_mode');
			} elseif(isset($this->params->desktop->values->color_mode) && !empty($this->params->desktop->values->color_mode)) {
				$color_mode = $this->params->desktop->values->color_mode;
			}

			$this->color_mode = $color_mode;
		}

		private function includeTemplates() {
			include_once('views/logo.php');
			include_once('views/menu.php');
			include_once('views/link.php');
			include_once('views/social.php');
			include_once('views/space.php');
			include_once('views/divider.php');
			include_once('views/address.php');
			include_once('views/phone.php');
			include_once('views/search.php');
			include_once('views/sidebar.php');
			include_once('views/text.php');
			include_once('views/language.php');
			include_once('views/cart.php');
			include_once('views/whishlist.php');
			include_once('views/button.php');
			include_once('views/login.php');
			include_once('views/full_screen.php');
		}

		public function renderRow($row_name) {
			$pos = 'main';
			if(strpos($row_name, 'main') !== false) {
				$pos = 'main';
			} else if(strpos($row_name, 'bottom') !== false) {
				$pos = 'bottom';
			} else if(strpos($row_name, 'top') !== false) {
				$pos = 'top';
			}

			$rowAtts = $this->params->$row_name;
			$colsArray = $this->box_array[$row_name];

			if(!$this->rowHasElements($colsArray)) return false;

			$this->buildCSS($row_name, $rowAtts->values);

      $this->container = 'container';
			
			if(get_field('header_container') && get_field('header_container') != 'default') {
				$this->container = get_field('header_container');
			} elseif(isset($this->params->desktop->values->container) && !empty($this->params->desktop->values->container)) {
				$this->container = $this->params->desktop->values->container;
			}

			if($row_name != 'desktop_top_bar' && $row_name != 'mobile_top_bar') { ?>
				<div class="header-<?php echo esc_attr($pos) ?>-block">
			<?php } ?>
					<div class="<?php echo esc_attr($this->container) ?>">
						<div class="row">
							<?php if(count($colsArray) > 0) {
								foreach($colsArray as $col) {
									$this->renderCol($col);
								}
							} ?>
						</div>
					</div>
			<?php if($row_name != 'desktop_top_bar' && $row_name != 'mobile_top_bar') { ?>
				</div>
			<?php }
		}

		public function renderCol($col_name) {
			$col_css = [];

			$col_css[] = yprm_kebab_case($col_name);

			switch ($col_name) {
				case 'desktop_top_bar_left':
					$col_css[] = 'col-6';
					break;
				case 'desktop_top_bar_right':
					$col_css[] = 'col-6';
					break;
				case 'desktop_main_bar_left':
					$col_css[] = 'col-auto';
					break;
				case 'desktop_main_bar_right':
					$col_css[] = 'col';
					break;
				case 'desktop_bottom_bar_left':
					$col_css[] = 'col-4';
					break;
				case 'desktop_bottom_bar_center':
					$col_css[] = 'col-4';
					break;
				case 'desktop_bottom_bar_right':
					$col_css[] = 'col-4';
					break;
				case 'mobile_top_bar_left':
					$col_css[] = 'col-auto';
					break;
				case 'mobile_top_bar_right':
					$col_css[] = 'col';
					break;
				case 'mobile_main_bar_left':
					$col_css[] = 'col-auto';
					break;
				case 'mobile_main_bar_right':
					$col_css[] = 'col';
					break;
				case 'mobile_bottom_bar_left':
					$col_css[] = 'col-auto';
					break;
				case 'mobile_bottom_bar_right':
					$col_css[] = 'col';
					break;
				default:
					$col_css[] = 'col-12';
					break;
			}

			$values = $this->params->$col_name->values;
			$elements = $this->params->$col_name->elements;

			if((!is_array($elements) && !is_object($elements)) || count($elements) == 0) return false;

			$this->buildCSS($col_name, $values);

			if(isset($values->align)) {
				if($values->align == 'center') {
					$col_css[] = 'justify-content-center';
				} else if($values->align == 'right') {
					$col_css[] = 'justify-content-end';
				} else {
					$col_css[] = 'justify-content-start';
				}
			}

      $mobile = false;
      if(strpos($col_name, 'mobile') !== false) {
        $mobile = true;
      }

			?>
			<div class="<?php echo yprm_implode($col_css, '') ?>">
				<?php $this->renderElements($elements, $mobile); ?>
			</div>
			<?php
		}

		public function rowHasElements($colsArray) {
			foreach($colsArray as $col) {
				if($this->hasItems($col)) {
					return true;
				}
			}

			return false;
		}

		public function renderElements($elementsArray = array(), $mobile = false) {
			if(count($elementsArray) > 0) {
				foreach($elementsArray as $element) {
					apply_filters('header_builder_view_'.$element->type, $element->values, $mobile);
				}
			}
		}

		public function hasItems($block) {
			$has = false;
			if(isset($this->params->$block->elements) && count($this->params->$block->elements) > 0) {
				$has = true;
			}

			return $has;
		}

		public function render() {
      if(!isset($this->params->desktop->values)) return;

			extract(
				shortcode_atts(
					array(
						'color_mode' => 'light',
						'container' => 'container',
						'position' => 'fixed',
					),
					$this->params->desktop->values
				)
			);

			$this->container = $container;

			$header_css = [];

			$header_css[] = $this->header_css;
			$header_css[] = $this->color_mode.'-header';
			$header_css[] = $position.'-header';

			$header_top_css = [];
			$header_top_css[] = $this->header_css;
			$header_top_css[] = $this->color_mode.'-header';

			if($this->hasItems('desktop_top_bar_left') || $this->hasItems('desktop_top_bar_right')) { ?>
				<div class="site-header-top<?php echo yprm_implode($header_top_css) ?>">
					<?php echo $this->renderRow('desktop_top_bar'); ?>
				</div>
			<?php } ?>
      <header class="site-header<?php echo yprm_implode($header_css) ?>">
        <?php echo $this->renderRow('desktop_main_bar'); ?>
        <?php echo $this->renderRow('desktop_bottom_bar'); ?>
      </header>

      <?php 

        $mob_atts = shortcode_atts(
          array(
            'color_mode' => $this->color_mode,
            'container' => $container,
            'position' => 'fixed',
          ),
          $this->params->mobile->values
        );

				if(get_field('header_color_mode') && get_field('header_color_mode') != 'default') {
					$mob_atts['color_mode'] = get_field('header_color_mode');
        } else if(empty($mob_atts['color_mode'])) {
          $mob_atts['color_mode'] = $this->color_mode;
        }

        if(empty($mob_atts['container'])) {
          $mob_atts['container'] = $this->container;
        }

        $this->container = $mob_atts['container'];

        $mob_header_css = [];

        $mob_header_css[] = $this->header_css;
        $mob_header_css[] = $mob_atts['color_mode'].'-header';
        $mob_header_css[] = $mob_atts['position'].'-header';
      ?>
      
			<?php if($this->hasItems('mobile_top_bar_left') || $this->hasItems('mobile_top_bar_right')) { ?>
				<div class="site-header-top mobile-type<?php echo yprm_implode($header_top_css) ?>">
					<?php echo $this->renderRow('mobile_top_bar'); ?>
				</div>
			<?php } ?>
		  <div class="site-header mobile-type<?php echo yprm_implode($mob_header_css) ?>">
        <?php echo $this->renderRow('mobile_main_bar'); ?>
      </div>
      <?php
        global $mobile_nav_atts;

        $nav_args = array('theme_location' => 'navigation', 'container' => 'ul', 'menu_class' => 'menu container', 'link_before' => '<span>', 'link_after' => '</span>', 'echo' => false);

        $mob_type = '';
        if(isset($mobile_nav_atts['type'])) {
          $mob_type = $mobile_nav_atts['type'];
        }

        if(isset($mobile_nav_atts['nav_args'])) {
          $nav_args = $mobile_nav_atts['nav_args'];

          $nav_args['menu_class'] = 'menu container';
        }

        $hover_type = '';
        if(isset($mobile_nav_atts['hover_type'])) {
          $hover_type = ' '.$mobile_nav_atts['hover_type'];
        }

        $iniq_id = '';
        if(isset($mobile_nav_atts['uniq_id'])) {
          $iniq_id = $mobile_nav_atts['uniq_id'];
        }
      if($mob_type != 'full_screen') { ?>
      <div class="mobile-navigation-block<?php echo yprm_implode($mob_header_css) ?>">
        <nav class="mobile-navigation <?php echo $iniq_id.$hover_type ?>">
          <?php echo wp_nav_menu($nav_args); ?>
        </nav>
        <?php echo $this->renderRow('mobile_bottom_bar') ?>
      </div>
			<?php }
		}

		public function buildCSS($name, $args = array()) {
			$css_code = [];

			if($name == 'desktop_top_bar') {
				$css_class = 'body .'.$this->header_css.'.site-header-top:not(.mobile-type)';
			} elseif($name == 'desktop_main_bar') {
				$css_class = 'body .'.$this->header_css.':not(.mobile-type) .header-main-block';
			} elseif($name == 'desktop_bottom_bar') {
				$css_class = 'body .'.$this->header_css.':not(.mobile-type) .header-bottom-block';
			} elseif($name == 'mobile_top_bar') {
				$css_class = 'body .'.$this->header_css.'.mobile-type.site-header-top';
			} elseif($name == 'mobile_main_bar') {
				$css_class = 'body .'.$this->header_css.'.mobile-type .header-main-block';
			} elseif($name == 'mobile_bottom_bar') {
				$css_class = 'body .'.$this->header_css.'.mobile-type .header-bottom-block';
			} else {
				$css_class = '.'.$this->header_css.' .'.yprm_kebab_case($name);
			}

			if(is_object($args) && !empty($args)) {
				foreach($args as $param => $value) {
					if(!$value) continue;

					if(
						$param == 'font_size'
					) {
						$css_code[] = "font-size: $value;";
					}

					if($param == 'align') {
						if($value == 'left') {
							$value = 'flex-start';
						} else if($value == 'right') {
							$value = 'flex-end';
						}
						$css_code[] = "justify-content: $value;";
					}

					if($param == 'bg_sticky_color') {
						if($name == 'desktop_main_bar') {
							yprm_buildCSS("body .$this->header_css.fixed-header.fixed:not(.mobile-type):not(.mobile-type) .header-main-block", array(
								'background-color' => $value
							));
						} elseif ( $name == 'mobile_main_bar') {
							yprm_buildCSS("body .$this->header_css.fixed.mobile-type .header-main-block", array(
								'background-color' => $value
							));
						} else {
							$css_code[] = "background-color: $value !important;";
						}
					}

					if($param == 'bg_color') {
						if($name == 'desktop_main_bar') {
							yprm_buildCSS("body .$this->header_css:not(.fixed):not(.mobile-type) .header-main-block", array(
								'background-color' => $value
							));
						} else {
							$css_code[] = "background-color: $value !important;";
						}
					}

					if($param == 'color') {
						$css_code[] = "color: $value;";
					}

					if($param == 'height') {
						$css_code[] = "height: $value;";
					}

					if($param == 'min_height') {
						$css_code[] = "min-height: $value;";

            yprm_buildCSS(".{$this->header_css} .header-main-block .navigation>ul>li>a", array(
              'height' => ((int) $value-22).'px'
            ));
					}

					if($param == 'border_width') {
						$css_code[] = "border-bottom: $value solid;";
					}
					
					if($param == 'border_color') {
						$css_code[] = "border-color: $value;";
					}

					if($param == 'min_height_on_scroll' && $name == 'desktop_main_bar') {
            
						yprm_buildCSS(".{$this->header_css}.fixed:not(.mobile-type) .header-main-block", array(
							'min-height' => $value
						));

            yprm_buildCSS(".{$this->header_css}.fixed:not(.mobile-type) .header-main-block .navigation>ul>li>a", array(
							'height' => ((int) $value-22).'px'
						));
					}

					if($param == 'min_height_on_scroll' && $name == 'mobile_main_bar') {
            
						yprm_buildCSS(".{$this->header_css}.fixed.mobile-type .header-main-block", array(
							'min-height' => $value
						));
					}
				}

				do_action('yprm_inline_css', "$css_class {".yprm_implode($css_code, '')."}");
			}
		}
	}
}