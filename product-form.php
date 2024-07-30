<?php
/*
Plugin Name: WooCommerce Product Form
Plugin URI: https://plife.se/
Description: This is adding custom product form 
Version: 1.0
Author: Plife
Author URI: https://plife.se
License: GPL2
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class CustomProductForm
{

    public function __construct()
    {
        add_filter('woocommerce_product_data_tabs', array($this, 'wc_add_custom_product_data_tab'));
        add_action('woocommerce_product_data_panels', array($this, 'wc_add_custom_product_field'));
        add_action('woocommerce_process_product_meta', array($this, 'wc_save_custom_product_form'));
        add_action('woocommerce_single_product_summary', array($this, 'wc_display_custom_field_on_product_page'));
    }


    function wc_add_custom_product_data_tab($tabs)
    {
        $tabs['custom_data_tab'] = array(
            'label'    => __('Product Form', 'woocommerce'),
            'target'   => 'custom_product_form_data',
            'class'    => array(),
            'priority' => 21,
        );
        return $tabs;
    }

    // Register the custom field
    function wc_add_custom_product_field()
    {
        global $post;

        // Load current field value
        $custom_field_value = get_post_meta($post->ID, '_custom_product_form', true);

        // Output the content of the custom tab
        echo '<div id="custom_product_form_data" class="panel woocommerce_options_panel hidden">';
        echo '<div class="options_group">';
        woocommerce_wp_textarea_input(
            array(
                'id'          => '_custom_product_form',
                'label'       => __('Product Form', 'woocommerce'),
                'placeholder' => 'Enter text here...',

                'desc_tip'    => 'false',
                'value'       => $custom_field_value,
                "style" => "height:500px !important;width:100%"
            )
        );
        echo '</div>';
        echo '</div>';
    }


    // Save the custom field
    function wc_save_custom_product_form($post_id)
    {
        $custom_field_value = isset($_POST['_custom_product_form']) ? $_POST['_custom_product_form'] : '';
        update_post_meta($post_id, '_custom_product_form', $custom_field_value);
    }

    // Display custom field value on the frontend (optional)
    function wc_display_custom_field_on_product_page()
    {
        global $post;
        $custom_field_value = get_post_meta($post->ID, '_custom_product_form', true);
        if (!empty($custom_field_value)) {
            echo '<div class="custom-product-form-field">' . $custom_field_value . '</div>';
        }
    }
}

new CustomProductForm();
