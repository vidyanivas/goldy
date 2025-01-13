<?php

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

class Elementor_Google_MAP_Widget extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		if ( get_option( 'elementor_google_maps_api_key' ) ) {
			$args = [
	            'key' => get_option( 'elementor_google_maps_api_key' ),
	            'v' => 3,
	            'callback' => 'Function.prototype'
	        ];

	        wp_register_script( 'pt-googleapis', sprintf( 'https://maps.googleapis.com/maps/api/js?%s', http_build_query( $args ) ), ['jquery'], null, true );
		}
        
		wp_register_script( 'google-map-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/google-map.js', array( 'jquery', 'elementor-frontend' ), '', true );
	}

	public function get_title() {
		return esc_html__( 'Google Map', 'pt-addons' );
	}

	public function get_icon() {
		return 'pt-el-icon-google-map';
	}

	public function get_categories() {
		return [ 'novo-elements' ];
	}

	public function get_script_depends() {
		return [ 'pt-googleapis', 'google-map-handler' ];
	}

	public function get_name() {
		return 'yprm_google_map';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_map',
			[
				'label' => __( 'Map', 'pt-addons' ),
			]
		);

		$this->add_control(
			'map_lat',
			[
				'label' => __( 'Latitude', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '51.508742',
				'default' => '51.508742',
				'dynamic' => [ 'active' => true ]
			]
		);

		$this->add_control(
			'map_lng',
			[
				'label' => __( 'Longitude', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '-0.120850',
				'default' => '-0.120850',
				'separator' => true,
				'dynamic' => [ 'active' => true ]
			]
		);

		$this->add_control(
			'zoom',
			[
				'label' => __( 'Zoom Level', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 25,
					],
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'default'   => [
					'size' => 300,
				],
				'range' => [
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 40,
						'max' => 1440,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .google-map' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'map_type',
			[
				'label' => __( 'Map Type', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'roadmap' => __( 'Road Map', 'pt-addons' ),
					'satellite' => __( 'Satellite', 'pt-addons' ),
					'hybrid' => __( 'Hybrid', 'pt-addons' ),
					'terrain' => __( 'Terrain', 'pt-addons' ),
				],
				'default' => 'roadmap',
			]
		);

		$this->add_control(
			'custom_map_style',
			[
				'label' => __( 'Custom Map Style', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'description' => __('Add style from <a href="https://mapstyle.withgoogle.com/" target="_blank">Google Map Styling Wizard</a>. Copy and Paste the style in the textarea.'),
				'condition' => [
					'map_type' => 'roadmap',
				],
				'default'	=> '[
            {
                "featureType": "all",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "saturation": 36
                    },
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 40
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "visibility": "on"
                    },
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 16
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 20
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 17
                    },
                    {
                        "weight": 1.2
                    }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 20
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 21
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 17
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 29
                    },
                    {
                        "weight": 0.2
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 18
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 16
                    }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 19
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "lightness": 17
                    }
                ]
            }
        ]',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'pt-addons' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		$map_styles = $settings['custom_map_style'];
		$replace_code_content = strip_tags($map_styles);
        $new_replace_code_content = preg_replace('/\s/', '', $replace_code_content);

		if ( 0 === absint( $settings['zoom']['size'] ) ) {
			$settings['zoom']['size'] = 10;
		}

		$this->add_render_attribute('extended-google-map-for-elementor', 'data-google-map-style', $new_replace_code_content);

		$mapmarkers = [
			'lat'	=> $settings['map_lat'],
			'lng'	=> $settings['map_lng'],
		];

		?>
		<section class="map">
			<div id="google-map-<?php echo esc_attr( $this->get_id() ); ?>" class="google-map" <?php echo $this->get_render_attribute_string('extended-google-map-for-elementor'); ?> data-google-map-type="<?php echo $settings['map_type']; ?>" data-google-map-lat="<?php echo $settings['map_lat']; ?>" data-google-map-lng="<?php echo $settings['map_lng']; ?>" data-google-map-zoom="<?php echo $settings['zoom']['size']; ?>" data-google-locations='<?php echo json_encode($mapmarkers);?>'></div>
		</section>
	<?php
    }
}
