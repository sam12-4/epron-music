<?php
global $product;

$product_id = $product->get_id();

$customer_first_name = '';
$customer_last_name = '';
$customer_phone = '';
$customer_email = '';
if (is_user_logged_in()) {
  global $current_user;
  $customer_first_name = get_user_meta($current_user->ID, 'billing_first_name', true);
  $customer_last_name = get_user_meta($current_user->ID, 'billing_last_name', true);
  $customer_phone = get_user_meta($current_user->ID, 'billing_phone', true);
  $customer_email = get_user_meta($current_user->ID, 'billing_email', true);
}
$active_theme = wp_get_theme();

$product_url = get_the_permalink($product_id);
$my_account_page = wc_get_page_id( 'myaccount' );

if( !empty($my_account_page)) {
  $wp_login_url = get_permalink( $my_account_page ) . '?redirect_to=' . $product_url;
} else {
  $wp_login_url = wp_login_url($product_url);
}

?>
<?php if (!empty(easyorder_has_price_rules($product_id))) : ?>
  <?php if (!is_user_logged_in()) : ?>
    <a href="<?php echo esc_url($wp_login_url) ?>" class="button"><?php esc_html_e('Request for a quote?', 'easyorder') ?></a>
  <?php else : ?>
    <a href="#quote-popup" class="open-popup-link wc-easyorder-btn-request-quote theme-<?php echo esc_html($active_theme->get('TextDomain')) ?>">
      <?php esc_html_e('Request for a quote?', 'easyorder') ?>
    </a>
    <div class="wc-easyorder-quote-alert-container">
      <div id="quote-message-sent" class="wc-easyorder-alert-success"></div>
    </div>
    <div id="quote-popup" class="white-popup mfp-hide mfp-quote-popup">
      <div id="quote-loader"><?php esc_html_e('Sending....', 'easyorder') ?></div>
      <form action="" class="quote-form" id="easyorder-quote-form">
        <div class="wc-easyorder-input-group">
          <label class="wc-easyorder-input-label" for="quote-first-name"><?php esc_html_e('First Name', 'easyorder') ?></label>
          <input type="text" name="quote-first-name" id="quote-first-name" placeholder="<?php esc_attr_e('First Name', 'easyorder') ?>" value="<?php echo esc_attr($customer_first_name) ?>" required />
        </div>
        <div class="wc-easyorder-input-group">
          <label class="wc-easyorder-input-label" for="quote-last-name"><?php esc_html_e('Last Name', 'easyorder') ?></label>
          <input type="text" name="quote-last-name" id="quote-last-name" placeholder="<?php esc_attr_e('Last Name', 'easyorder') ?>" value="<?php echo esc_attr($customer_last_name) ?>" required />
        </div>
        <div class="wc-easyorder-input-group">
          <label class="wc-easyorder-input-label" for="quote-email"><?php esc_html_e('Email', 'easyorder') ?></label>
          <input type="email" name="quote-email" id="quote-email" placeholder="<?php esc_attr_e('Your Email', 'easyorder') ?>" value="<?php echo esc_attr($customer_email) ?>" required />
        </div>
        <div class="wc-easyorder-input-group">
          <label class="wc-easyorder-input-label" for="quote-phone"><?php esc_html_e('Phone', 'easyorder') ?></label>
          <input type="text" name="quote-phone" id="quote-phone" placeholder="<?php esc_attr_e('Phone', 'easyorder') ?>" value="<?php echo esc_attr($customer_phone) ?>" required />
        </div>
        <div class="wc-easyorder-input-group wc-easyorder-input-group-full">
          <label class="wc-easyorder-input-label" for="quote-message"><?php esc_html_e('Message', 'easyorder') ?></label>
          <textarea name="quote-message" id="quote-message" placeholder="<?php esc_attr_e('Your message', 'easyorder') ?>"></textarea>
        </div>
        <input type="hidden" name="product_id" class="quote-product" value="<?php echo esc_attr($product_id) ?>">
        <div class="wc-easyorder-input-group wc-easyorder-input-group-full">
          <label class="wc-easyorder-checkbox-field" for="accept_gdpr">
            <input type="checkbox" name="accept_gdpr" id="accept_gdpr" required="true">
            <span class="wc-easyorder-checkbox-field-text">
              <?php esc_html_e('I accept the terms &amp; conditions', 'easyorder'); ?>
            </span>
          </label>
        </div>
        <div class="wc-easyorder-input-group wc-easyorder-input-group-full">
          <ul id="quote-validate"></ul>
        </div>
        <div class="wc-easyorder-input-group input-group-full">
          <button class="quote-submit wc-easyorder-quote-submit"><?php esc_html_e('Send Message', 'easyorder') ?></button>
        </div>
      </form>
    </div>
  <?php endif ?>
<?php endif ?>
