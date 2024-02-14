'use strict';
(function ($) {
  $('.easyorder_product_add_tier').on('click', function (e) {
    e.preventDefault();
    $('.wc-easyorder-tier-body').append($(this).data('row'));
    $('body').trigger('row_added');
    removePriceTier();
    return false;
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
    let newHeight = 4 + numberOfLineBreaks * 20 + 12 + 2;
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

