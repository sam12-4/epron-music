<?php $easyorder_pricetiers = get_post_meta($post_id, '_easyorder_pricetiers', true); ?>
<?php $currency_symbol = get_woocommerce_currency_symbol() ?>
<div class="wc-easyorder-tier-pricing">

  <label for="wc-easyorder-tiered-pricing" class="wc-easyorder-tier-price-title">
    <strong><?php esc_html_e('Price Tiers', 'easyorder') ?></strong>
  </label>

  <div class="wc-easyorder-tier-body">
    <?php if (!is_array($easyorder_pricetiers)) :  ?>
      <div class="wc-easyorder-price-tier">
        <div class="wc-easyorder-price-tier-input dokan-input-group">
          <span class="wc-easyorder-input-group-addon">&lt;&gt;</span>
          <input type="text" class="wc-easyorder-tiered-pricing_element dokan-product-regular-price wc_input_price dokan-form-control" name="easyorder_pricetiers_quantity[]" placeholder="<?php esc_attr_e('Min. Quantity', 'easyorder') ?>">
        </div>

        <div class="wc-easyorder-price-tier-input dokan-input-group">
          <span class="wc-easyorder-input-group-addon"><?php echo esc_html($currency_symbol); ?></span>
          <input type="text" class="wc-easyorder-tiered-pricing_element dokan-product-sales-price wc_input_price dokan-form-control" name="easyorder_pricetiers_price[]" placeholder="<?php esc_attr_e('Final Price', 'easyorder') ?>">
        </div>
      </div>
    <?php else : ?>
      <?php foreach ($easyorder_pricetiers as $easyorder_pricetiers) : ?>
        <div class="wc-easyorder-price-tier">
          <div class="wc-easyorder-price-tier-input dokan-input-group">
            <span class="wc-easyorder-input-group-addon">&lt;&gt;</span>
            <input type="text" class="wc-easyorder-tiered-pricing_element dokan-product-regular-price wc_input_price dokan-form-control" value="<?php echo esc_attr($easyorder_pricetiers['quantity']) ?>" name="easyorder_pricetiers_quantity[]" placeholder="<?php esc_attr_e('Min. Quantity', 'easyorder') ?>">
          </div>

          <div class="wc-easyorder-price-tier-input dokan-input-group">
            <span class="wc-easyorder-input-group-addon"><?php echo esc_html($currency_symbol); ?></span>
            <input type="text" class="wc-easyorder-tiered-pricing_element dokan-product-sales-price wc_input_price dokan-form-control" value="<?php echo esc_attr($easyorder_pricetiers['price']) ?>" name="easyorder_pricetiers_price[]" placeholder="<?php esc_attr_e('Final Price', 'easyorder') ?>">
          </div>

          <a href="javascript:;" class="wc-easyorder-remove-price-tier-group">
            <svg enable-background="new 0 0 32 32" height="20px" viewBox="0 0 32 32" width="20px" xml:space="preserve">
              <path d="M17.459,16.014l8.239-8.194c0.395-0.391,0.395-1.024,0-1.414c-0.394-0.391-1.034-0.391-1.428,0  l-8.232,8.187L7.73,6.284c-0.394-0.395-1.034-0.395-1.428,0c-0.394,0.396-0.394,1.037,0,1.432l8.302,8.303l-8.332,8.286  c-0.394,0.391-0.394,1.024,0,1.414c0.394,0.391,1.034,0.391,1.428,0l8.325-8.279l8.275,8.276c0.394,0.395,1.034,0.395,1.428,0  c0.394-0.396,0.394-1.037,0-1.432L17.459,16.014z" fill="#121313" id="Close" />
            </svg>
          </a>
        </div>
      <?php endforeach ?>
    <?php endif ?>
  </div>


  <span class="wc-easyorder-add-price-tier">
    <button type="button" class="dokan-btn dokan-btn-default easyorder_product_add_tier"
      data-row="<?php ob_start();
      include('inputGroup.php');
      $html = ob_get_clean();
      echo esc_attr($html); ?>">
      <?php esc_html_e('Add Tier', 'easyorder') ?>
    </button>
  </span>
</div>
