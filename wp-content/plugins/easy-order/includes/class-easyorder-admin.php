<?php

/**
 * Admin Class
 */
class EasyOrderAdmin
{
    /**
     * Init class
     */
    function __construct()
    {
        add_filter('add_menu_classes', array($this, 'bubble_count_number'));
        add_action('pre_get_posts', array($this, 'message_pre_get_posts'), 1);

        add_action('save_post', array($this, 'save_post'), 10, 2);

        add_filter('manage_easyorder_message_posts_columns', array($this, 'columns_easyorder_message_head'));
        add_action('manage_easyorder_message_posts_custom_column', array($this, 'columns_easyorder_message_content'), 10, 2);
        add_filter('page_row_actions', array($this, 'remove_row_actions'), 10, 2);

        add_action( 'woocommerce_product_options_general_product_data', [$this, 'add_price_tiers'] );

        add_action( 'woocommerce_process_product_meta', [$this, 'wc_product_custom_fields_save'] );
    }

    function wc_product_custom_fields_save($post_id) {
        $easyorder_pricetiers_quantity = !empty($_POST['easyorder_pricetiers_quantity']) ? $_POST['easyorder_pricetiers_quantity'] : [];
        $easyorder_pricetiers_price = !empty($_POST['easyorder_pricetiers_price']) ? $_POST['easyorder_pricetiers_price'] : [];
        $easyorder_pricetiers = [];
        for($i=0; $i<count($easyorder_pricetiers_quantity); $i++) {
            if( !empty($easyorder_pricetiers_quantity[$i]) && !empty($easyorder_pricetiers_price[$i])) {
                $easyorder_pricetiers[] = [
                    'quantity' => $easyorder_pricetiers_quantity[$i],
                    'price' => $easyorder_pricetiers_price[$i]
                ];
            }
        }

        update_post_meta($post_id, '_easyorder_pricetiers', $easyorder_pricetiers);

    }

    function add_price_tiers() {
        global $woocommerce, $post;
        $post_id = $post->ID;
        $template = __DIR__ . '/views/qtyBasedPrice.php';

        if (file_exists($template)) {
            include $template;
        }
    }

    //Remove Quick Edit from Row Actions
    public function remove_row_actions($actions, $post)
    {
        if ($post->post_type == 'easyorder_message' && isset($actions['inline hide-if-no-js'])) {
            unset($actions['inline hide-if-no-js']);
        }
        return $actions;
    }

    // Show All column Head
    public function columns_easyorder_message_head($defaults)
    {
        unset($defaults['title']);
        unset($defaults['date']);
        $defaults['quote'] = esc_html__('Quote', 'easyorder');
        $defaults['status'] = esc_html__('Status', 'easyorder');
        $defaults['product'] = esc_html__('Product', 'easyorder');
        $defaults['email'] = esc_html__('Email', 'easyorder');
        $defaults['date'] = esc_html__('Date', 'easyorder');
        return $defaults;
    }

    // Show All corresponding value for each column
    public function columns_easyorder_message_content($column_name, $post_ID)
    {
        $order_quote_meta = json_decode(get_post_meta($post_ID, 'order_quote_meta', true), true);
        $forms = array();

        foreach ($order_quote_meta as $key => $meta) {
            $forms[$meta['name']] = $meta['value'];
        }

        if ($column_name == 'quote') { ?>
            <p>
                <a href="<?php get_admin_url() ?>post.php?post=<?php echo esc_attr($post_ID); ?>&amp;action=edit"><strong><?php echo '#' . esc_html($post_ID); ?></strong></a> <?php esc_html_e('by', 'easyorder') ?> <?php echo esc_html($forms['quote-first-name']) . ' ' . esc_html($forms['quote-last-name']); ?>
            </p>
        <?php }

        if ($column_name == 'status') {
            echo ucfirst(substr(get_post($post_ID)->post_status, 10));
        }
        if ($column_name == 'product') {
            $product_id = get_post_meta($post_ID, 'product_id', true);
            $product_title = get_the_title($product_id);
            $product_url = get_the_permalink($product_id); ?>
            <a href="<?php echo esc_url($product_url) ?>" target="_blank"><?php echo esc_html($product_title); ?></a>
            <?php
        }
        if ($column_name == 'date') {
            echo get_post($post_ID)->date;
        }
        if ($column_name == 'email') { ?>
            <a href="mailto:<?php echo esc_url($forms['quote-email']); ?>"><?php echo esc_html($forms['quote-email']); ?></a>
        <?php }
    }

    // Calculate and display count number
    public function bubble_count_number($menu)
    {
        global $wpdb;
        $query = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'easyorder_message' AND post_status = 'easyorder-pending'";
        $count = $wpdb->get_var($query);

        $check_menu_str = 'edit.php?post_type=easyorder_message';

        // loop through $menu items, find match, add indicator
        foreach ($menu as $menu_key => $menu_data) {
            if ($check_menu_str != $menu_data[2])
                continue;
            $menu[$menu_key][0] .= " <span class='update-plugins count-$count'><span class='plugin-count'>" . number_format_i18n($count) . '</span></span>';
        }

        return $menu;
    }

    public function message_pre_get_posts($query)
    {
        if (is_admin() && $query->query['post_type'] == 'easyorder_message') {
            if (!isset($query->query['post_status']) && empty($query->query['post_status'])) {
                $query->set('post_status', array('easyorder-pending', 'easyorder-processing', 'easyorder-on-hold', 'easyorder-accepted', 'easyorder-completed', 'easyorder-cancelled'));
                $query->set('orderby', array('date' => 'DESC'));
                $query->set('order', 'DESC');
            }
        }
    }

    public function save_post($post_id, $post)
    {

        if (isset($_POST['previous_post_status']) && ($_POST['previous_post_status'] !== $post->post_status)) {
            // send email

            $form_data = json_decode(get_post_meta($post_id, 'order_quote_meta', true), true);

            $from_name = '';
            $from_email = '';
            $from_phone = '';
            $product_id = '';
            $to_email = '';
            $to_author_id = '';

            $message_from_sender_html = '';

            foreach ($form_data as $key => $meta) {
                /**
                 * Get the post author_id, author_email, prodct_id
                 */
                if (isset($meta['name']) && $meta['name'] === 'add-to-cart') {
                    $product_id = $meta['value'];
                    $to_author_id = get_post_field('post_author', $product_id);
                    $to_email = get_the_author_meta('user_email', $to_author_id);
                }
                /**
                 * Get the customer name, email, phone, message
                 */
                else if (isset($meta['forms'])) {
                    $forms = $meta['forms'];
                    foreach ($forms as $k => $v) {
                        $message_from_sender_html .= "<p>" . $k . " : " . $v . "</p>";
                        if ($k === 'email') {
                            $from_email = $v;
                        }
                        if ($k === 'name') {
                            $from_name = $v;
                        }
                    }
                }
            }

            switch ($post->post_status) {
                case 'quote-accepted':
                    // send email to the customer

                    $admin_profile = easyorder_get_quote_admin_profile();

                    $from_email = $admin_profile['email'];
                    $from_name  = $admin_profile['name'];

	                // To info for customer
	                $to_email = easyorder_get_quote_customer_email($post->ID);

                    $quote_id = $post->ID;

                    $subject = esc_html__("Congratulations! Your quote request has been accepted", 'easyorder');
                    $data_object = array(
                        'quote_id' => $quote_id,
                        'status' => $post->post_status
                    );

                    // Send the mail to the customer
                    $email = new EasyOrderEmail();
                    $email->quote_accepted_notify_customer($to_email, $subject, $from_email, $from_name, $data_object);
                    break;

                default:
                    // send email to the customer

                    $admin_profile = easyorder_get_quote_admin_profile();

                    $from_email = $admin_profile['email'];
                    $from_name  = $admin_profile['name'];

                    // To info for customer
                    $to_email = easyorder_get_quote_customer_email($post->ID);

                    $quote_id = $post->ID;

                    $subject = esc_html__("Your quote request status has been updated", 'easyorder');
                    $data_object = array(
                        'quote_id' => $quote_id,
                        'status' => $post->post_status
                    );

                    // Send the mail to the customer
                    $email = new EasyOrderEmail();
                    $email->quote_status_update_notify_customer($to_email, $subject, $from_email, $from_name, $data_object);
                    break;
            }
        }

        if (isset($_POST['quote_price'])) {
            update_post_meta($post_id, '_quote_price', $_POST['quote_price']);
        }

        if (isset($_POST['min_qty'])) {
            update_post_meta($post_id, '_min_quantity', $_POST['min_qty']);
        }

        if (isset($_POST['add-quote-message']) && !empty($_POST['add-quote-message'])) {

            global $current_user;
            $time = current_time('mysql');

            $data = array(
                'comment_post_ID'      => $post->ID,
                'comment_author'       => $current_user->user_nicename,
                'comment_author_email' => $current_user->user_email,
                'comment_author_url'   => $current_user->user_url,
                'comment_content'      => $_POST['add-quote-message'],
                'comment_type'         => 'easyorder_message',
                'comment_parent'       => 0,
                'user_id'              => $current_user->ID,
                'comment_author_IP'    => easyorder_get_the_user_ip(),
                'comment_agent'        => $_SERVER['HTTP_USER_AGENT'],
                'comment_date'         => $time,
                'comment_approved'     => 1,
            );

            $comment_id = wp_insert_comment($data);

            // send email to the customer

            $admin_profile = easyorder_get_quote_admin_profile();
            $from_email = $admin_profile['email'];
            $from_name = $admin_profile['name'];

            // To info
            $to_author_id = get_post_field('post_author', $post->ID);
            $to_email = get_the_author_meta('user_email', $to_author_id);

            $quote_id = $post->ID;

            $subject = esc_html__("New reply for your quote request", 'easyorder');
            $reply_message = $_POST['add-quote-message'];
            $data_object = array(
                'reply_message' => $reply_message,
                'quote_id'      => $quote_id,
            );

            // Send the mail to the customer
            $email = new EasyOrderEmail();
            $email->owner_reply_message($to_email, $subject, $from_email, $from_name, $data_object);
        }
    }
}

new EasyOrderAdmin();
