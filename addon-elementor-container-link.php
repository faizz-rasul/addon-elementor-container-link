<?php
/*
Plugin Name: Addon Elementor Container Link
Plugin URI: https://downloads.wordpress.org/plugin/addon-elementor-container-link
Description: Enhance Elementor with our plugin's new feature: anchor links for clickable sections and containers. Elevate user experience and navigation on your website instantly
Version: 1.0
Author: Faiz R
Author URI: https://github.com/faizz-rasul
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: addon-elementor-container-link
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// load text domain
function fr_add_container_link_load_textdomain() {
    load_plugin_textdomain( 'addon-elementor-container-link', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'fr_add_container_link_load_textdomain' );

// Hook into Elementor's section & container structure settings
add_action( 'elementor/element/section/section_layout/after_section_end', 'fr_add_container_link_option' );
add_action( 'elementor/element/container/section_layout/after_section_end', 'fr_add_container_link_option' );

function fr_add_container_link_option( $element ) {
    $element->start_controls_section(
        'section_container_link',
        [
            'label' => __( 'Container Link', 'addon-elementor-container-link' ),
            'tab'   => \Elementor\Controls_Manager::TAB_LAYOUT,
        ]
    );

    $element->add_control(
        'container_link',
        [
            'label'       => __( 'Container Link', 'addon-elementor-container-link' ),
            'type'        => \Elementor\Controls_Manager::URL,
            'placeholder' => __( 'https://your-link.com', 'addon-elementor-container-link' ),
            'default'     => [
                'url' => '',
            ],
        ]
    );

    $element->end_controls_section();
}

// Save and retrieve the link value
add_action( 'elementor/frontend/section/before_render', 'fr_render_container_link' );
add_action( 'elementor/frontend/container/before_render', 'fr_render_container_link' );

function fr_render_container_link( $element ) {
    $settings = $element->get_settings();
    $link     = $settings['container_link']['url'];

    if ( $link ) {
        ?>
        <script>
            jQuery( document ).ready( function( $ ) {
                $( '.elementor-element-<?php echo esc_attr( $element->get_id() ); ?>' ).css( 'cursor', 'pointer' ).click( function() {
                    window.location.href = '<?php echo esc_url( $link ); ?>';
                } );
            } );
        </script>
        <?php
    }
}
