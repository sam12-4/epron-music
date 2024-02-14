<?php

/**
 * Ajax class
 */
class EasyOrderAjax
{
    /**
     * Initialize ajax class
     */
    public function __construct()
    {
        add_action('wp_ajax_easyorder_request_for_a_quote', array($this, 'request_for_a_quote'));
        add_action('wp_ajax_nopriv_easyorder_request_for_a_quote', array($this, 'request_for_a_quote'));
    }

    public function request_for_a_quote()
    {
        if (is_user_logged_in()) {
            $posted = $_POST;
            $form_data = $posted['form_data'];
            $product_id = $posted['product_id'];

            $new_user_id = get_current_user_id();

            $my_post = array(
                'post_title'  => date('Y-m-d H:i:s', current_time('timestamp', 1)),
                'post_status' => 'easyorder-pending',
                'post_type'   => 'easyorder_message',
                'post_author' => $new_user_id,
            );

            // Insert the post into the database
            $post_id = wp_insert_post($my_post);

            update_post_meta($post_id, 'order_quote_meta', json_encode($form_data, JSON_UNESCAPED_UNICODE), true);
            update_post_meta($post_id, '_quote_user', $new_user_id, true);
            update_post_meta($post_id, '_product_id', $product_id, true);

            $product_vendor = get_post_field('post_author', $product_id);

            update_post_meta($post_id, '_product_vendor', $product_vendor, true);

            foreach ($form_data as $key => $meta) {
                if (isset($meta['name'])) {
                    update_post_meta($post_id, $meta['name'], $meta['value']);
                }
            }

            $quote_id = $post_id;

            $from_name = '';
            $to_name = '';
            $from_email = '';
            $from_phone = '';
            $product_id = '';
            $to_email = '';
            $to_author_id = '';
            $reply_message = '';

            $message_from_receiver_html = '';
            $message_from_sender_html = '';

            foreach ($form_data as $key => $meta) {

                /**
                 * Get the customer name, email, phone, message
                 */

                if ($meta['name'] === 'quote-email') {
                    $to_email = $meta['value'];
                }
                if ($meta['name'] === 'quote-first-name') {
                    $to_name .= $meta['value'];
                }
                if ($meta['name'] === 'quote-last-name') {
                    $to_name .= ' ' . $meta['value'];
                }

                if ($meta['name'] === 'quote-message') {
                    $reply_message = $meta['value'];
                }

            }


            $time = current_time('mysql');
            global $current_user;

            $data = array(
                'comment_post_ID'      => $quote_id,
                'comment_author'       => $current_user->user_nicename,
                'comment_author_email' => $current_user->user_email,
                'comment_author_url'   => $current_user->user_url,
                'comment_content'      => $reply_message,
                'comment_type'         => 'easyorder_message',
                'comment_parent'       => 0,
                'user_id'              => $new_user_id,
                'comment_author_IP'    => easyorder_get_the_user_ip(),
                'comment_agent'        => $_SERVER['HTTP_USER_AGENT'],
                'comment_date'         => $time,
                'comment_approved'     => 1,
            );

            $comment_id = wp_insert_comment($data);

            $product_id = get_post_meta($quote_id, '_product_id', true);
            $post_author_id = get_post_field( 'post_author', $product_id );
            $from_email = get_the_author_meta('user_email', $post_author_id);
            $from_name = get_the_author_meta('display_name', $post_author_id);

            // To info
            $to_author_id = get_post_field('post_author', $post_id);
            $subject = esc_html__("Your quote request has been placed", 'easyorder');
            $data_object = array(
                'reply_message' => $reply_message,
                'quote_id'      => $quote_id,
            );

            // Send the mail to the customer
            $email = new EasyOrderEmail();
            $email->customer_place_quote_request($to_email, $subject, $from_email, $from_name, $data_object);

            // Send the mail to the owner
            $to_email_owner = $from_email;
            $subject_owner = esc_html__('You have a new quote request', 'easyorder');
            $from_email_customer = $to_email;
            $from_name_customer = $to_name;

            $email->owner_notify_place_quote_request($to_email_owner, $subject_owner, $from_email_customer, $from_name_customer, $data_object);

            echo json_encode(array('message' => esc_html__('Thanks! Your email has been sent.', 'easyorder'), 'status_code' => 200));

            wp_die();
        }
    }
}

new EasyOrderAjax();
