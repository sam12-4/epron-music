<?php

/**
 * Admin Class
 */
class EasyOrderPostTypes
{
    /**
     * Init class
     */
    function __construct()
    {
        add_action('init', [$this, 'register_post_types'], 10, 1);
    }

     /**
     * Handle Post Type, Taxonomy, Term Meta
     *
     * @author RedQTeam
     * @version 1.0.0
     * @since 1.0.0
     */
    public function register_post_types()
    {
        $labels = array(
            'name'               => esc_html_x('Message', 'post type general name', 'easyorder'),
            'singular_name'      => esc_html_x('Message', 'post type singular name', 'easyorder'),
            'menu_name'          => esc_html_x('Message', 'admin menu', 'easyorder'),
            'name_admin_bar'     => esc_html_x('Message', 'add new on admin bar', 'easyorder'),
            'add_new'            => esc_html_x('Add New', 'easyorder_message', 'easyorder'),
            'add_new_item'       => esc_html__('Add New Message', 'easyorder'),
            'new_item'           => esc_html__('New Message', 'easyorder'),
            'edit_item'          => esc_html__('Edit Message', 'easyorder'),
            'view_item'          => esc_html__('View Message', 'easyorder'),
            'all_items'          => esc_html__('All Messages', 'easyorder'),
            'search_items'       => esc_html__('Search Message', 'easyorder'),
            'parent_item_colon'  => esc_html__('Parent Message:', 'easyorder'),
            'not_found'          => esc_html__('No Message found.', 'easyorder'),
            'not_found_in_trash' => esc_html__('No Message found in Trash.', 'easyorder')
        );

        $args = array(
            'labels'          => $labels,
            'description'     => esc_html__('Description.', 'easyorder'),
            'public'          => false,
            'show_ui'         => true,
            'show_in_menu'    => true,
            'query_var'       => true,
            'rewrite'         => array('slug' => 'easyorder_message'),
            'capability_type' => 'post',
            'menu_icon'       => 'dashicons-email-alt2',
            'has_archive'     => false,
            'hierarchical'    => true,
            'menu_position'   => 57,
            'supports'        => array(''),
            'map_meta_cap'    => true, //After disabling new qoute capabilities if this is not set then row actions are disabled. So no edit or trash will be availabe.
            'capabilities'    => array(
                'create_posts' => false  //Removing Add new quote capabilities
            ),
        );

        register_post_type('easyorder_message', $args);

        self::register_post_status();
    }

    /**
     * Post Status
     *
     * @return null
     * @since 1.0.0
     */
    public static function register_post_status()
    {
        $post_statuses = apply_filters(
            'woocommerce_easyorder_register_message_post_statuses',
            array(
                'easyorder-pending' => array(
                    'label' => esc_html_x('Pending', 'Message status', 'easyorder'),
                    'public' => false,
                    'protected' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop('Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>', 'easyorder')
                ),
                'easyorder-processing' => array(
                    'label' => esc_html_x('Processing', 'Message status', 'easyorder'),
                    'public' => false,
                    'protected' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop('Processing <span class="count">(%s)</span>', 'Processing <span class="count">(%s)</span>', 'easyorder')
                ),
                'easyorder-on-hold' => array(
                    'label' => esc_html_x('On Hold', 'Message status', 'easyorder'),
                    'public' => false,
                    'protected' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop('On Hold <span class="count">(%s)</span>', 'On Hold <span class="count">(%s)</span>', 'easyorder')
                ),
                'easyorder-accepted' => array(
                    'label' => esc_html_x('Accepted', 'Message status', 'easyorder'),
                    'public' => false,
                    'protected' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop('Accepted <span class="count">(%s)</span>', 'Accepted <span class="count">(%s)</span>', 'easyorder')
                ),
                'easyorder-completed' => array(
                    'label' => esc_html_x('Completed', 'Message status', 'easyorder'),
                    'public' => false,
                    'protected' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop('Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'easyorder')
                ),
                'easyorder-cancelled' => array(
                    'label' => esc_html_x('Cancelled', 'Message status', 'easyorder'),
                    'public' => false,
                    'protected' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop('Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'easyorder')
                ),
            )
        );

        foreach ($post_statuses as $post_status => $values) {
            register_post_status($post_status, $values);
        }
    }
}

new EasyOrderPostTypes();
