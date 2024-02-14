<?php

/**
 * Is simple type product get price rules
 *
 * @param int $product_id
 * @return array
 */

function easyorder_get_price_rules($product_id) {
  $easyorder_pricetiers = get_post_meta($product_id, '_easyorder_pricetiers', true);

  if (!empty($easyorder_pricetiers)) {
    if (is_user_logged_in()) {
      $cq_args = [
        'posts_per_page'    => -1,
        'post_type'         => 'easyorder_message',
        'post_status'       => 'easyorder-accepted',
        'meta_key'          => '_product_id',
        'meta_value'        => $product_id,
        'author'            => get_current_user_id(),
      ];
      $customer_quotes = new WP_Query($cq_args);

      if ($customer_quotes->have_posts()) {
        while ($customer_quotes->have_posts()) {
          $customer_quotes->the_post();
          $quote_id = get_the_ID();
          $easyorder_pricetiers[] = [
            'quantity' => get_post_meta($quote_id, '_min_quantity', true),
            'price' => get_post_meta($quote_id, '_quote_price', true)
          ];
        }
      }
      wp_reset_query();
    }

    // you can use array_column() instead of the above code
    $quantity  = array_column($easyorder_pricetiers, 'quantity');

    // Sort the data with quantity ascending
    array_multisort($quantity, SORT_ASC, $easyorder_pricetiers);

    $rules = [];
    for ($i = 0; $i < count($easyorder_pricetiers); $i++) {
      $next = $i + 1;
      if ($next < count($easyorder_pricetiers)) {
        if ($easyorder_pricetiers[$i]['quantity'] !== $easyorder_pricetiers[$next]['quantity']) {
          $rules[] = [
            'min_qty' => $easyorder_pricetiers[$i]['quantity'],
            'max_qty' => $easyorder_pricetiers[$next]['quantity'] - 1,
            'price'   => $easyorder_pricetiers[$i]['price']
          ];
        }
      } elseif ($next === count($easyorder_pricetiers)) {
        $rules[] = [
          'min_qty' => $easyorder_pricetiers[$i]['quantity'],
          'max_qty' => $easyorder_pricetiers[$i]['quantity'],
          'price'   => $easyorder_pricetiers[$i]['price']
        ];
      }
    }

    return $rules;
  }
  return [];
}

function easyorder_get_qty_based_price($product_id, $qty = 1, $defaultPrice) {
  $default = [
    'min_qty' => $qty,
    'max_qty' => $qty,
    'price'   => $defaultPrice
  ];

  $rules = easyorder_get_price_rules($product_id);

  foreach ($rules as $key => $rule) {
    if ($rule['min_qty'] <= $qty && $qty <= $rule['max_qty']) {
      return $rule;
    } else if ($rule['min_qty'] == $rule['max_qty'] && $qty >= $rule['max_qty']) {
      return $rule;
    }
  }

  return $default;
}

add_action('woocommerce_after_add_to_cart_form', 'easyorder_request_quote');

if (!function_exists('easyorder_request_quote')) {
  /**
   * Output the start of the page wrapper.
   *
   */
  function easyorder_request_quote() {
    easyorder_get_template('easyorder/request-quote.php');
  }
}


/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
function easyorder_get_template($template_name, $args = [], $template_path = '', $default_path = '') {
  if (!empty($args) && is_array($args)) {
    extract($args);
  }

  $located = easyorder_locate_template($template_name, $template_path, $default_path);

  if (!file_exists($located)) {
    _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $located), '2.1');
    return;
  }

  // Allow 3rd party plugin filter template file from their plugin.
  $located = apply_filters('easyorder_get_template', $located, $template_name, $args, $template_path, $default_path);

  do_action('woocommerce_before_template_part', $template_name, $template_path, $located, $args);

  include $located;

  do_action('woocommerce_after_template_part', $template_name, $template_path, $located, $args);
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *        yourtheme        /    $template_path    /    $template_name
 *        yourtheme        /    $template_name
 *        $default_path    /    $template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function easyorder_locate_template($template_name, $template_path = '', $default_path = '') {
  if (!$template_path) {
    $template_path = WC()->template_path();
  }

  if (!$default_path) {
    $default_path = trailingslashit(EASYORDER_TEMPLATE_PATH);
  }

  // Look within passed path within the theme - this is priority.
  $template = locate_template(
    [
      trailingslashit($template_path) . $template_name,
      $template_name
    ]
  );

  // Get default template/
  if (!$template) {
    $template = $default_path . $template_name;
  }

  // Return what we found.
  return apply_filters('woocommerce_locate_template', $template, $template_name, $template_path);
}

function easyorder_get_the_user_ip() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    //check ip from share internet
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //to check ip is pass from proxy
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return apply_filters('easyorder_get_ip', $ip);
}

function easyorder_get_view_message_url($quote_id) {

  $view_quote_url = wc_get_endpoint_url('easyorder-view-message', $quote_id, wc_get_page_permalink('myaccount'));

  return apply_filters('easyorder_get_view_message_url', $view_quote_url, $quote_id);
}

/**
 * Get the nice name for an quote status.
 *
 * @param string $status
 * @return string
 * @since  2.2
 */
function easyorder_get_quote_status_name($status) {
  $statuses = easyorder_get_quote_statuses();
  $status = 'easyorder-' === substr($status, 0, 10) ? substr($status, 10) : $status;
  $status = isset($statuses['easyorder-' . $status]) ? $statuses['easyorder-' . $status] : $status;

  return $status;
}

/**
 * Get all quote statuses.
 *
 * @return array
 * @since 2.2
 */
function easyorder_get_quote_statuses() {
  $quote_statuses = array(
    'easyorder-pending'    => esc_html_x('Pending', 'Quote status', 'easyorder'),
    'easyorder-processing' => esc_html_x('Processing', 'Quote status', 'easyorder'),
    'easyorder-on-hold'    => esc_html_x('On Hold', 'Quote status', 'easyorder'),
    'easyorder-accepted'    => esc_html_x('Accepted', 'Quote status', 'easyorder'),
    'easyorder-completed'  => esc_html_x('Completed', 'Quote status', 'easyorder'),
    'easyorder-cancelled'  => esc_html_x('Cancelled', 'Quote status', 'easyorder'),
  );
  return apply_filters('easyorder_quote_statuses', $quote_statuses);
}

add_action('easyorder_vendor_message_reply_submit', 'easyorder_vendor_message_reply_submit');
function easyorder_vendor_message_reply_submit() {

  // if this fails, check_admin_referer() will automatically print a "failed" page and die.
  if (!empty($_POST) && check_admin_referer('quote_reply_action', 'quote_reply_nonce_field')) {
    $post_id = $_POST['post_id'];
    $post = get_post( $post_id );
    $status = $_POST['post_status'];

    if ($_POST['previous_post_status'] !== $_POST['post_status']) {
      global $wpdb;
      $wpdb->query(
        $wpdb->prepare(
            "UPDATE $wpdb->posts SET post_status = '%s' WHERE ID = %d",
            $status,
            $post_id
        )
      );

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

      switch ($status) {
          case 'quote-accepted':
              // send email to the customer

              $admin_profile = easyorder_get_quote_admin_profile();

              $from_email = $admin_profile['email'];
              $from_name  = $admin_profile['name'];

              // To info for customer
              $to_email = easyorder_get_quote_customer_email($post_id);

              $quote_id = $post_id;

              $subject = esc_html__("Congratulations! Your quote request has been accepted", 'easyorder');
              $data_object = array(
                'quote_id' => $quote_id,
                'status' => $status,
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
              $to_email = easyorder_get_quote_customer_email($post_id);

              $quote_id = $post_id;

              $subject = esc_html__("Your quote request status has been updated", 'easyorder');
              $data_object = array(
                'quote_id' => $quote_id,
                'status' => $status,
              );

              // Send the mail to the customer
              $email = new EasyOrderEmail();
              $email->quote_status_update_notify_customer($to_email, $subject, $from_email, $from_name, $data_object);
              break;
      }
    } // end if previous_post_status

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
        'comment_post_ID'      => $post_id,
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
      $to_author_id = get_post_field('post_author', $post_id);
      $to_email = get_the_author_meta('user_email', $to_author_id);

      $quote_id = $post_id;

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
    // Reload the page again
    echo "<meta http-equiv='refresh' content='0'>";
  } // end check_admin_referer
}
add_action('easyorder_message_reply_submit', 'easyorder_message_reply_submit');
function easyorder_message_reply_submit() {
  // if this fails, check_admin_referer() will automatically print a "failed" page and die.
  if (!empty($_POST) && check_admin_referer('quote_reply_action', 'quote_reply_nonce_field')) {
    // process form data

    if (isset($_POST['quote-reply-message']) && !empty($_POST['quote-reply-message'])) {

      global $current_user;
      $posted = $_POST;

      $quote_id = $posted['quote-reply-id'];
      $reply_message = $posted['quote-reply-message'];

      $time = current_time('mysql');

      $data = array(
        'comment_post_ID'      => $quote_id,
        'comment_author'       => $current_user->user_nicename,
        'comment_author_email' => $current_user->user_email,
        'comment_author_url'   => $current_user->user_url,
        'comment_content'      => $reply_message,
        'comment_type'         => 'easyorder_message',
        'comment_parent'       => 0,
        'user_id'              => $current_user->ID,
        'comment_author_IP'    => easyorder_get_the_user_ip(),
        'comment_agent'        => $_SERVER['HTTP_USER_AGENT'],
        'comment_date'         => $time,
        'comment_approved'     => 1,
      );

      $comment_id = wp_insert_comment($data);


      // send email to the product owner

      $product_id = get_post_meta($quote_id, '_product_id', true);
      $post_author_id = get_post_field( 'post_author', $product_id );
      $to_email = get_the_author_meta('user_email', $post_author_id);

      // FROM info
      $from_author_id = get_post_field('post_author', $quote_id);
      $from_email = get_the_author_meta('user_email', $from_author_id);
      $from_name = get_the_author_meta('user_nicename', $from_author_id);

      $subject = esc_html__("New reply from customer quote request", 'easyorder');

      $data_object = array(
        'reply_message' => $reply_message,
        'quote_id'      => $quote_id,
      );

      // Send the mail to the customer
      $email = new EasyOrderEmail();
      $email->customer_reply_message($to_email, $subject, $from_email, $from_name, $data_object);

      // Reload the page again
      echo "<meta http-equiv='refresh' content='0'>";
    }
  }
}


/**
 * Admin profile for RFQ
 *
 * @return array
 */
function easyorder_get_quote_admin_profile() {
  $profile = [
    'name'  => get_option('easyorder_message_admin_profile_name'),
    'email' => get_option('easyorder_message_admin_profile_email'),
  ];

  // If the user isn't set the custom name, then use sitename
  if (empty(trim($profile['name']))) {
    $profile['name'] = get_option('blogname');
  }

  // If the user isn't set the custom email, then use wp-admin email
  if (empty(trim($profile['email']))) {
    $profile['email'] = get_option('admin_email');
  }

  return $profile;
}

function easyorder_get_view_quote_url($quote_id) {

  $view_quote_url = wc_get_endpoint_url('easyorder-view-message', $quote_id, wc_get_page_permalink('myaccount'));

  return apply_filters('easyorder_get_view_qoute_url', $view_quote_url, $quote_id);
}

function easyorder_get_view_quote_admin_url($quote_id) {

  $view_quote_admin_url = admin_url('post.php?post=' . $quote_id) . '&action=edit';

  return apply_filters('easyorder_get_view_quote_admin_url', $view_quote_admin_url, $quote_id);
}

/**
 * Helper function for get RFQ author email and name
 *
 * @param $post_id
 *
 * @return mixed
 */
function easyorder_get_quote_customer_data($post_id) {
  // Get the order_quote_meta content
  $order_quote_meta = json_decode(get_post_meta($post_id, 'order_quote_meta', true), true);

  // Extract the email from that forms and return it
  return $order_quote_meta;
}

/**
 * Helper function for get RFQ author email and name
 *
 * @param $post_id
 *
 * @return mixed
 */
function easyorder_get_quote_customer_email($post_id) {
  // Get the order_quote_meta content
  $order_quote_meta = json_decode(get_post_meta($post_id, 'order_quote_meta', true), true);

  // Get the forms array
  $forms = array_column($order_quote_meta, 'value', 'name');

  // Extract the email from that forms and return it
  return $forms['quote-email'];
}

/**
 * Helper function for get RFQ author email and name
 *
 * @param $post_id
 *
 * @return mixed
 */
function easyorder_get_quote_customer_input($key, $data) {
  // Get the forms array
  $forms = array_column($data, 'value', 'name');

  // Extract the email from that forms and return it
  return $forms[$key];
}


function easyorder_has_price_rules($post_id) {
  return easyorder_get_price_rules($post_id);
}
