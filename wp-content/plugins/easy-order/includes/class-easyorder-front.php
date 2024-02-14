<?php

class EasyOrderFront
{
    /**
     * Init class
     */
    public function __construct()
    {
        add_filter('woocommerce_add_cart_item', array($this, 'addToCartItem'), 10, 1);
        add_filter('woocommerce_get_cart_item_from_session', array($this, 'getCartItemFromSession'), 10, 2);
    }

    public function addToCartItem($cartItem)
    {
        $product_id = $cartItem['data']->get_id();
        $defaultPrice = $cartItem['data']->get_price();

        $qtyBasedPrice = easyorder_get_qty_based_price($product_id, $cartItem['quantity'], $defaultPrice);

        $cartItem['data']->set_price($qtyBasedPrice['price']);

        return $cartItem;
    }

    public function getCartItemFromSession($cartItem, $values)
    {
        $cartItem = $this->addToCartItem($cartItem);

        return $cartItem;
    }
}

new EasyOrderFront();
