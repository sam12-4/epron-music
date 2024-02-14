<?php

add_filter('dokan_query_var_filter', 'easyorder_dokan_load_document_menu');
function easyorder_dokan_load_document_menu($query_vars) {
  $query_vars['message'] = 'message';
  $query_vars['view-message'] = 'view-message';
  return $query_vars;
}

add_filter('dokan_get_dashboard_nav', 'easyorder_dokan_add_message_menu');
function easyorder_dokan_add_message_menu($urls) {
  $urls['message'] = array(
    'title' => esc_html__('Message', 'easyorder'),
    'icon'  => '<i class="fa fa-envelope"></i>',
    'url'   => dokan_get_navigation_url('message'),
    'pos'   => 51
  );
  return $urls;
}

add_action('dokan_load_custom_template', 'easyorder_dokan_load_template');
function easyorder_dokan_load_template($query_vars) {
  if (isset($query_vars['message'])) {
    
    wc_get_template('dokan/message.php', $args = array(), $template_path = '', EASYORDER_TEMPLATE_PATH);
  }

  if (isset($query_vars['view-message'])) {
    $message_id = $query_vars['view-message'];
    wc_get_template('dokan/view-message.php', array(
      'message_id' => $message_id
    ), $template_path = '', EASYORDER_TEMPLATE_PATH);
  }
}
