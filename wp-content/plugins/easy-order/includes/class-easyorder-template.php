<?php

class EasyOrderTemplate
{
    /**
     * Init class
     */
    public function __construct()
    {
        add_action('dokan_product_edit_after_pricing', array($this, 'addQtyBasedPriceTemplate'), 10, 2);
        add_action('woocommerce_after_add_to_cart_form', array($this, 'qtyBasedPriceRules'), 10);

        add_action( 'dokan_product_updated', [$this, 'saveProductMeta'], 10, 2 );
    }

    /**
     * Quantity based price template
     *
     * @return void
     */
    public function addQtyBasedPriceTemplate( $post, $post_id )
    {
        $template = __DIR__ . '/views/qtyBasedPrice.php';

        if (file_exists($template)) {
            include $template;
        }
    }

    /**
     * Quantity based price rules
     *
     * @return void
     */
    public function qtyBasedPriceRules()
    {
        $template = __DIR__ . '/views/qtyBasedPriceRule.php';

        if (file_exists($template)) {
            include $template;
        }
    }

    public function saveProductMeta($post_id, $postdata) {
        if( ! dokan_is_user_seller( get_current_user_id() ) ) {
            return;
        }

        $easyorder_pricetiers_quantity = (isset($postdata['easyorder_pricetiers_quantity'])) ? $postdata['easyorder_pricetiers_quantity'] : [];
        $easyorder_pricetiers_price = (isset($postdata['easyorder_pricetiers_price'])) ? $postdata['easyorder_pricetiers_price'] : [];
        $easyorder_pricetiers = [];
        
        for($i=0; $i<count($easyorder_pricetiers_quantity); $i++) {
            if( !empty($easyorder_pricetiers_quantity[$i]) && !empty($easyorder_pricetiers_price[$i])) {
                $easyorder_pricetiers[] = [
                    'quantity' => $easyorder_pricetiers_quantity[$i],
                    'price' => $easyorder_pricetiers_price[$i]
                ];
            }
        }

        update_post_meta($post_id, '_easyorder_pricetiers', $easyorder_pricetiers);

    }
}

new EasyOrderTemplate();
