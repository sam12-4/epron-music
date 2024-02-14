<?php

/**
 *  Dokan Dashboard Template
 *
 *  Dokan Main Dashboard template for Fron-end
 *
 *  @since 2.4
 *
 *  @package dokan
 */
?>
<?php do_action('dokan_dashboard_wrap_start'); ?>

<div class="dokan-dashboard-wrap">
  <?php

  /**
   *  dokan_dashboard_content_before hook
   *
   *  @hooked get_dashboard_side_navigation
   *
   *  @since 2.4
   */
  do_action('dokan_dashboard_content_before');
  ?>

  <div class="dokan-dashboard-content">
    <?php
    if ($message_id === '') { ?>
      <div class="woocommerce-error"><?php esc_html_e('Invalid quote.', 'easyorder') ?> <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" class="wc-forward"><?php esc_html_e('My Account', 'easyorder') ?></a></div>
    <?php
      exit;
    }
    // global $post;
    $message = get_post($message_id);

    $product_vendor = get_post_meta($message_id, '_product_vendor', true);
    $product_id = get_post_meta($message_id, '_product_id', true);

    if ($product_vendor == get_current_user_id()) { ?>
      <div class="wc-easyorder-quote-status">
        <?php
        printf(
          esc_html__('Quote #%1$s request was placed on %2$s and is currently %3$s.', 'easyorder'),
          '<mark class="order-number">' . $message_id . '</mark>',
          '<mark class="order-date">' . date_i18n(get_option('date_format'), strtotime($message->post_date)) . '</mark>',
          '<mark class="order-status">' . easyorder_get_quote_status_name($message->post_status) . '</mark>'
        );
        ?>
      </div>

      <?php do_action('easyorder_vendor_message_reply_submit'); ?>

      <form class="quote-reply-form" method="post">
        <div class="wc-easyorder-content-body">
          <div class="wc-easyorder-quote-table">
            <h2><?php esc_html_e('Quote Details', 'easyorder'); ?></h2>
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
                      <?php echo get_the_title($product_id) ?> <strong class="product-quantity">× <?php echo get_post_meta($message_id, '_min_quantity', true); ?></strong>
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
          </div>
          <div class="wc-easyorder-quote-actions">
            <div id="quote-status" class="wc-easyorder-quote-action-input">
              <label><?php esc_html_e('Quote Status', 'easyorder') ?></label>
              <?php
              $quote_statuses = apply_filters(
                'easyorder_get_request_quote_post_statuses',
                array(
                  'easyorder-pending'    => esc_html_x('Pending', 'Quote status', 'easyorder'),
                  'easyorder-processing' => esc_html_x('Processing', 'Quote status', 'easyorder'),
                  'easyorder-on-hold'    => esc_html_x('On Hold', 'Quote status', 'easyorder'),
                  'easyorder-accepted'   => esc_html_x('Accepted', 'Quote status', 'easyorder'),
                  'easyorder-completed'  => esc_html_x('Completed', 'Quote status', 'easyorder'),
                  'easyorder-cancelled'  => esc_html_x('Cancelled', 'Quote status', 'easyorder'),
                )
              );
              ?>
              <input type="hidden" name="previous_post_status" value="<?php echo esc_attr($message->post_status); ?>">
              <input type="hidden" name="post_id" value="<?php echo esc_attr($message_id); ?>">
              <select name="post_status">
                <?php foreach ($quote_statuses as $key => $value) : ?>
                  <option value="<?php echo esc_attr($key); ?>" <?php echo ($message->post_status === $key) ? 'selected="selected"' : '' ?>><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="wc-easyorder-quote-action-input">
              <label><?php esc_html_e('Price', 'easyorder') ?>
                (<?php echo get_woocommerce_currency_symbol() ?>)</label>
              <?php
              $price = get_post_meta($message_id, '_quote_price', true);
              $min_qty = get_post_meta($message_id, '_min_quantity', true);
              ?>
              <input type="number" class="input_price" name="quote_price" value="<?php echo esc_attr($price); ?>"  step=any>
              <input type="hidden" name="previous_post_status" value="<?php echo esc_attr($message->post_status); ?>">
            </div>
            <div class="wc-easyorder-quote-action-input">
              <label><?php esc_html_e('Min Quantity', 'easyorder') ?></label>
              <input type="number" class="input_quantity" name="min_qty" value="<?php echo esc_attr($min_qty); ?>">
            </div>
            <div class="wc-easyorder-quote-action-input last">
              <label for=""></label>
              <input type="submit" class="button save_quote button-primary tips" name="save" value="<?php esc_html_e('Update Quote', 'easyorder'); ?>" data-tip="<?php esc_html_e('Update the %s', 'easyorder'); ?>" />
            </div>
          </div>
        </div> <!-- end .wc-easyorder-content-body -->

        <?php
        
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

        <div class="wc-easyorder-message-container">
          <div class="wc-easyorder-messaging-header">
            <figure class="wc-easyorder-customer-avatar">
              <img src="<?php echo esc_url( get_avatar_url( $message->post_author ) ); ?>" alt="">
            </figure>
            <h3 class="wc-easyorder-customer-name"><?php echo esc_html(get_the_author_meta('display_name', $message->post_author)) ?></h3>
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
              <div class="wc-easyorder-quote-message-item<?php echo $isCustomer ? ' customer' : ' vendor' ?>">
                <?php if ($isCustomer) : ?>
                  <figure class="wc-easyorder-quote-avatar">
                    <img src="<?php echo esc_url( get_avatar_url( $message->post_author ) ); ?>" alt="">
                  </figure>
                <?php endif; ?>
                <div class="wc-easyorder-message-body<?php echo $isCustomer ? ' customer' : ' vendor'  ?>">
                  <?php echo wptexturize(wp_kses_post($comment->comment_content)); ?>
                </div>
                <p class="wc-easyorder-time-meta<?php echo $isCustomer ? ' customer' : ' vendor' ?>">
                  <?php echo easyorder_time_elapsed_string($comment->comment_date); ?>
                </p>
              </div>
            <?php endforeach; ?>
          </div> <!-- end #wc-easyorder-quote-message-list -->

          <div class="wc-easyorder-message-input-container">
            <div class="wc-easyorder-message-input">
              <input type="hidden" name="quote-reply-id" value="<?php echo esc_attr($message_id); ?>" />
              <textarea name="add-quote-message" class="add-quote-message" placeholder="<?php esc_html_e('Your message', 'easyorder') ?>"></textarea>
              <?php wp_nonce_field('quote_reply_action', 'quote_reply_nonce_field'); ?>
            </div> <!-- end .wc-easyorder-message-input -->
            <button type="submit" class="wc-easyorder-message-submit">
              <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 18L21 9L0 0V7L15 9L0 11V18Z" fill="black" />
              </svg>
            </button>
          </div> <!-- end .wc-easyorder-message-input-container -->
        </div> <!-- end .wc-easyorder-message-container -->
      </form> <!-- end .quote-reply-form -->
    <?php
    } else { ?>
      <p><?php esc_html_e('Sorry! Quote does not found.', 'easyorder') ?></p>
    <?php
    } // end if quote author
    ?>

  </div><!-- .dokan-dashboard-content -->

  <?php

  /**
   *  dokan_dashboard_content_after hook
   *
   *  @since 2.4
   */
  do_action('dokan_dashboard_content_after');
  ?>

</div><!-- .dokan-dashboard-wrap -->

<?php do_action('dokan_dashboard_wrap_end'); ?>
