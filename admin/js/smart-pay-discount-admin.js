jQuery(document).ready(function($) {
    // Adiciona classe ao wrapper do formulário
    $('.wrap form').addClass('spd-settings-wrap');

    // Inicializa o Select2 para seleção de categorias
    $('.spd-categories-select').select2({
        width: '100%',
        placeholder: 'Selecione as categorias excluídas',
        allowClear: true,
        language: {
            noResults: function() {
                return "Nenhuma categoria encontrada";
            }
        }
    });

    // Adiciona descrições aos campos
    $('input[name="spd_settings[discount_percentage]"]').after(
        '<p class="description">' +
        'Defina o percentual de desconto que será aplicado quando o método de pagamento selecionado for utilizado.' +
        '</p>'
    );

    $('input[name="spd_settings[payment_methods][]"]').first().closest('td').append(
        '<p class="description">' +
        'Selecione quais métodos de pagamento devem conceder o desconto automático.' +
        '</p>'
    );

    $('.spd-categories-select').after(
        '<p class="description">' +
        'Selecione as categorias de produtos que NÃO devem receber o desconto automático.' +
        '</p>'
    );

    $('input[name="spd_settings[ignore_with_coupon]"]').after(
        '<p class="description">' +
        'Se marcado, o desconto automático não será aplicado quando houver um cupom de desconto no carrinho.' +
        '</p>'
    );
}); 