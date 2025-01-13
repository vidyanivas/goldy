<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Elementor_Gallery_Widget extends \Elementor\Widget_Base
{
    public $displacement_url;
    protected $settings;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->displacement_url = YPRM_PLUGINS_HTTP . '/assets/imgs/pattern4.jpg';

        wp_register_script('gallery-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/gallery.js', array('jquery', 'isotope', 'elementor-frontend'), '', true);
    }

    public function get_name()
    {
        return 'yprm_gallery';
    }

    public function get_title()
    {
        return esc_html__('Gallery', 'pt-addons');
    }

    public function get_icon()
    {
        return 'pt-el-icon-gallery';
    }

    public function get_script_depends()
    {
        if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
            return ['isotope', 'swiper11', 'gallery-handler'];
        }

        $scripts = [];
        $type = $this->get_settings_for_display('gallery_layout');

        if ($type === 'slider') {
            $scripts[] = 'swiper';
        } elseif ($type == 'masonry') {
            $scripts[] = 'isotope';
        }

        $scripts[] = 'gallery-handler';
        return $scripts;
    }

    public function get_style_depends()
    {
        if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
            return ['swiper'];
        }

        $styles = [];
        $type = $this->get_settings_for_display('gallery_layout');

        if ($type === 'slider') {
            $styles[] = 'swiper';
        }

        return $styles;
    }

    public function get_categories()
    {
        return ['novo-elements'];
    }

    protected function register_controls()
    {
        $this->start_controls_section('settings', ['label' => __('Settings', 'pt-addons')]);

        $this->add_control(
            'gallery',
            [
                'type' => Controls_Manager::GALLERY,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'gallery_layout',
            [
                'type' => Controls_Manager::SELECT,
                'label' => __('Layout', 'pt-addons'),
                'default' => 'grid',
                'options' => [
                    'grid' => __('Grid', 'pt-addons'),
                    'slider' => __('Slider', 'pt-addons'),
                    'masonry' => __('Masonry', 'pt-addons'),
                ],
                'separator' => 'before',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'content_hover_animation',
            [
                'label' => __('Hover Animation', 'pt-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    "none" => esc_html__("Content always is shown", "novo"),
                    "type_1" => esc_html__("Type 1", "novo"),
                    "type_2" => esc_html__("Type 2", "novo"),
                    "type_3" => esc_html__("Type 3", "novo"),
                    "type_4" => esc_html__("Type 4", "novo"),
                    "type_5" => esc_html__("Type 5", "novo"),
                    "type_6" => esc_html__("Type 6", "novo"),
                    "type_7" => esc_html__("Type 7", "novo"),
                    "type_8" => esc_html__("Type 8", "novo"),
                    "type_9" => esc_html__("Type 9", "novo"),
                ],
                'default' => 'type_1',
                'frontend_available' => true,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'gallery_layout',
                            'operator' => 'in',
                            'value' => ['grid', 'masonry'],
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'hover_disable',
            [
                'label' => __('Hover disable', 'novo'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => '',
                'default' => '',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'gallery_layout',
                            'operator' => 'in',
                            'value' => ['grid', 'masonry'],
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'slider_thumbs',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Thumbs', 'pt-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'gallery_layout' => 'slider',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Cols::get_type(),
            [
                'name' => 'cols',
                'label' => esc_html__('Cols', 'pt-addons'),
                'frontend_available' => true,
                'condition' => [
                    'gallery_layout!' => 'slider',
                ],
            ]
        );



        $this->add_control(
            'slider_image_type',
            [
                'label' => esc_html__('Images Type', 'pt-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'cropped' => esc_html__('Cropped', 'pt-addons'),
                    'original' => esc_html__('Original', 'pt-addons'),
                ],
                'default' => 'cropped',
                'condition' => [
                    'gallery_layout' => 'slider',
                ],
            ]
        );

        $this->end_controls_section(); // settings

        $this->start_controls_section(
            'slider_settings_section',
            [
                'label' => __('Slider Settings', 'novo'),
                'condition' => [
                    'gallery_layout' => 'slider',
                ],
            ]
        );

        $this->add_control(
            'carousel_nav',
            [
                'label' => __('Carousel navigation', 'novo'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'infinite_loop',
            [
                'label' => __('Infinite loop', 'novo'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mousewheel',
            [
                'label' => __('Mousewheel', 'novo'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => __('Transition speed', 'novo'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 300,
                "min" => 100,
                "max" => 10000,
                "step" => 100,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay Slides', 'novo'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed', 'novo'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5000,
                "min" => 100,
                "max" => 10000,
                "step" => 10,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'overlay_content_style',
            [
                'label' => __('Content', 'pt-addons'),
            ]
        );

        $this->add_control(
            'popup_mode_title',
            [
                'label' => __('Popup Mode Title', 'pt-addons'),
                'type' => Controls_Manager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'popup_mode_desc',
            [
                'label' => __('Popup Mode Description', 'pt-addons'),
                'type' => Controls_Manager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'show_heading',
            [
                'label' => __('Show Heading', 'pt-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label' => __('Show Description', 'pt-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section(); // overlay_content

        $this->start_controls_section(
            'pagination_settings_section',
            [
                'label' => __('Pagination Settings', 'novo'),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'gallery_layout',
                            'operator' => 'in',
                            'value' => ['grid', 'masonry'],
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => __('Navigation', 'novo'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    "none" => esc_html__("None", "novo"),
                    "load_more" => esc_html__("Load More", "novo"),
                    "load_more_on_scroll" => esc_html__("Load More On Scroll", "novo"),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'count_items',
            [
                'label' => __('Posts Per Page', 'novo'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 9,
            ]
        );

        $this->end_controls_section();
    }

    protected function set_css_classes()
    {

        $this->add_render_attribute(
            [
                'wrapper' => [
                    'data-portfolio-settings' => [
                        wp_json_encode(array_filter([
                            "loop" => ("yes" == $this->settings["infinite_loop"]) ? true : false,
                            "autoplay" => ("yes" == $this->settings["autoplay"]) ? true : false,
                            "arrows" => ("yes" == $this->settings["carousel_nav"]) ? true : false,
                            "mousewheel" => ("yes" == $this->settings["mousewheel"]) ? true : false,
                            "autoplay_speed" => $this->settings['autoplay_speed'],
                            "speed" => $this->settings['speed'],
                            'gallery_layout' => $this->settings['gallery_layout'],
                            'slider_thumbs' => $this->settings['slider_thumbs'],
                        ])),
                    ],
                ],
            ]
        );

        $this->add_render_attribute('wrapper', 'id', 'project-slider-' . uniqid());

        if ($this->settings['gallery_layout'] == 'slider') {
            $this->add_render_attribute('wrapper', 'class', 'project-slider-block elementor-block gallery-block');
        } else {
            $this->add_render_attribute('wrapper', 'class', 'project-grid-page elementor-block gallery-block');
            if ($this->settings['content_hover_animation']) {
                $this->add_render_attribute('inner-wrapper', 'class', 'portfolio_hover_' . $this->settings['content_hover_animation']);
            }

            $this->add_render_attribute('inner-wrapper', 'class', [
                'post-gallery-grid',
                'row',
                'popup-gallery',
            ]);

            $this->add_render_attribute('inner-wrapper', 'class', 'gallery-type-' . $this->settings['gallery_layout']);

            if ($this->settings['hover_disable'] == 'yes') {
                $this->add_render_attribute('inner-wrapper', 'class', 'hover-disable');
            }


            if ($this->settings['gallery_layout'] == 'grid') {
                $this->add_render_attribute('inner-wrapper', 'class', 'disable-iso');
            }

            $cols = yprm_el_cols($this->settings);

            $this->add_render_attribute('item-wrapper', 'class', [
                'portfolio-item',
                'popup-item',
            ]);

            $this->add_render_attribute('item-wrapper', 'class', $cols);
        }
    }

    protected function render()
    {
        $this->settings = $this->get_settings_for_display();

        if (!$this->settings['gallery'])
            return;

        $this->set_css_classes();

        ob_start();
        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <?php wp_enqueue_script('pt-load-posts');

            if ($this->settings['gallery_layout'] == 'grid' || $this->settings['gallery_layout'] == 'masonry'): ?>
                <div <?php echo $this->get_render_attribute_string('inner-wrapper') ?>>
                    <?php $this->render_items(); ?>
                </div>
            <?php else: ?>
                <div class="slider swiper">
                    <div class="swiper-wrapper">
                        <?php $this->render_slider_items(); ?>
                    </div>
                </div>
                <?php if ($this->settings['slider_thumbs']): ?>
                    <?php $this->render_thumbs(); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php echo ob_get_clean();
    }

    protected function render_slider_items()
    {
        $gallery_items = [];
        foreach ($this->settings['gallery'] as $index => $item) {
            $img_array = wp_get_attachment_image_src($item['id'], 'large');
            $img_full_array = wp_get_attachment_image_src($item['id'], 'full');
            $img_info = get_post($item['id']);
            $img_html = wp_get_attachment_image($item['id'], 'large');
            ?>
            <?php if ($this->settings['slider_image_type'] == 'cropped') { ?>
                <div class="swiper-slide full-height" style="<?php echo esc_attr(yprm_get_image($item['id'], 'bg')) ?>">
                <?php } else { ?>
                    <div class="swiper-slide full-height">
                        <?php echo wp_kses($img_html, 'post') ?>
                    <?php } ?>
                    <?php if ($this->settings['show_heading'] == 'yes' || $this->settings['show_description'] == 'yes'): ?>
                        <div class="content">
                            <?php if ($this->settings['show_heading'] == 'yes'): ?>
                                <h6><?php echo esc_html($img_info->post_title) ?></h6>
                            <?php endif;
                            if ($this->settings['show_description'] == 'yes' && function_exists('mb_strimwidth')) {
                                $desc = mb_strimwidth($img_info->post_content, 0, 45, '...');
                                ?>
                                <p><?php echo strip_tags($img_info->post_content) ?></p>
                            <?php } ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php }
    }

    protected function render_items()
    {

        $gallery_items = [];
        $i1 = 1;
        $key2 = 0;

        echo '<div class="grid-sizer col-1"></div>';

        foreach ($this->settings['gallery'] as $index => $item) {
            $img_array = wp_get_attachment_image_src($item['id'], 'large');
            $img_full_array = wp_get_attachment_image_src($item['id'], 'full');
            $img_html = wp_get_attachment_image($item['id'], 'large');
            $img_info = get_post($item['id']);

            if (!isset($img_info->ID))
                continue;

            $index_num = $index + 1;
            $img_info = array(
                'id' => $img_info->ID,
                'title' => $img_info->post_title,
                'desc' => $img_info->post_content
            );

            $popup_array = [];

            $popup_array['image'] = [
                'url' => $img_full_array[0],
                'w' => $img_full_array[1],
                'h' => $img_full_array[2]
            ];

            $popup_array['post_id'] = $item['id'];

            if ($this->settings['popup_mode_title'] == 'yes' && !empty($img_info['title'])) {
                $popup_array['title'] = $img_info['title'];
            }

            if ($this->settings['popup_mode_desc'] == 'yes' && !empty($img_info['desc'])) {
                $popup_array['desc'] = mb_strimwidth($img_info['desc'], 0, yprm_get_theme_setting('popup_desc_size'), '...');
            }

            if ($this->settings['navigation'] != 'none' && $this->settings['count_items'] > 0 && count($this->settings['gallery']) > $this->settings['count_items'] && $this->settings['count_items'] == $index): ?>
                </div>
                <div class="load-items load-items<?php echo esc_html($i1); ?>">
                    <?php
                    $key2 = $index;
                    $i1++;
            endif;
            if ($this->settings['navigation'] != 'none' && $this->settings['count_items'] > 0 && count($this->settings['gallery']) > $this->settings['count_items'] && $this->settings['count_items'] + $key2 == $index): ?>
                </div>
                <div class="load-items load-items<?php echo esc_html($i1); ?>">
                    <?php
                    $key2 = $index;
                    $i1++;
            endif; ?>

                <div <?php echo $this->get_render_attribute_string('item-wrapper') ?>>
                    <div class="wrap">
                        <?php if ($this->settings['gallery_layout'] == 'masonry'): ?>
                            <div class="a-img" data-original="<?php echo esc_attr($img_full_array[0]); ?>">
                                <?php echo wp_kses_post($img_html); ?></div>
                        <?php else: ?>
                            <div class="a-img" data-original="<?php echo esc_attr($img_full_array[0]); ?>">
                                <div style="background-image: url(<?php echo esc_url($img_array[0]); ?>)"></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->settings['show_heading'] == 'yes' || $this->settings['show_description'] == 'yes'): ?>
                            <div class="content">
                                <?php if ($this->settings['content_hover_animation'] == 'type_5') {
                                    echo '<h5><span>' . yprm_lead_zero($index_num) . '</span> ' . esc_html($img_info->post_title) . '</h5>';
                                } else {
                                    echo '<h5>' . esc_html($img_info->post_title) . '</h5>';
                                }

                                if ($this->settings['show_description'] == 'yes' && function_exists('mb_strimwidth')) {
                                    $desc = mb_strimwidth($img_info->post_content, 0, 45, '...');
                                    ?>
                                    <p><?php echo strip_tags($img_info->post_content) ?></p>
                                <?php } ?>
                            </div>
                        <?php endif; ?>

                        <a href="#" data-popup-json="<?php echo esc_attr(json_encode($popup_array)) ?>"
                            data-id="<?php echo esc_attr($index) ?>"></a>
                    </div>
                </div>
                <?php
        } ?>

            <?php if ($this->settings['navigation'] != 'none' && $this->settings['count_items'] > 0 && count($this->settings['gallery']) > $this->settings['count_items']) { ?>
            </div>
            <div class="project-image-load-button tac">
                <a href="#" class="button-style1 <?php echo esc_attr($this->settings['navigation']); ?>">
                    <span><?php echo yprm_get_theme_setting('tr_load_more') ?></span>
                </a>
            </div>
        <?php } ?>
    <?php
    }

    protected function render_thumbs()
    { ?>
        <div class="thumbs swiper">
            <div class="prev ionicons-chevron-back-outline"></div>
            <div class="next ionicons-chevron-forward-outline"></div>
            <div class="swiper-wrapper">
                <?php foreach ($this->settings['gallery'] as $index => $item): ?>
                    <div class="swiper-slide" style="<?php echo esc_attr(yprm_get_image($item['id'], 'bg')); ?>"></div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php }
}
