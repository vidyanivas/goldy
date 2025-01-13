<?php 

if (!defined('ABSPATH')) {
	exit;
}

if ( ! function_exists( 'novo_get_term_list' ) ) {
	function novo_get_term_list( $taxonomy ) {
		if ( empty( $taxonomy ) ) {
			return [];
		}

		$args = array(
			'hide_empty' => true,
		);

		$terms = get_terms( $taxonomy, $args );
		$result = [];
		$result[0] = "Select Term";

		if ( empty( $terms ) || is_wp_error(  $terms ) ) {
			return;
		}

		foreach( $terms as $term ) {
			if ( ! $term ) {
				continue;
			}

			$name = get_category_parents($term->term_id);
			$name = trim( $name, '/' );
			$result[ $term->term_id ] = 'ID [' . $term->term_id . '] '. $name;
		}
	
		return $result;
	}
}

if ( ! function_exists( 'novo_get_post_items' ) ) {
	function novo_get_post_items( $post_type ) {
		if ( empty( $post_type ) ) {
			return [];
		}

		$result = array();

	    $args = array(
	      'post_type' => $post_type,
	      'post_status' => 'publish',
	      'posts_per_page' => '10000',
	    );

	    $post_array = new WP_Query($args);
	    $result[0] = "";

		if (!empty($post_array->posts)) {
			foreach ($post_array->posts as $item) {
				$result[$item->ID] = ['ID [' . $item->ID . '] ' . $item->post_title];
			}
		}

	    return $result;
	}
}

if ( ! function_exists( 'pt_get_all_blog_category' ) ) {
  function pt_get_all_blog_category() {
    $taxonomy = 'category';
    $args = array(
      'hide_empty' => true,
    );

    $terms = get_terms($taxonomy, $args);
    $result = array();
    $result[0] = "";

    if (!empty($terms) && !is_wp_error($terms)) {
      foreach ($terms as $term) {
        $name = get_category_parents($term->term_id);
        $name = trim($name, '/');
        $result['ID [' . $term->term_id . '] ' . $name] = $term->term_id;
      }
    }

    return $result;
  }
}

if ( ! function_exists( 'pt_get_all_blog_items' ) ) {
  function pt_get_all_blog_items($param = 'All') {
    $result = array();

    $args = array(
      'post_type' => 'post',
      'post_status' => 'publish',
      'posts_per_page' => '10000',
    );

    $blog_array = new WP_Query($args);
    $result[0] = "";

    if (is_array($blog_array->posts) && !empty($blog_array->posts)) {
      foreach ($blog_array->posts as $item) {
        $result['ID [' . $item->ID . '] ' . $item->post_title] = $item->ID;
      }
    }

    return $result;
  }
}

if(!function_exists('yprm_el_cols')) {
	function yprm_el_cols($atts, $prefix = 'cols', $type = null) {
		$screensArray = [
			'xs' => [
				'size' => 200
			], 
			'sm' => [
				'size' => 576
			], 
			'md' => [
				'size' => 768
			], 
			'lg' => [
				'size' => 992
			], 
			'xl' => [
				'size' => 1200
			]
		];
		$result = [];
		
		foreach($screensArray as $screenSize => $screen) {
			if(isset($atts[$prefix.'_'.$screenSize]) && !empty($atts[$prefix.'_'.$screenSize])) {
				$value = $atts[$prefix.'_'.$screenSize];

				if($type == 'swiper') {
					$result[] = $screen['size'].': { slidesPerView: '.(12/$value).' },';
				} else {
					if($value == 5) {
						$cols = '5';
					} else {
						$cols = 12/$value;
					}

					if($screenSize == 'xs') {
						$result[] = 'col-'.$cols;
					} else {
						$result[] = 'col-'.$screenSize.'-'.$cols;
					}
				}
			}
		}

		if(!count($result)) {
			if($type == 'swiper') {
				return '0: { slidesPerView: 1 }';
			} else {
				return 'col';
			}
		} else {
			return yprm_implode($result, '');
		}
	}
}

if(!function_exists('yprm_el_link')) {
	function yprm_el_link($atts, $prefix = 'link') {
		if(isset($atts[$prefix.'_url']['url']) && isset($atts[$prefix.'_label']) && !empty($atts[$prefix.'_url']['url']) && !empty($atts[$prefix.'_label'])) {
			return [
				'url' => $atts[$prefix.'_url']['url'],
				'title' => $atts[$prefix.'_label'],
				'target' => $atts[$prefix.'_url']['is_external'] == 'on' ? '_blank' : '_self'
			];
		}

		return false;
	}
}

if(!function_exists('yprm_el_get_css_key')) {
	function yprm_el_get_css_key($obj) {
		if($obj->get_type() == 'repeater') {
			return '{{CURRENT_ITEM}}';
		}
		return '{{WRAPPER}}';
	}
}

if(!function_exists('yprm_el_heading_customize')) {
	function yprm_el_heading_customize($obj, $prefix = 'heading', $with_heading = true) {
		if($with_heading) {
			$obj->add_control(
				$prefix.'_hr',
				[
					'label' => yprm_title_case($prefix).esc_html__( ' Customizing', 'pt-addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						$prefix.'!' => ''
					],
				]
			);
		}
		

		$obj->add_control(
			$prefix.'_html_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1'  => esc_html__( 'H1', 'pt-addons' ),
					'h2'  => esc_html__( 'H2', 'pt-addons' ),
					'h3'  => esc_html__( 'H3', 'pt-addons' ),
					'h4'  => esc_html__( 'H4', 'pt-addons' ),
					'h5'  => esc_html__( 'H5', 'pt-addons' ),
					'h6'  => esc_html__( 'H6', 'pt-addons' ),
				],
				'default' => 'h2',
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => $prefix,
				'label' => esc_html__( 'Typography', 'pt-addons' ),
				'selector' => yprm_el_get_css_key($obj).' .heading-block .h',
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_color',
			[
				'label' => esc_html__( 'Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .h' => 'color: {{VALUE}}',
				],
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_gradient',
			[
				'label' => esc_html__( 'Gradient Color', 'pt-addons' ),
				'type' => \Elementor\Gradient_Control::GRADIENT,
				'frontend_available' => true,
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .h' => '-webkit-background-clip: text; color: transparent; background-image: {{GRADIENT}}',
				],
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_hr2',
			[
				'label' => esc_html__( 'Accent Words', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => $prefix.'_accent_typography',
				'label' => esc_html__( 'Typography', 'pt-addons' ),
				'selector' => yprm_el_get_css_key($obj).' .heading-block .h div span',
				'exclude' => ['text_decoration', 'line_height', 'letter_spacing', 'font_size'],
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_accent_color',
			[
				'label' => esc_html__( 'Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .h div span' => 'color: {{VALUE}}',
				],
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_accent_gradient',
			[
				'label' => esc_html__( 'Gradient Color', 'pt-addons' ),
				'type' => \Elementor\Gradient_Control::GRADIENT,
				'frontend_available' => true,
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .h div span, '.yprm_el_get_css_key($obj).' .heading-block .h div span i' => '-webkit-background-clip: text; color: transparent; background-image: {{GRADIENT}}',
					yprm_el_get_css_key($obj).' .heading-block.with-underline .h span:before' => 'box-shadow: none; background-image: {{GRADIENT}}',
				],
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_accent_underline',
			[
				'label' => esc_html__( 'Underline', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'pt-addons' ),
				'label_off' => esc_html__( 'No', 'pt-addons' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_accent_background',
			[
				'label' => esc_html__( 'Background', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'pt-addons' ),
				'label_off' => esc_html__( 'Hide', 'pt-addons' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					$prefix.'!' => '',
				],
			]
		);

		$obj->add_control(
			$prefix.'_accent_background_color',
			[
				'label' => esc_html__( 'Background', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .h div span:after' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					$prefix.'_accent_background' => 'yes',
				]
			]
		);

		$obj->add_control(
			$prefix.'_accent_background_text_color',
			[
				'label' => esc_html__( 'Background Text Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .h div span:after' => 'color: {{VALUE}}',
					yprm_el_get_css_key($obj).' .heading-block .h div span:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					$prefix.'_accent_background' => 'yes',
				]
			]
		);
	}
}

if(!function_exists('yprm_el_text_customize')) {
	function yprm_el_text_customize($obj, $prefix = 'text', $with_heading = true) {
		if($with_heading) {
			$obj->add_control(
				$prefix.'_hr',
				[
					'label' => yprm_title_case($prefix).esc_html__( ' Customizing', 'pt-addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						$prefix.'!' => ''
					],
				]
			);
		}
		

		$obj->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => $prefix,
				'label' => esc_html__( 'Typography', 'pt-addons' ),
				'selector' => yprm_el_get_css_key($obj).' .text',
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_color',
			[
				'label' => esc_html__( 'Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .text' => 'color: {{VALUE}}',
				],
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);
	}
}

if(!function_exists('yprm_el_sub_heading_customize')) {
	function yprm_el_sub_heading_customize($obj, $prefix = 'sub_heading', $with_heading = true) {
		if($with_heading) {
			$obj->add_control(
				$prefix.'_hr',
				[
					'label' => yprm_title_case($prefix).esc_html__( ' Customizing', 'pt-addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						$prefix.'!' => ''
					],
				]
			);
		}

		$obj->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => $prefix,
				'label' => esc_html__( 'Typography', 'pt-addons' ),
				'selector' => yprm_el_get_css_key($obj).' .heading-block .sub-h',
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_color',
			[
				'label' => esc_html__( 'Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .sub-h' => 'color: {{VALUE}}',
				],
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_background_type',
			[
				'label' => esc_html__( 'Background', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'None', 'pt-addons' ),
					'color' => esc_html__( 'Color', 'pt-addons' ),
					'gradient' => esc_html__( 'Gradient', 'pt-addons' ),
				],
				'default' => '',
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_background_color',
			[
				'label' => esc_html__( 'Background Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .sub-h:before' => 'background-color: {{VALUE}} !important',
				],
				'condition' => [
					$prefix.'!' => '',
					$prefix.'_background_type' => 'color'
				]
			]
		);

		$obj->add_control(
			$prefix.'_background_gradient',
			[
				'label' => esc_html__( 'Gradient', 'pt-addons' ),
				'type' => \Elementor\Gradient_Control::GRADIENT,
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .sub-h:before' => 'background-image: {{GRADIENT}} !important ',
				],
				'condition' => [
					$prefix.'!' => '',
					$prefix.'_background_type' => 'gradient'
				]
			]
		);

		$obj->add_control(
			$prefix.'_padding',
			[
				'label' => esc_html__( 'Padding', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .sub-h' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);

		$obj->add_control(
			$prefix.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .sub-h:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					$prefix.'!' => ''
				],
			]
		);
	}
}

if(!function_exists('yprm_el_sub_heading_icon_input_controls')) {
	function yprm_el_sub_heading_icon_input_controls($obj, $prefix = 'sub_heading', $with_heading = true) {
		if($with_heading) {
			$obj->add_control(
				$prefix.'_icon_type',
				[
					'label' => esc_html__( 'Icon', 'pt-addons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'None', 'pt-addons' ),
						'icons-library' => esc_html__( 'Icons Library', 'pt-addons' ),
						'media-library' => esc_html__( 'Media Library', 'pt-addons' ),
						'emoji' => esc_html__( 'Emoji', 'pt-addons' ),
					],
					'default' => '',
					'condition' => [
						$prefix.'!' => ''
					]
				]
			);
		}

		$obj->add_control(
			$prefix.'_icon',
			[
				'label' => yprm_title_case($prefix).esc_html__( ' Icon', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					$prefix.'!' => '',
					$prefix.'_icon_type' => 'icons-library'
				]
			]
		);

		$obj->add_control(
			$prefix.'_icon_image',
			[
				'label' => yprm_title_case($prefix).esc_html__( ' Icon Image', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					$prefix.'!' => '',
					$prefix.'_icon_type' => 'media-library'
				]
			]
		);

		$obj->add_control(
			$prefix.'_icon_emoji',
			[
				'label' => yprm_title_case($prefix).esc_html__( ' Emoji', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => [
					$prefix.'!' => '',
					$prefix.'_icon_type' => 'emoji'
				]
			]
		);
	}
}

if ( ! function_exists( 'yprm_title_case' ) ) {
	function yprm_title_case( $prefix ) {
		return $prefix;
	}
}

if(!function_exists('yprm_el_sub_heading_icon_customize')) {
	function yprm_el_sub_heading_icon_customize($obj, $prefix = 'sub_heading', $with_heading = true) {
		
		if($with_heading) {
			$obj->add_control(
				$prefix.'_icon_hr',
				[
					'label' => yprm_title_case($prefix).esc_html__( ' Icon Customizing', 'pt-addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						$prefix.'_icon_type!' => '',
						$prefix.'_icon!' => '',
					]
				]
			);
		}

		$obj->add_responsive_control(
			$prefix.'_icon_size',
			[
				'label' => esc_html__( 'Size', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'responsive' => true,
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .h-top i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					$prefix.'_icon_type!' => '',
				]
			]
		);

		$obj->add_control(
			$prefix.'_icon_color',
			[
				'label' => esc_html__( 'Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .h-top i' => 'color: {{VALUE}}',
				],
				'condition' => [
					$prefix.'_icon_type!' => '',
				]
			]
		);

		$obj->add_control(
			$prefix.'_icon_rotate',
			[
				'label' => esc_html__( 'Rotate', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
					]
				],
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .h-top i' => 'transform: rotate({{SIZE}}deg);',
				],
				'condition' => [
					$prefix.'_icon_type!' => '',
				]
			]
		);

		$obj->add_control(
			$prefix.'_icon_margin',
			[
				'label' => esc_html__( 'Margin', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					yprm_el_get_css_key($obj).' .heading-block .h-top i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					$prefix.'_icon_type!' => '',
				]
			]
		);
	}
}

if(!function_exists('yprm_el_link_button')) {
	function yprm_el_link_button($obj, $prefix = 'link', $with_heading = true) {
		if($with_heading) {
			$obj->add_control(
				$prefix.'_hr',
				[
					'label' => yprm_title_case($prefix),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
		}
		

		$obj->add_group_control(
			\Elementor\Group_Control_Link::get_type(),
			[
				'name' => $prefix,
				'label' => esc_html__('Link', 'pt-addons'),
			]
		);

		$obj->add_control(
			$prefix.'_has_icon',
			[
				'label' => esc_html__( 'With Icon', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'pt-addons' ),
				'label_off' => esc_html__( 'Hide', 'pt-addons' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					$prefix.'_url[url]!' => '',
					$prefix.'_label!' => '',
				]
			]
		);

		$obj->add_control(
			$prefix.'_icon',
			[
				'label' => esc_html__( 'Icon', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					$prefix.'_url[url]!' => '',
					$prefix.'_label!' => '',
					$prefix.'_has_icon' => 'yes',
				]
			]
		);
	}
}

if(!function_exists('yprm_el_link_button_customize')) {
	function yprm_el_link_button_customize($obj, $prefix = 'link', $with_heading = true) {
		if($with_heading) {
			$obj->add_control(
				$prefix.'_hr2',
				[
					'label' => yprm_title_case($prefix).esc_html__( ' Customize', 'pt-addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						$prefix.'_url[url]!' => '',
						$prefix.'_label!' => '',
					]
				]
			);
		}
		
		$obj->add_control(
			$prefix.'_icon_position',
			[
				'label' => esc_html__( 'Icon position', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'backward'  => esc_html__( 'Backward', 'pt-addons' ),
					'forward' => esc_html__( 'Forward', 'pt-addons' ),
				],
				'default' => 'backward',
				'condition' => [
					$prefix.'_url[url]!' => '',
					$prefix.'_label!' => '',
					$prefix.'_has_icon' => 'yes',
				]
			]
		);

		$obj->add_control(
			$prefix.'_type',
			[
				'label' => esc_html__( 'Type', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'filled'  => esc_html__( 'Filled', 'pt-addons' ),
					'bordered' => esc_html__( 'Bordered', 'pt-addons' ),
				],
				'default' => 'filled',
				'condition' => [
					$prefix.'_url[url]!' => '',
					$prefix.'_label!' => '',
				]
			]
		);

		$obj->add_control(
			$prefix.'_hover_type',
			[
				'label' => esc_html__( 'Hover', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'zoom-in'  => esc_html__( 'Zoom In', 'pt-addons' ),
					'circular-fill' => esc_html__( 'Circular Fill', 'pt-addons' ),
				],
				'default' => 'zoom-in',
				'condition' => [
					$prefix.'_url[url]!' => '',
					$prefix.'_label!' => '',
				]
			]
		);

		$obj->add_control(
			$prefix.'_label_hex',
			[
				'label' => esc_html__( 'Label Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .pre-'.$prefix.'-button' => 'color: {{VALUE}}',
				],
				'condition' => [
					$prefix.'_url[url]!' => '',
					$prefix.'_label!' => '',
				]
			]
		);

		$obj->add_control(
			$prefix.'_border_hex',
			[
				'label' => esc_html__( 'Border Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .pre-'.$prefix.'-button:not(:hover)' => 'border-color: {{VALUE}}; opacity: 1;',
				],
				'condition' => [
					$prefix.'_url[url]!' => '',
					$prefix.'_label!' => '',
					$prefix.'_type' => 'bordered',
				]
			]
		);

		$obj->add_control(
			$prefix.'_bg_hex',
			[
				'label' => esc_html__( 'Background Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .pre-'.$prefix.'-button:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					$prefix.'_url[url]!' => '',
					$prefix.'_label!' => '',
					$prefix.'_hover_type' => 'zoom-in',
				]
			]
		);

		$obj->add_control(
			$prefix.'_label_hover_hex',
			[
				'label' => esc_html__( 'Hover Label Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .pre-'.$prefix.'-button:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					$prefix.'_url[url]!' => '',
					$prefix.'_label!' => '',
				]
			]
		);

		$obj->add_control(
			$prefix.'_border_hover_hex',
			[
				'label' => esc_html__( 'Hover Border Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .pre-'.$prefix.'-button:hover' => 'border-color: {{VALUE}}; opacity: 1;',
				],
				'condition' => [
					$prefix.'_url[url]!' => '',
					$prefix.'_label!' => '',
					$prefix.'_type' => 'bordered',
				]
			]
		);

		/* $obj->add_control(
			$prefix.'_bg_hover_hex',
			[
				'label' => esc_html__( 'Background Hover Color', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					yprm_el_get_css_key($obj).' .pre-'.$prefix.'-button' => 'color: {{VALUE}}',
				],
				'condition' => [
					$prefix.'_url[url]!' => '',
					$prefix.'_label!' => '',
					$prefix.'_hover_type' => 'zoom-in',
				]
			]
		); */
	}
}