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
    $my_quotes_columns = apply_filters('woocommerce_my_account_my_orders_columns', array(
      'quote-number'    => esc_html__('Order', 'easyorder'),
      'quote-status'    => esc_html__('Status', 'easyorder'),
      'quote-product'   => esc_html__('Product', 'easyorder'),
      'quote-email'     => esc_html__('Email', 'easyorder'),
      'quote-date'      => esc_html__('Date', 'easyorder'),
      'quote-actions'   => '&nbsp;',

    ));

    $customer_quotes = get_posts(apply_filters('easyorder_my_account_my_quote_query', array(
      'numberposts' => -1,
      'meta_key'    => '_product_vendor',
      'meta_value'  => get_current_user_id(),
      'post_type'   => 'easyorder_message',
      'post_status' => array('easyorder-pending', 'easyorder-processing', 'easyorder-on-hold', 'easyorder-accepted', 'easyorder-completed', 'easyorder-cancelled')
    )));

    ?>
    <div class="table-responsive wc-easyorder-message-list">
      <table class="shop_table shop_table_responsive my_account_orders">
        <thead>
          <tr>
            <?php foreach ($my_quotes_columns as $column_id => $column_name) : ?>
              <th class="<?php echo esc_attr($column_id); ?>"><?php echo esc_html($column_name); ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($customer_quotes as $customer_quote) : ?>
            <?php $message_id = $customer_quote->ID; ?>
            <?php
            $order_quote_meta = json_decode(get_post_meta($message_id, 'order_quote_meta', true), true);
            $forms = array();

            foreach ($order_quote_meta as $key => $meta) {
              $forms[$meta['name']] = $meta['value'];
            }
            ?>
            <tr class="order">
              <?php foreach ($my_quotes_columns as $column_id => $column_name) : ?>
                <td class="<?php echo esc_attr($column_id); ?>" data-title="<?php echo esc_attr($column_name); ?>" <?php echo ($column_id === 'quote-actions') ? 'style="text-align: right;"' : ''; ?>>
                  <?php if (has_action('easyorder_my_account_my_quotes_column_' . $column_id)) : ?>
                    <?php do_action('easyorder_my_account_my_quotes_column_' . $column_id, $order); ?>

                  <?php elseif ('quote-number' === $column_id) : ?>

                    <a href="<?php echo esc_url(dokan_get_navigation_url('view-message/' . $message_id)); ?>">
                      <?php echo esc_html_x('#', 'hash before order number', 'easyorder') . $message_id ?> <?php esc_html_e('by', 'easyorder') ?> <?php echo esc_html($forms['quote-first-name']) . ' ' . esc_html($forms['quote-last-name']); ?>
                    </a>

                  <?php elseif ('quote-status' === $column_id) : ?>
                    <?php echo easyorder_get_quote_status_name($customer_quote->post_status); ?>

                  <?php elseif ('quote-product' === $column_id) : ?>
                    <?php
                    $product_id = get_post_meta($message_id, 'product_id', true);
                    $product_title = get_the_title($product_id);
                    $product_url = get_the_permalink($product_id);
                    ?>
                    <a href="<?php echo esc_url($product_url) ?>" target="_blank"><?php echo esc_html($product_title); ?></a>

                  <?php elseif ('quote-email' === $column_id) : ?>
                    <a href="mailto:<?php echo esc_url($forms['quote-email']); ?>"><?php echo esc_html($forms['quote-email']); ?></a>

                  <?php elseif ('quote-date' === $column_id) : ?>
                    <time datetime="<?php echo date('Y-m-d', strtotime($customer_quote->post_date)); ?>" title="<?php echo esc_attr(strtotime($customer_quote->post_date)); ?>"><?php echo date_i18n(get_option('date_format'), strtotime($customer_quote->post_date)); ?></time>

                  <?php elseif ('quote-actions' === $column_id) :

                    echo '<a href="' . esc_url(esc_url(dokan_get_navigation_url('view-message/' . $message_id))) . '" class="button view">' . esc_html('view') . '</a>';

                  endif; ?>
                </td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>

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
