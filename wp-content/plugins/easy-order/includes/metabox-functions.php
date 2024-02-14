<?php

/**
 * Converting timestamp to time ago in PHP e.g 1 day ago, 2 days ago…
 *
 * @link https://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
 */
function easyorder_time_elapsed_string($datetime, $full = false) {
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = [
    'y' => 'year',
    'm' => 'month',
    'w' => 'week',
    'd' => 'day',
    'h' => 'hour',
    'i' => 'minute',
    's' => 'second',
  ];
  foreach ($string as $k => &$v) {
    if ($diff->$k) {
      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    } else {
      unset($string[$k]);
    }
  }

  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function easyorder_quote_message_cb($post) {
  $quote_id = $post->ID;
  // Remove the comments_clauses where query here.
  remove_filter('comments_clauses', 'exclude_request_quote_comments_clauses');
  $args = array(
    'post_id' => $quote_id,
    'orderby' => 'comment_ID',
    'order'   => 'ASC',
    'approve' => 'approve',
    'type'    => 'easyorder_message'
  );
  $comments = get_comments($args); ?>
  <?php
    $order_quote_meta = json_decode(get_post_meta($quote_id, 'order_quote_meta', true), true);
    // _log(easyorder_get_quote_customer_input('quote-email', $order_quote_meta));
    $gravatar_url = get_avatar_url(easyorder_get_quote_customer_input('quote-email', $order_quote_meta));
    $customer_name = easyorder_get_quote_customer_input('quote-first-name', $order_quote_meta) . ' ' .  easyorder_get_quote_customer_input('quote-last-name', $order_quote_meta);
  ?>
  <div class="wc-easyorder-messaging-header">
    <figure class="wc-easyorder-customer-avatar">
      <img src="<?php echo esc_url($gravatar_url) ?>" alt="<?php echo esc_html($customer_name) ?>">
    </figure>
    <h3 class="wc-easyorder-customer-name"><?php echo esc_html($customer_name) ?></h3>
  </div>
  <div id="wc-easyorder-quote-message-list" class="wc-easyorder-quote-message-list">
    <?php foreach ($comments as $comment) : ?>
      <?php
      $isCustomer = null;
      if ($comment->user_id === get_post_field('post_author', $quote_id)) {
        $isCustomer = true;
      } else {
        $isCustomer = false;
      }
      ?>
      <div class="wc-easyorder-quote-message-item<?php echo $isCustomer ? ' customer' : ' vendor' ?>">
        <?php if ($isCustomer) : ?>
          <figure class="wc-easyorder-quote-avatar">
            <img src="<?php echo esc_url($gravatar_url) ?>" alt="<?php echo esc_html($customer_name) ?>">
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
  </div>

  <div class="wc-easyorder-message-input-container">
    <div class="wc-easyorder-message-input">
      <textarea class="widefat add-quote-message" name="add-quote-message" placeholder="<?php esc_html_e('Your message', 'easyorder') ?>"></textarea>
    </div>


    <button class="wc-easyorder-message-submit add-message-button">
      <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 18L21 9L0 0V7L15 9L0 11V18Z" fill="black" />
      </svg>
    </button>
  </div>
<?php
}

function easyorder_quote_message_save_cb($post) { ?>
  <div class="wc-easyorder-quote-actions submitbox">
    <div class="wc-easyorder-quote-action-input" id="quote-status">
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
      <select name="post_status">
        <?php foreach ($quote_statuses as $key => $value) : ?>
          <option value="<?php echo esc_attr($key); ?>" <?php echo ($post->post_status === $key) ? 'selected="selected"' : '' ?>>
            <?php echo esc_html($value); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="wc-easyorder-quote-action-input">
      <label>
        <?php esc_html_e('Price', 'easyorder') ?>
        (<?php echo get_woocommerce_currency_symbol() ?>)
      </label>
      <?php
      $price = get_post_meta($post->ID, '_quote_price', true);
      $min_qty = get_post_meta($post->ID, '_min_quantity', true);
      ?>
      <input type="number" class="input_price" name="quote_price" value="<?php echo esc_attr($price); ?>" step=any>
      <input type="hidden" name="previous_post_status" value="<?php echo esc_attr($post->post_status); ?>">
    </div>
    <div class="wc-easyorder-quote-action-input">
      <label><?php esc_html_e('Min Quantity', 'easyorder') ?></label>
      <input type="number" class="input_quantity" name="min_qty" value="<?php echo esc_attr($min_qty); ?>">
    </div>
    <div class="wc-easyorder-quote-action-input" id="delete-action">
      <?php
      if (current_user_can('delete_post', $post->ID)) {
        if (!EMPTY_TRASH_DAYS) {
          $delete_text = esc_html__('Delete Permanently', 'easyorder');
        } else {
          $delete_text = esc_html__('Move to Trash', 'easyorder');
        } ?>
        <a class="submitdelete deletion" href="<?php echo esc_url(get_delete_post_link($post->ID)); ?>">
          <?php echo esc_html($delete_text); ?>
        </a>
      <?php } ?>
    </div>
    <div class="wc-easyorder-quote-action-input">
      <input type="submit" class="button save_quote button-primary tips" name="save" value="<?php esc_html_e('Update Quote', 'easyorder'); ?>" data-tip="<?php esc_html_e('Update the %s', 'easyorder'); ?>" />
    </div>
  </div>
<?php
}
