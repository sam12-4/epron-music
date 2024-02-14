<?php

/**
 * View Quote
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  redqteam
 * @package EasyOrder/Templates
 * @version 2.2.0
 */

if (!defined('ABSPATH')) {
  exit;
}
if ($message_id === '') { ?>
  <div class="woocommerce-error"><?php esc_html_e('Invalid quote.', 'easyorder') ?> <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" class="wc-forward"><?php esc_html_e('My Account', 'easyorder') ?></a></div>
<?php
  exit;
}
// global $post;
$message = get_post($message_id);
$product_id = get_post_meta($message_id, '_product_id', true);
$product = wc_get_product($product_id);

if ($message->post_author == get_current_user_id()) { ?>
  <div class="wc-easyorder-quote-status">
    <?php
    printf(
      __('Quote #%1$s request was placed on %2$s and is currently %3$s.', 'easyorder'),
      '<mark class="order-number">' . $message_id . '</mark>',
      '<mark class="order-date">' . date_i18n(get_option('date_format'), strtotime($message->post_date)) . '</mark>',
      '<mark class="order-status">' . easyorder_get_quote_status_name($message->post_status) . '</mark>'
    );
    ?>
  </div>
  <div class="wc-easyorder-content-body customer-end">
    <div class="wc-easyorder-quote-table">
      <h2><?php esc_html_e('Quote Details', 'easyorder'); ?></h2>
      <!-- Custom add to cart -->
      <?php if (strtolower(easyorder_get_quote_status_name($message->post_status)) === 'accepted' && $product->is_purchasable() && $product->is_in_stock()) : ?>
        <form class="cart" action="<?php echo esc_url(wc_get_checkout_url()); ?>" method="post" enctype='multipart/form-data'>
          <div class="table-responsive">
            <table class="shop_table order_details">
              <thead>
                <tr>
                  <th class="product-name" colspan="2"><?php esc_html_e('Product', 'easyorder'); ?></th>
                  <th class="product-price"><?php esc_html_e('Price', 'easyorder'); ?></th>
                  <th class="product-price"><?php esc_html_e('Quantity', 'easyorder'); ?></th>
                  <th class="product-price"><?php esc_html_e('Action', 'easyorder'); ?></th>
                </tr>
              </thead>
              <tbody>
                <tr class="order_item">
                  <td class="product-name" colspan="2">
                    <a href="<?php echo get_the_permalink($product_id) ?>"><?php echo get_the_title($product_id) ?></a> <strong class="product-quantity">× <?php echo get_post_meta($message_id, '_min_quantity', true); ?></strong>
                    <?php $message_metas = json_decode(get_post_meta($message_id, 'order_quote_meta', true), true); ?>
                  </td>
                  <td class="product-total">
                    <span class="woocommerce-Price-amount amount">
                      <?php echo wc_price(get_post_meta($message_id, '_quote_price', true)); ?>
                    </span>
                  </td>

                  <td class="product-quantity">
                    <?php
                    woocommerce_quantity_input(
                      array(
                        'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                        'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                        'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
                        // WPCS: CSRF ok, input var ok.
                      ),
                      $product
                    );
                    ?>
                  </td>

                  <td>
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="wc-easyorder-add-to-cart single_add_to_cart_button button alt"><?php esc_html_e('Buy Now', 'easyorder'); ?></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </form>

      <?php else : ?>
        <div class="table-responsive">
          <table class="shop_table order_details">
            <thead>
              <tr>
                <th class="product-name"><?php esc_html_e('Product', 'easyorder'); ?></th>
                <th class="product-price"><?php esc_html_e('Price', 'easyorder'); ?></th>
              </tr>
            </thead>
            <tbody>
              <tr class="order_item">
                <td class="product-name">
                  <a href="<?php echo get_the_permalink($product_id) ?>"><?php echo get_the_title($product_id) ?></a> <strong class="product-quantity">× <?php echo get_post_meta($message_id, '_min_quantity', true); ?></strong>
                  <?php $message_metas = json_decode(get_post_meta($message_id, 'order_quote_meta', true), true); ?>
                </td>
                <td class="product-total">
                  <span class="woocommerce-Price-amount amount">
                    <?php echo wc_price(get_post_meta($message_id, '_quote_price', true)); ?>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

    </div> <!-- end .wc-easyorder-quote-table -->
  </div> <!-- end .wc-easyorder-content-body -->

  <?php

  $post_author_id = get_post_field('post_author', $product_id);

  // Remove the comments_clauses where query here.
  remove_filter('comments_clauses', 'exclude_request_quote_comments_clauses');
  $args = array(
    'post_id' => $message_id,
    'orderby' => 'comment_ID',
    'order' => 'ASC',
    'approve' => 'approve',
    'type' => 'easyorder_message'
  );
  $comments = get_comments($args); ?>

  <?php do_action('easyorder_message_reply_submit'); ?>

  <div class="wc-easyorder-message-container">
    <div class="wc-easyorder-messaging-header">
      <figure class="wc-easyorder-customer-avatar">
        <img src="<?php echo esc_url(get_avatar_url($post_author_id)); ?>" alt="">
      </figure>
      <h3 class="wc-easyorder-customer-name"><?php echo esc_html(get_the_author_meta('display_name', $post_author_id)) ?></h3>
    </div>
    <div id="wc-easyorder-quote-message-list" class="wc-easyorder-quote-message-list">
      <?php foreach ($comments as $comment) : ?>
        <?php
        $isCustomer = null;
        if ($comment->user_id === get_post_field('post_author', $message_id)) {
          $isCustomer = true;
        } else {
          $isCustomer = false;
        }
        ?>
        <div class="wc-easyorder-quote-message-item customer-end<?php echo $isCustomer ? ' customer' : ' vendor' ?>" data-name="<?php echo esc_attr($comment->comment_author); ?>">
          <?php if (!$isCustomer) : ?>
            <figure class="wc-easyorder-quote-avatar">
              <img src="<?php echo esc_url(get_avatar_url($post_author_id)); ?>" alt="">
            </figure>
          <?php endif; ?>
          <div class="wc-easyorder-message-body<?php echo $isCustomer ? ' customer' : ' vendor'  ?>">
            <?php echo wptexturize(wp_kses_post($comment->comment_content)); ?>
          </div>
          <p class="wc-easyorder-time-meta customer-end<?php echo $isCustomer ? ' customer' : ' vendor' ?>">
            <?php echo easyorder_time_elapsed_string($comment->comment_date); ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>

    <form class="wc-easyorder-message-input-container" method="post">
      <div class="wc-easyorder-message-input">
        <input type="hidden" name="quote-reply-id" value="<?php echo esc_attr($message_id); ?>" />
        <textarea name="quote-reply-message" class="add-quote-message" placeholder="<?php esc_html_e('Your message', 'easyorder') ?>" required="true"></textarea>
        <?php wp_nonce_field('quote_reply_action', 'quote_reply_nonce_field'); ?>
      </div>

      <button class="wc-easyorder-message-submit add-message-button" type="submit">
        <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 18L21 9L0 0V7L15 9L0 11V18Z" fill="black" />
        </svg>
      </button>
    </form>
  </div>
<?php
} else { ?>
  <p><?php esc_html_e('Sorry! Quote does not found.', 'easyorder'); ?></p>
<?php
} // end if quote author
?>
