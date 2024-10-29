<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Dimensions;

class Elementor_Role_Api_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'role_api_widget';
    }

    public function get_title()
    {
        return __('Role API Widget', 'eraw');
    }

    public function get_icon()
    {
        return 'eicon-post-list';
    }

    public function get_categories()
    {
        return [ 'general' ];
    }

    // Retrieve taxonomy name using the ID
    private function get_taxonomy_name_by_id($taxonomy_url, $id)
    {
        $response = wp_remote_get($taxonomy_url . '/' . $id);

        if (is_wp_error($response)) {
            return '';
        }

        $taxonomy_data = json_decode(wp_remote_retrieve_body($response), true);

        return !empty($taxonomy_data['name']) ? $taxonomy_data['name'] : '';
    }

    private function get_taxonomy_slug_by_id($taxonomy_url, $id)
    {
        $response = wp_remote_get($taxonomy_url . '/' . $id);

        if (is_wp_error($response)) {
            return '';
        }

        $taxonomy_data = json_decode(wp_remote_retrieve_body($response), true);

        return !empty($taxonomy_data['slug']) ? $taxonomy_data['slug'] : '';
    }

    protected function _register_controls()
    {
        // CONTENT SECTION
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'eraw'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_job_description',
            [
                'label' => __('Show Job Description', 'eraw'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'show_responsibilities',
            [
                'label' => __('Show Responsibilities', 'eraw'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'show_required_skills',
            [
                'label' => __('Show Required Skills', 'eraw'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'show_what_we_offer',
            [
                'label' => __('Show What We Offer', 'eraw'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'show_department',
            [
                'label' => __('Show Department', 'eraw'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'group_by_department',
            [
                'label' => __('Group by Department', 'eraw'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'show_department' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_kind',
            [
                'label' => __('Show Kind', 'eraw'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_location',
            [
                'label' => __('Show Location', 'eraw'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Layout Tab
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'eraw'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'card_gap_rows',
            [
            'label' => __('Gap Between Cards - Rows', 'eraw'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 10,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .department-group' => '--gap-rows: {{SIZE}}{{UNIT}};',
            ],
        ]
        );

        $this->add_responsive_control(
            'card_gap_columns',
            [
            'label' => __('Gap Between Cards - Columns', 'eraw'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 10,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .department-group' => '--gap-columns: {{SIZE}}{{UNIT}};',
            ],
        ]
        );

        $this->add_responsive_control(
            'role_item_padding',
            [
                'label' => __('Padding', 'eraw'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .card-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        //STYLE SECTION

        // Cards Tab
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Cards', 'eraw'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'type' => Controls_Manager::CHOOSE,
                'label' => esc_html__('Alignment', 'eraw'),
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'eraw'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'eraw'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'eraw'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'desktop_default' => 'left',
                'tablet_default' => 'left',
                'mobile_default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .card-title' => 'text-align: {{OPTIONS}};',
                    '{{WRAPPER}} .card-label' => 'display:flex; justify-content: {{OPTIONS}};',
                    '{{WRAPPER}} .alignment' => 'text-align: {{OPTIONS}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'department_alignment',
            [
                'type' => Controls_Manager::CHOOSE,
                'label' => esc_html__('Department Title Alignment', 'eraw'),
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'eraw'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'eraw'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'eraw'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .department-title' => 'text-align: {{OPTIONS}};',
                ],
                'condition' => [
                    'show_department' => 'yes',
                    'group_by_department' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__('Width', 'eraw'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'range' => [
                    'px' => [
                        'min' => 250,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 25,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .department-group' => '--width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        //Department Margin bottom
        $this->add_responsive_control(
            'department-margin',
            [
                'label' => esc_html__('Space after department', 'eraw'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'em',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .department-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_department' => 'yes',
                    'group_by_department' => 'yes',
                ],
            ]
        );

        //Tab Normal

        $this->start_controls_tabs(
            'card_tabs'
        );

        $this->start_controls_tab(
            'card_normal_tab',
            [
                'label' => esc_html__('Normal', 'eraw'),
            ]
        );

        // Background control
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'card_background',
                'label' => __('Card Background', 'eraw'),
                'types' => ['classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .card-link',
            ]
        );

        // Border control
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'label' => __('Card Border', 'eraw'),
                'selector' => '{{WRAPPER}} .card-link',
            ]
        );

        // Box Shadow control
        $this->add_group_control(
            Elementor\Group_Control_Box_Shadow::get_type(),
            array(
                'name' => 'card_shadow',
                'label' => __('Card Shadow', 'eraw'),
                'selector' => '{{WRAPPER}} .card-link',
            )
        );

        $this->end_controls_tab();

        //Tab Hover
        $this->start_controls_tab(
            'card_hover_tab',
            [
                'label' => esc_html__('Hover', 'eraw'),
            ]
        );


        // Background control
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'card_background_hover',
                'label' => __('Card Background', 'eraw'),
                'types' => ['classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .card-link:hover',
            ]
        );

        // Border control
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_border_hover',
                'label' => __('Card Border', 'eraw'),
                'selector' => '{{WRAPPER}} .card-link:hover',
            ]
        );

        // Box Shadow control
        $this->add_group_control(
            Elementor\Group_Control_Box_Shadow::get_type(),
            array(
                'name' => 'card_shadow_hover',
                'label' => __('Card Shadow', 'eraw'),
                'selector' => '{{WRAPPER}} .card-link:hover',
            )
        );

        //Transition Duration on Hover
        $this->add_control(
            'transition_duration_hover',
            [
                'label' => esc_html__('Transition Duration on Hover', 'eraw'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 's', 'ms', 'custom' ],
                'range' => [
                    's' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                    'ms' => [
                        'min' => 0,
                        'max' => 3000,
                        'step' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 's',
                    'size' => 0.4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .card-link' => 'transition-duration: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();

        // Title Tab
        $this->start_controls_section(
            'title_section',
            [
                'label' => __('Title', 'eraw'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        //Margin bottom
        $this->add_responsive_control(
            'title-margin',
            [
                'label' => esc_html__('Space after Title', 'eraw'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'em',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .department-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .card-title',
            ]
        );


        //Tab Normal

        $this->start_controls_tabs(
            'title_tabs'
        );

        $this->start_controls_tab(
            'title_normal_tab',
            [
                'label' => esc_html__('Normal', 'eraw'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'eraw'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .card-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        //Tab Hover
        $this->start_controls_tab(
            'title_hover_tab',
            [
                'label' => esc_html__('Hover', 'eraw'),
            ]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Title Color', 'eraw'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .card-link:hover .card-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Label Tab
        $this->start_controls_section(
            'label_section',
            [
                'label' => __('Label', 'eraw'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .card-label',
            ]
        );

        //Tab Normal

        $this->start_controls_tabs(
            'label_tabs'
        );

        $this->start_controls_tab(
            'label_normal_tab',
            [
                'label' => esc_html__('Normal', 'eraw'),
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Label Color', 'eraw'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .card-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        //Tab Hover
        $this->start_controls_tab(
            'label_hover_tab',
            [
                'label' => esc_html__('Hover', 'eraw'),
            ]
        );

        $this->add_control(
            'label_color_hover',
            [
                'label' => esc_html__('Label Color', 'eraw'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .card-link:hover .card-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();

        // Text Tab
        $this->start_controls_section(
            'text_section',
            [
                'label' => __('Text', 'eraw'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .card-text',
            ]
        );

        //Tab Normal

        $this->start_controls_tabs(
            'text_tabs'
        );

        $this->start_controls_tab(
            'text_normal_tab',
            [
                'label' => esc_html__('Normal', 'eraw'),
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'eraw'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .card-text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .card-text > p' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .card-text > div' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .card-text > ul > li' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        //Tab Hover
        $this->start_controls_tab(
            'text_hover_tab',
            [
                'label' => esc_html__('Hover', 'eraw'),
            ]
        );

        $this->add_control(
            'text_color_hover',
            [
                'label' => esc_html__('Text Color', 'eraw'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .card-link:hover .card-text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .card-link:hover .card-text > p' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .card-link:hover .card-text > div' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .card-link:hover .card-text > ul > li' => 'color: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();

    }

    protected function render()
    {
        // API URLs for taxonomies
        $department_api_url = 'https://ngp.careers/wp-json/wp/v2/department';
        $visibility_api_url = 'https://ngp.careers/wp-json/wp/v2/visibility';

        // API URL for roles
        $api_url = 'https://ngp.careers/wp-json/wp/v2/role';

        // Make the request
        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            echo esc_html__('Error fetching API data.', 'eraw');
            return;
        }

        $roles = json_decode(wp_remote_retrieve_body($response), true);

        if (empty($roles)) {
            echo esc_html__('No roles found.', 'eraw');
            return;
        }

        // Check if user wants to group by department
        $group_by_department = $this->get_settings_for_display('group_by_department') === 'yes';
        $show_department = $this->get_settings_for_display('show_department') === 'yes'; ?>

<div class="widget-wrapper">

    <?php
        if ($group_by_department) {
            // Group roles by department
            $grouped_roles = [];

            foreach ($roles as $role) {
                $department_id = $role['acf']['department'] ?? '';
                $department_name = $this->get_taxonomy_name_by_id($department_api_url, $department_id);

                $grouped_roles[$department_name][] = $role;
            }

            ksort($grouped_roles);

            foreach ($grouped_roles as $department_name => $roles_in_department) { ?>
    <div class="department-wrapper">
        <h2 class="department-title"><?= esc_html($department_name)?>
        </h2>
        <div class="department-group">

            <?php
                foreach ($roles_in_department as $role) {
                    $this->render_role($role, $show_department);
                }
                ?>
        </div>
    </div>
    <?php
            }
        } else {
            if ($show_department) {
                usort($roles, function ($a, $b) {
                    return strcmp($a['title']['rendered'], $b['title']['rendered']);
                });
            }
            ?>

    <div class="department-wrapper">
        <div class="department-group">
            <?php
                    foreach ($roles as $role) {
                        $this->render_role($role, $show_department);
                    }
            ?>
        </div>
    </div>
    <?php
        } ?>
</div>
<?php }

    private function render_role($role, $show_department)
    {

        $kind_api_url = 'https://ngp.careers/wp-json/wp/v2/kind';
        $location_api_url = 'https://ngp.careers/wp-json/wp/v2/location';

        $title = $role['title']['rendered'];
        $job_description = $role['acf']['job_description'] ?? '';
        $responsibilities = $role['acf']['responsibilities'] ?? '';
        $required_skills = $role['acf']['required_skills'] ?? '';
        $what_we_offer = $role['acf']['what_we_offer'] ?? '';
        $role_link = $role['acf']['role_link'] ?? '';

        $get_show_department = $this->get_settings_for_display('show_department');
        $get_group_by_department = $this->get_settings_for_display('group_by_department');
        $department = $show_department ? $this->get_taxonomy_name_by_id('https://ngp.careers/wp-json/wp/v2/department', $role['acf']['department']) : '';
        $kind = !empty($role['acf']['kind']) ? $this->get_taxonomy_name_by_id($kind_api_url, $role['acf']['kind']) : '';
        $location = !empty($role['acf']['location']) ? $this->get_taxonomy_name_by_id($location_api_url, $role['acf']['location'][0]) : '';

        ?>


<?php if (!empty($role_link)) : ?>
<a class="card-link" href="<?= esc_url($role_link) ?>"
    target="_blank">
    <?php endif; ?>
    <div class="card-item">
        <h3 class="card-title alignment"><?= esc_html($title) ?></h3>

        <!-- Job description -->
        <?php if ($this->get_settings_for_display('show_job_description') === 'yes') : ?>
        <div class="card-group">
            <span
                class="card-label alignment"><?= esc_html__('Job Description: ', 'eraw') ?></span>
            <p class="card-text alignment">
                <?= wp_kses_post($job_description) ?>
            </p>
        </div>
        <?php endif; ?>

        <!-- Responsibilities -->
        <?php if ($this->get_settings_for_display('show_responsibilities') === 'yes') : ?>
        <div class="card-group">
            <span
                class="card-label alignment"><?= esc_html__('Responsibilities: ', 'eraw') ?></span>
            <p class="card-text alignment">
                <?= wp_kses_post($responsibilities) ?>
            </p>
        </div>
        <?php endif; ?>

        <!-- Required Skills -->
        <?php if ($this->get_settings_for_display('show_required_skills') === 'yes') : ?>
        <div class="card-group">
            <span
                class="card-label alignment"><?= esc_html__('Required Skills: ', 'eraw') ?></span>
            <p class="card-text alignment">
                <?= wp_kses_post($required_skills) ?>
            </p>
        </div>
        <?php endif; ?>


        <!-- What We Offer -->
        <?php if ($this->get_settings_for_display('show_what_we_offer') === 'yes') : ?>
        <div class="card-group">
            <span
                class="card-label alignment"><?= esc_html__('What We Offer: ', 'eraw') ?></span>
            <p class="card-text alignment">
                <?= wp_kses_post($what_we_offer) ?>
            </p>
        </div>
        <?php endif; ?>

        <!-- Department -->
        <?php
        if ($get_group_by_department === '' && $get_show_department === 'yes') : ?>
        <div class="card-group">
            <span
                class="card-label alignment"><?= esc_html__('Department: ', 'eraw') ?></span>
            <p class="card-text alignment">
                <?= esc_html($department) ?>
            </p>
        </div>
        <?php endif; ?>

        <!-- Kind -->
        <?php if ($this->get_settings_for_display('show_kind') === 'yes') : ?>
        <div class="card-group">
            <span
                class="card-label alignment"><?= esc_html__('Kind: ', 'eraw')?></span>
            <p class="card-text alignment">
                <?= esc_html($kind)?>
            </p>
        </div>
        <?php endif; ?>

        <!-- Location -->
        <?php if ($this->get_settings_for_display('show_location') === 'yes') : ?>
        <div class="card-group">
            <span
                class="card-label alignment"><?= esc_html__('Location: ', 'eraw')?></span>
            <p class="card-text alignment">
                <?= esc_html($location)?>
            </p>
        </div>
        <?php endif; ?>




    </div>
    <?php if (!empty($role_link)) : ?>
</a>
<?php endif; ?>


<?php }
    }


// Add default/custom styles
function elementor_role_api_widget_styles()
{
    ?>
<style>
    <?php include_once(__DIR__ . '/../sass/style.min.css');
    ?>
</style>
<?php
}
add_action('wp_head', 'elementor_role_api_widget_styles');
