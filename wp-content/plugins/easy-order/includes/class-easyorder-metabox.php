<?php

class EasyOrderMetabox {
  /**
   * Init class
   */
  public function __construct() {
    add_action('add_meta_boxes', array($this, 'register_meta_boxes'));
  }

  /**
   * Availability management meta box define
   * @param callback redq_inventory_availability_control_cb, id redq_inventory_availability_control
   * @author RedQTeam
   * @version 2.0.0
   * @since 2.0.0
   */
  public function register_meta_boxes() {
    remove_meta_box('submitdiv', 'easyorder_message', 'side');
    add_meta_box(
      'easyorder_quote_action',
      esc_html__('Quote Actions', 'easyorder'),
      'easyorder_quote_message_save_cb',
      'easyorder_message',
      'side',
      'high'
    );

    add_meta_box(
      'easyorder_quote_message',
      esc_html__('Request For A Quote Message', 'easyorder'),
      'easyorder_quote_message_cb',
      'easyorder_message',
      'normal',
      'high'
    );
  }
}

new EasyOrderMetabox();
