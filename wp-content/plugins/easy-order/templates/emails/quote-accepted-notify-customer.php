<?php include(EASYORDER_TEMPLATE_PATH.'emails/email-header.php'); ?>
  <?php extract($quote) ?>
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" valign="top">
        <center>

          <table width="100%" style="background:#F7F7F7;border-bottom:1px solid #E5E5E5;">
            <tr>
              <td align="center">
                <center style="padding:0 0 50px 0;">

                  <table width="70%" style="margin:0 auto;">
                    <tr>
                      <td>

                        <table>
                          <tr>
                            <td>
                              <h2 align="left" style="font-family:Georgia,Cambria,'Times New Roman',serif;font-size:32px;font-weight:300;line-height: normal;padding: 35px 0 0;color: #4d4d4d;"><?php echo esc_html( $heading ) ?></h2>
                            </td>
                          </tr>
                          <tr>
                            <td align="left" style="color:#777777;font-size:14px;line-height:21px;font-weight:400;">
                              <p><?php esc_html_e( "Hello, your quote request has been accepted.You can now book as the listed price.", 'easyorder' ); ?></p>

                              <p><?php esc_html_e( "You can now click the book now button in the view quote page. Then checkout the product.", 'easyorder' ); ?></p>
                            </td>
                          </tr>
                          <tr>
                            <td style="text-align:center;padding:30px 0 15px;">
                              <a href="<?php echo esc_url( $customer_view_quote ) ?>" style="background-color:#E74C3C;border-radius:0;color:#ffffff;display:inline-block;font-size:14px;font-weight:normal;line-height:40px;text-align:center;text-decoration:none;width:300px;-webkit-text-size-adjust:none;mso-hide:all;text-transform:uppercase"><?php esc_html_e('View Quote & Order Now','easyorder'); ?></a></div>
                            </td>
                          </tr>
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
  
<?php

include(EASYORDER_TEMPLATE_PATH . 'emails/request-quote-item-details.php');
  
include(EASYORDER_TEMPLATE_PATH.'emails/email-footer.php');
