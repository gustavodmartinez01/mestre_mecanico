/**
 * Funções de Gerenciamento de Itens da OS
 * Oficina Bueno & Martínez
 */

function inicializarSelect2(tipo, baseUrl, minimumInput = 4) {
    const label = (tipo === 'produto') ? 'Selecionar Produto' : 'Selecionar Serviço';
    $('#label_item').text(label);
    
    if ($('#select_item_busca').data('select2')) {
        $('#select_item_busca').select2('destroy');
    }

    $('#select_item_busca').select2({
        theme: 'bootstrap4',
        placeholder: 'Digite pelo menos ' + minimumInput + ' caracteres...',
        allowClear: true,
        minimumInputLength: minimumInput,
        // ESTA LINHA RESOLVE O PROBLEMA DO FOCO:
        dropdownParent: $('#modalNovoItem'), 
        language: {
            inputTooShort: function (args) {
                var remaining = args.minimum - args.input.length;
                return "Por favor, digite mais " + remaining + " caracteres...";
            },
            noResults: function () { return "Nenhum item encontrado."; },
            searching: function () { return "Buscando..."; }
        },
        ajax: {
            url: baseUrl + 'os/buscar_itens_json/' + tipo,
            dataType: 'json',
            delay: 300,
            data: function (params) { return { q: params.term }; },
            processResults: function (data) { return { results: data }; }
        }
    });
}



function calcularSubtotal() {
    const qtd = parseFloat($('#item_qtd').val()) || 0;
    const preco = parseFloat($('#item_preco').val()) || 0;
    const subtotal = qtd * preco;
    
    $('#exibir_subtotal').text(
        subtotal.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })
    );
}




// Escuta mudanças nos inputs de valor e quantidade
$(document).on('input', '#item_qtd, #item_preco', function() {
    calcularSubtotal();
});