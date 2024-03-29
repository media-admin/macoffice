<?php
/**
 * WPBakery plugin integration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_WPBakery' ) && class_exists('WPBakeryShortCode') ) :

class AWS_WPBakery extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );
        add_shortcode( 'wpb_aws_search_form', array( $this, 'render_shortcode' ) );
    }

    public function create_shortcode() {

        $plugin_options = get_option( 'aws_pro_settings' );
        $form_ids = array();
        foreach ( $plugin_options as $instance_id => $instance_options ) {
            $form_ids[$instance_id] = $instance_id;
        }

        vc_map( array(
            'name'          => __('Search Form', 'advanced-woo-search'),
            'base'          => 'wpb_aws_search_form',
            'description'  	=> __( 'Plugin search form', 'advanced-woo-search'),
            'category'      => __( 'Advanced Woo Search', 'advanced-woo-search'),
            "class"		    => "vc_aws_form",
            "icon"		    => AWS_PRO_URL . 'assets/img/logo-small.png',
            "controls"	    => "full",
            'params' => array(

                array(
                    'type'          => 'textfield',
                    'holder'        => 'div',
                    'heading'       => __( 'Placeholder', 'advanced-woo-search' ),
                    'param_name'    => 'placeholder',
                    'value'         => __( 'Search', 'advanced-woo-search' ),
                    'description'   => '',
                ),

                array(
                    "type" => "dropdown",
                    "heading" => __( 'Form ID:', 'advanced-woo-search' ),
                    "param_name" => "form_id",
                    "value" => $form_ids,
                    "description" => ''
                ),

                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Element ID', 'advanced-woo-search' ),
                    'param_name'    => 'element_id',
                    'value'         => '',
                    'description'   => '',
                    'group'         => __( 'Extra', 'advanced-woo-search'),
                ),

                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Extra class name', 'advanced-woo-search' ),
                    'param_name'    => 'extra_class',
                    'value'         => '',
                    'description'   => '',
                    'group'         => __( 'Extra', 'advanced-woo-search'),
                ),

            ),
        ));

    }

    public function render_shortcode( $atts, $content, $tag ) {

        $atts = (shortcode_atts(array(
            'placeholder'   => '',
            'form_id'       => '1',
            'extra_class'   => '',
            'element_id'    => ''
        ), $atts));


        $placeholder = esc_html($atts['placeholder']);
        $form_id     = intval($atts['form_id']);
        $extra_class = esc_attr($atts['extra_class']);
        $element_id  = esc_attr($atts['element_id']);

        $output = '';

        if ( function_exists( 'aws_get_search_form' ) ) {
            $args = array();
            $args['id'] = $form_id;
            if ( $placeholder ) {
                $args['placeholder'] = $placeholder;
            }
            $search_form = aws_get_search_form( false, $args );
            $output = '<div class="aws-wpbakery-form ' . $extra_class . '" id="' . $element_id . '" >' . $search_form . '</div>';
        }

        return $output;

    }

}

new AWS_WPBakery();

endif;