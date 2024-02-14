'use strict';
(function ($) {
  $('.easyorder_product_add_tier').on('click', function (e) {
    e.preventDefault();
    $('.wc-easyorder-tier-body').append($(this).data('row'));
    $('body').trigger('row_added');
    removePriceTier();
    return false;
  });

  const loading = (selector) => {
    $(selector).block({
      message: null,
      overlayCSS: {
        background: '#fff',
        opacity: 0.6,
      },
    });
  };

  const unLoading = (selector) => {
    $(selector).unblock();
  };

  $('form.cart')
    .on('change', 'input.qty', function (e) {
      // e.preventDefault();
      
      const qty = $(this).val();

      $.each(EASYORDER.priceRules, function (key, rules) {
        if (
          (parseInt(qty) >= parseInt(rules.min_qty) &&
            parseInt(qty) <= parseInt(rules.max_qty)) ||
          (qty >= parseInt(rules.min_qty) &&
            parseInt(rules.min_qty) === parseInt(rules.max_qty))
        ) {
          const newPrice =
            '<bdi><span class="woocommerce-Price-currencySymbol">' +
            EASYORDER.currency +
            '</span>' +
            rules.price +
            '</bdi>';
          $('.price .woocommerce-Price-amount').html(newPrice);
          return false;
        } else {
          const newPrice =
            '<bdi><span class="woocommerce-Price-currencySymbol">' +
            EASYORDER.currency +
            '</span>' +
            EASYORDER.price +
            '</bdi>';
          $('.price .woocommerce-Price-amount').html(newPrice);
        }
      });
    })
    .change();

  $('.open-popup-link').magnificPopup({
    type: 'inline',
    midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
  });

  $('.quote-submit').on('click', function (e) {
    e.preventDefault();
    const self = $(this);
    let validateError = [];
    let validateHTML = '';
    $('#quote-validate').html('');
    $('#quote-message-sent').html('');
    // if ($('#accept_gdpr').is(':checked')) {
    const formData = $('.quote-form').serializeArray();

    $.each(formData, function (index, data) {
      if (data.value === '') {
        let inputName = data.name;
        inputName = inputName.replace('quote-', '');
        inputName = inputName.replace('-', ' ');
        validateHTML += '<li>' + inputName + ' field is requied</li>';
        validateError.push(data.name);
      }
    });

    if (!$('#accept_gdpr').is(':checked')) {
      validateHTML += '<li>You need accept the terms & conditions</li>';
      validateError.push('accept_gdpr');
    }

    if ($.isEmptyObject(validateError)) {
      const productID = $('.quote-product').val();

      const quote_params = {
        action: 'easyorder_request_for_a_quote',
        form_data: formData,
        product_id: productID,
      };

      self.attr('disabled', true);

      $.ajax({
        url: EASYORDER.ajax_url,
        dataType: 'json',
        type: 'POST',
        data: quote_params,
        beforeSend: function () {
          // Show image container
          $('#quote-loader').css('display', 'flex');
        },
        success: function (response) {
          if (response.status_code === 200) {
            $('#quote-message-sent').html(response.message);
            $('#quote-message').val('');
            self.attr('disabled', false);
          }
        },
        complete: function (data) {
          // Hide image container
          $('#quote-loader').hide();
          $.magnificPopup.close();
          $('#easyorder-quote-form').trigger('reset');
        },
      });
    } else {
      $('#quote-validate').html(validateHTML);

      validateError.forEach(function (item) {
        $('#' + item).addClass('error');
      });
    }
    
  });

  // scroll to bottom of message container when page load
  const messageContainer = document.getElementById('wc-easyorder-quote-message-list');
  if (typeof messageContainer != 'undefined' && messageContainer != null) {
    messageContainer.scrollTop = messageContainer.scrollHeight;
  }

  /**
   * Auto-Growing Inputs & Textareas
   * @link https://css-tricks.com/auto-growing-inputs-textareas/
   */
  // Dealing with Textarea Height
  function calcHeight(value) {
    let numberOfLineBreaks = (value.match(/\n/g) || []).length;
    // min-height + lines x line-height + padding + border
    let newHeight = 7 + numberOfLineBreaks * 20 + 12 + 2;
    return newHeight;
  }

  let textarea = document.querySelector('.add-quote-message');
  if (typeof textarea != 'undefined' && textarea != null) {
    textarea.addEventListener('keyup', () => {
      textarea.style.height = calcHeight(textarea.value) + 'px';
    });
  }


  removePriceTier();
  function removePriceTier() {
    $('.wc-easyorder-remove-price-tier-group').on('click', function() {
      $(this).parent().remove();
    });
  }
})(jQuery);
