<?php global $product;
$rules = easyorder_get_price_rules($product->get_id()); ?>
<?php if (!empty($rules)) : ?>
  <table class="shop_table shop_table_responsive easyorder_shop_table">
    <thead>
      <tr>
        <th><?php esc_html_e('Product Quantity', 'easyorder') ?></th>
        <th><?php esc_html_e('Price per Unit', 'easyorder') ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rules as $rule) : ?>
        <tr>
          <?php if ($rule['min_qty'] === $rule['max_qty']) : ?>
            <td><?php echo esc_html($rule['min_qty']); ?>+</td>
          <?php else : ?>
            <td><?php echo esc_html($rule['min_qty']); ?> - <?php echo esc_html($rule['max_qty']); ?></td>
          <?php endif ?>
          <td><?php echo wc_price($rule['price']); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
