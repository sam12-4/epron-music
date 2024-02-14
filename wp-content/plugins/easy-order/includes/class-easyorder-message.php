<?php

/**
 * Admin Class
 */
class EasyOrderMessage
{
    /**
     * Init class
     */
    function __construct()
    {

        add_action('init', array($this, 'message_endpoints'));

        add_filter('query_vars', array($this, 'message_query_vars'), 0);
        add_filter('woocommerce_account_menu_items', array($this, 'message_my_account_menu_items'), 10, 1);
        add_action('woocommerce_account_easyorder-message_endpoint', array($this, 'message_endpoint_content'));
        add_action('woocommerce_account_easyorder-view-message_endpoint', array($this, 'view_message_endpoint_content'));
    }

    /**
     * easyorder Message Endpoint
     *
     * @return null
     * @since 3.0.0
     */
    public static function message_endpoints()
    {
        add_rewrite_endpoint('easyorder-message', EP_ROOT | EP_PAGES);
        add_rewrite_endpoint('easyorder-view-message', EP_ALL);
    }

    function message_query_vars($vars)
    {
        $vars[] = 'easyorder-message';
        $vars[] = 'easyorder-view-message';
        return $vars;
    }

    function message_my_account_menu_items($items)
    {
        unset($items['customer-logout']);
        $items['easyorder-message'] = esc_html__('Message', 'easyorder');
        $items['customer-logout'] = esc_html__('Logout', 'easyorder');

        return $items;
    }

    function message_endpoint_content()
    {
        wc_get_template('myaccount/message.php', $args = array(), $template_path = '', EASYORDER_TEMPLATE_PATH);
    }

    function view_message_endpoint_content($message_id)
    {
        wc_get_template('myaccount/view-message.php', array(
            'message_id' => $message_id
        ), $template_path = '', EASYORDER_TEMPLATE_PATH);
    }
}

new EasyOrderMessage();
