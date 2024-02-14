<?php

if (!defined('ABSPATH')) {
  exit;
}

$my_quotes_columns = apply_filters('woocommerce_my_account_my_orders_columns', array(
  'quote-number'  => esc_html__('Order', 'easyorder'),
  'quote-date'    => esc_html__('Date', 'easyorder'),
  'quote-status'  => esc_html__('Status', 'easyorder'),
  'quote-total'   => esc_html__('Price', 'easyorder'),
  'quote-actions' => '&nbsp;',
));

$customer_quotes = get_posts(apply_filters('easyorder_my_account_my_quote_query', array(
  'author'        =>  get_current_user_id(),
  'post_type'   => 'easyorder_message',
  'post_status' => array('easyorder-pending', 'easyorder-processing', 'easyorder-on-hold', 'easyorder-accepted', 'easyorder-completed', 'easyorder-cancelled')
)));

?>
<div class="table-responsive">
  <table class="shop_table shop_table_responsive my_account_orders">
    <thead>
      <tr>
        <?php foreach ($my_quotes_columns as $column_id => $column_name) : ?>
          <th class="<?php echo esc_attr($column_id); ?>"><span class="nobr"><?php echo esc_html($column_name); ?></span></th>
        <?php endforeach; ?>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($customer_quotes as $customer_quote) : ?>
        <?php $message_id = $customer_quote->ID; ?>
        <tr class="order">
          <?php foreach ($my_quotes_columns as $column_id => $column_name) : ?>
            <td class="<?php echo esc_attr($column_id); ?>" data-title="<?php echo esc_attr($column_name); ?>" <?php echo ($column_id === 'quote-actions') ? 'style="text-align: right;"' : ''; ?>>
              <?php if (has_action('easyorder_my_account_my_quotes_column_' . $column_id)) : ?>
                <?php do_action('easyorder_my_account_my_quotes_column_' . $column_id, $order); ?>

              <?php elseif ('quote-number' === $column_id) : ?>
                <a href="<?php echo esc_url(easyorder_get_view_message_url($message_id)); ?>">
                  <?php echo esc_html_x('#', 'hash before order number', 'easyorder') . $message_id ?>
                </a>

              <?php elseif ('quote-date' === $column_id) : ?>
                <time datetime="<?php echo date('Y-m-d', strtotime($customer_quote->post_date)); ?>" title="<?php echo esc_attr(strtotime($customer_quote->post_date)); ?>"><?php echo date_i18n(get_option('date_format'), strtotime($customer_quote->post_date)); ?></time>

              <?php elseif ('quote-status' === $column_id) : ?>
                <?php echo easyorder_get_quote_status_name($customer_quote->post_status); ?>

              <?php elseif ('quote-total' === $column_id) : ?>
                <span class="woocommerce-Price-amount amount">
                  <?php echo wc_price(get_post_meta($message_id, '_quote_price', true)); ?>
                </span>
                <?php esc_html_e('for ', 'easyorder') ?>
                <?php echo get_post_meta($message_id, '_min_quantity', true); ?>
                <?php esc_html_e('items', 'easyorder') ?>
              <?php elseif ('quote-actions' === $column_id) :

                echo '<a href="' . esc_url(easyorder_get_view_message_url($message_id)) . '" class="button view">' . esc_html('view') . '</a>';

              endif; ?>
            </td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>
