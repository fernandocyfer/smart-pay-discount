(function($) {
    'use strict';

    $(document).ready(function() {
        // Atualiza o desconto quando o método de pagamento é alterado
        $(document.body).on('payment_method_selected', function() {
            var paymentMethod = $('input[name="payment_method"]:checked').val();
            
            if (paymentMethod) {
                $.ajax({
                    url: spd_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'update_payment_discount',
                        payment_method: paymentMethod,
                        nonce: spd_ajax.nonce
                    },
                    success: function(response) {
                        if (response.fragments) {
                            $.each(response.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
                        }
                    }
                });
            }
        });
    });
})(jQuery); 