<?php

class EasyOrderAssets {
  /**
   * Init class
   */
  public function __construct() {
    add_action('wp_enqueue_scripts', [$this, 'registerAssets'], 20);
    add_action('admin_enqueue_scripts', [$this, 'registerAdminAssets'], 10, 1);
  }

  /**
   * Get scripts
   *
   * @return array
   */
  public function getScripts() {
    return [
      'dokan-popup' => [
        'src'     => EASYORDER_ASSETS . '/js/magnific-popup.min.js',
        'version' => filemtime(EASYORDER_PATH . '/assets/js/magnific-popup.min.js'),
        'deps'    => ['jquery']
      ],
      'easyorder' => [
        'src'     => EASYORDER_ASSETS . '/js/easyorder.js',
        'version' => filemtime(EASYORDER_PATH . '/assets/js/easyorder.js'),
        'deps'    => ['jquery', 'dokan-popup']
      ],
    ];
  }

  /**
   * Get scripts
   *
   * @return array
   */
  public function getAdminScripts() {
    return [
      'easyorder-admin' => [
        'src'     => EASYORDER_ASSETS . '/js/easyorder-admin.js',
        'version' => filemtime(EASYORDER_PATH . '/assets/js/easyorder-admin.js'),
        'deps'    => ['jquery']
      ],
    ];
  }

  /**
   * Styles
   *
   * @return array
   */
  public function getStyles() {
    return [
      'dokan-popup' => [
        'src'           => EASYORDER_ASSETS . '/css/magnific-popup.css',
      ],
      'easyorder' => [
        'src'           => EASYORDER_ASSETS . '/css/easyorder.css',
      ],
    ];
  }

  /**
   * Register assets
   */
  public function registerAssets() {
    $scripts = $this->getScripts();
    $styles  = $this->getStyles();

    foreach ($scripts as $handle => $script) {
      $deps    = isset($script['deps']) ? $script['deps']      : false;
      $version = isset($script['version']) ? $script['version'] : EASYORDER_VERSION;

      wp_register_script($handle, $script['src'], $deps, $version, true);

      // wp_enqueue_script($handle);
    }

    foreach ($styles as $handle => $style) {
      $deps    = isset($style['deps']) ? $style['deps']      : false;
      $version = isset($style['version']) ? $style['version'] : EASYORDER_VERSION;
      $media = isset($style['media']) ? $style['media'] : false;

      wp_enqueue_style($handle, $style['src'], $deps, $version, $media);
    }

    if (!is_admin()) {
      wp_enqueue_script('easyorder');
      $post_id = get_the_ID();
      $rules = easyorder_has_price_rules($post_id);
      if (!empty($rules)) {
        $_product = wc_get_product($post_id);
        wp_localize_script('easyorder', 'EASYORDER', [
          'ajax_url' => admin_url('admin-ajax.php'),
          'priceRules' => $rules,
          'currency' => get_woocommerce_currency_symbol(),
          'price' => $_product->get_price(),
        ]);
      } else {
        wp_localize_script('easyorder', 'EASYORDER', []);
      }
    }
  }

  public function registerAdminAssets($hook) {
    global $post;

    $scripts = $this->getAdminScripts();
    foreach ($scripts as $handle => $script) {
      $deps    = isset($script['deps']) ? $script['deps']      : false;
      $version = isset($script['version']) ? $script['version'] : EASYORDER_VERSION;

      wp_register_script($handle, $script['src'], $deps, $version, true);

      wp_enqueue_script($handle);
    }

    wp_enqueue_style('easyorder', EASYORDER_ASSETS . '/css/easyorder-admin.css', false, false, false);
  }
}

new EasyOrderAssets();
