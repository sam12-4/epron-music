  <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
          <td align="center" valign="top">
              <center>

                  <table width="100%" style="background-color:#ffffff;border-bottom:1px solid #e5e5e5;">
                      <tr>
                          <td align="center">
                              <center style="padding:0 0 50px 0;">

                                  <table width="70%" style="margin:0 auto;">
                                      <tr>
                                          <td>

                                              <table>
                                                  <tr>
                                                      <td>
                                                          <h2 align="left" style="font-family:Georgia,Cambria,'Times New Roman',serif;font-size:32px;font-weight:300;line-height: normal;padding: 35px 0 0;color: #4d4d4d;">
                                                              <?php printf(esc_html__('Quote #%s Details', 'easyorder'), $quote['id']); ?>
                                                          </h2>
                                                      </td>
                                                  </tr>
                                                  <tr>
                                                      <td style="border-collapse: collapse;width: 70%;padding-top: 20px;text-align: left;vertical-align: top;">
                                                          <table cellspacing="0" cellpadding="0" width="100%">
                                                              <tbody>
                                                                  <tr>
                                                                      <td style="border-collapse: collapse;text-align: left;vertical-align: top;width: 90%">
                                                                          <span style="color: #4d4d4d; font-weight:bold;"><?php echo wpautop(wptexturize($product_title)) ?> <strong> x <?php echo esc_html(get_post_meta($quote_id, '_min_quantity', true)) ?></strong></span>
                                                                      </td>
                                                                  </tr>
                                                              </tbody>
                                                          </table>
                                                      </td>
                                                      <td style="border-collapse: collapse;padding-top: 20px;text-align: left;vertical-align: top; width: 10%;">
                                                          <?php echo wc_price(get_post_meta($quote_id, '_quote_price', true)); ?>
                                                      </td>
                                                  </tr>


                                                  <!-- Start customer details part -->
                                                  <tr>
                                                      <td>
                                                          <h2 align="left" style="font-family:Georgia,Cambria,'Times New Roman',serif;font-size:32px;font-weight:300;line-height: normal;padding: 35px 0 0;color: #4d4d4d;">
                                                              <?php echo esc_html__('Customer Details', 'easyorder'); ?>
                                                          </h2>
                                                      </td>
                                                  </tr>
                                                  <tr>
                                                      <td style="border-collapse: collapse;width: 70%;padding-top: 20px;text-align: left;vertical-align: top;">
                                                          <table cellspacing="0" cellpadding="0" width="100%">
                                                              <tbody>
                                                                  <tr>
                                                                      <td style="border-collapse: collapse;text-align: left;vertical-align: top;width: 90%">
                                                                          <dl class="variation">
                                                                              <?php
                                                                                //Retrieve customer data

                                                                                $customer_info = easyorder_get_quote_customer_data($quote_id);
                                                                                
                                                                                $customer_first_name = easyorder_get_quote_customer_input('quote-first-name', $customer_info);
                                                                                $customer_last_name = easyorder_get_quote_customer_input('quote-last-name', $customer_info);
                                                                                $customer_email = easyorder_get_quote_customer_input('quote-email', $customer_info);
                                                                                $customer_phone = easyorder_get_quote_customer_input('quote-phone', $customer_info);
                                                                                $customer_first_name = easyorder_get_quote_customer_input('quote-first-name', $customer_info);
                                                                                ?>

                                                                              <?php if (!empty($customer_first_name)) : ?>
                                                                                  <dt style="float: left;margin-right: 10px;"><?php echo esc_html__('First Name', 'easyorder'); ?>:</dt>
                                                                                  <dd>
                                                                                      <p><strong><?php echo esc_html($customer_first_name); ?></strong></p>
                                                                                  </dd>
                                                                              <?php endif; ?>

                                                                              <?php if (!empty($customer_last_name)) : ?>
                                                                                  <dt style="float: left;margin-right: 10px;"><?php echo esc_html__('Last Name', 'easyorder'); ?>:</dt>
                                                                                  <dd>
                                                                                      <p><strong><?php echo esc_html($customer_last_name); ?></strong></p>
                                                                                  </dd>
                                                                              <?php endif; ?>

                                                                              <?php if (!empty($customer_email)) : ?>
                                                                                  <dt style="float: left;margin-right: 10px;"><?php echo esc_html__('Email', 'easyorder'); ?>:</dt>
                                                                                  <dd>
                                                                                      <p><strong><?php echo esc_html($customer_email); ?></strong></p>
                                                                                  </dd>
                                                                              <?php endif; ?>

                                                                              <?php if (!empty($customer_phone)) : ?>
                                                                                  <dt style="float: left;margin-right: 10px;"><?php echo esc_html__('Phone ', 'easyorder'); ?>:</dt>
                                                                                  <dd>
                                                                                      <p><strong><?php echo esc_html($customer_phone); ?></strong></p>
                                                                                  </dd>
                                                                              <?php endif; ?>
                                                                          </dl>
                                                                      </td>
                                                                  </tr>
                                                              </tbody>
                                                          </table>
                                                      </td>
                                                  </tr>
                                                  <!-- End customer details part -->


                                              </table>
                                          </td>
                                      </tr>
                                  </table>

                              </center>
                          </td>
                      </tr>
                  </table>

              </center>
          </td>
      </tr>
  </table>
