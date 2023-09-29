<?php
/**
 * Bricks Builder support
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Prefix_Element_Test extends \Bricks\Element {

    public $category     = 'woocommerce'; // Use predefined element category 'general'
    public $name         = 'aws-search-form'; // Make sure to prefix your elements
    public $icon         = 'ti-search'; // Themify icon font class
    public $css_selector = '.aws-search-form-wrapper'; // Default CSS selector

    public function get_label() {
        return esc_html__( 'Advanced Woo Search', 'advanced-woo-search' );
    }

    public function set_control_groups() {

        $this->control_groups['settings'] = [
            'title' => esc_html__( 'Settings', 'advanced-woo-search' ),
            'tab' => 'content',
        ];
    }

    // Set builder controls
    public function set_controls() {

        $plugin_options = get_option( 'aws_pro_settings' );
        $form_ids = array();
        if ( $plugin_options ) {
            foreach ( $plugin_options as $instance_id => $instance_options ) {
                $form_ids[$instance_id] = $instance_id;
            }
        }

        $this->controls['placeholder'] = array(
            'tab' => 'content',
            'group' => 'settings',
            'label' => esc_html__( 'Placeholder', 'advanced-woo-search' ),
            'type' => 'text',
            'default' => '',
        );

        $this->controls['form_id'] = array(
            'tab' => 'content',
            'group' => 'settings',
            'label' => esc_html__( 'Form ID:', 'advanced-woo-search'),
            'type' => 'select',
            'options' => $form_ids,
            'inline' => true,
            'clearable' => false,
            'pasteStyles' => false,
            'default' => 1,
        );

    }

    public function enqueue_scripts() {
    }

    // Render element HTML
    public function render() {

        $root_classes[] = 'aws-search-form-wrapper';

        $this->set_attribute( '_root', 'class', $root_classes );

        echo "<div {$this->render_attributes( '_root' )}>";

        if ( function_exists( 'aws_get_search_form' ) ) {
            $args = isset( $this->settings['placeholder'] ) && $this->settings['placeholder'] ? array( 'placeholder' => $this->settings['placeholder'] ) : array();
            $args['id'] = isset( $this->settings['form_id'] ) && $this->settings['form_id'] ? $this->settings['form_id'] : 1;
            $search_form = aws_get_search_form( false, $args );
            echo $search_form;
        }

        echo '</div>';

    }
}