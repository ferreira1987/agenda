$(function () {
    $.fn.select2.defaults.set("theme", "bootstrap");
    $.fn.AjaxModal();

    $('[class~="touchspin"]').TouchSpin();
    $('[class~="select2"]').select2();
    $('.input-group.date').datepicker({
        format: "dd/mm/yyyy",
        todayHighlight: true,
        language: "pt-BR",
        autoclose: true
    });

    $('[class~="datepicker"]').inputmask("99/99/9999");

    $('body').on('focus', '[data-rel^="Money"]', function () {
        $(this).maskMoney({prefix: '', allowNegative: false, thousands: '.', decimal: ',', affixesStay: false});
    });

    // EFETUA BUSCA POR PRODUTO
    $('.select-ajax').select2({
        width: "off",
        placeholder: "Nome ou código do produto",
        ajax: {
            url: getBase() + "control.php",
            type: "POST",
            dataType: "json",
            delay: 250,
            data: function (params) {
                return {
                    query: params.term,
                    page: params.page,
                    action: "SearchProduto"
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return{
                            text: item.descricao,
                            id: item.codigo
                        };
                    })
                };
            }
        },
        minimumInputLength: 2
    }).on("change", function (e) {
        var codigo = $(this).val();

        $.ajax({
            url: getBase() + "control.php",
            type: "POST",
            dataType: "json",
            data: {query: codigo, action: "SearchProduto"},
            beforeSend: function () {
                $.fn.Modal('Aguarde...');
            },
            success: function (data) {
                $('input[name="qtde"]').val('1').focus();
                $('input[name="preco"]').val(number_format(data[0].preco, 2, ',', '.'));
                swal.closeModal();
            }
        });
    });

    // EFETUA BUSCA POR CLIENTE
    $('.terceiro').select2({
        width: "off",
        placeholder: "Nome ou código do terceiro",
        ajax: {
            url: getBase() + "control.php",
            type: "POST",
            dataType: "json",
            delay: 250,
            data: function (params) {
                return {
                    razao: params.term,
                    page: params.page,
                    action: "terceiro"
                };
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data, function (item) {
                        return{
                            text: item.codigo + ' - ' + item.razao + " - CPF/CNPJ: " + item.cnpj,
                            id: item.codigo
                        };
                    })
                };
            }
        },
        minimumInputLength: 2
    });

    // ADICIONA PRODUTO AO PEDIDO
    $('form[name="product"]').FormValidate({
        rules: {
            qtde: {
                min: 1
            }
        },
        submitHandler: function (form) {
            var codigo = $('select[name="produto"]').val();
            var descricao = $('select[name="produto"] > option:selected').text();
            var qtde = $('input[name="qtde"]').val();
            var valor = $('input[name="preco"]').val().replace(',', '.');
            var desc = (!$.isEmptyObject($('input[name="desconto"]').val()) ? $('input[name="desconto"]').val().replace(',', '.') : 0);
            var total = parseFloat(qtde) * parseFloat(valor);
            var valorDesc = total * desc / 100;
            var TotalDesc = total - valorDesc;
            var result = false;
            var NameOrder = $('input[name="NameOrder"]').val();

            $.ajax({
                url: getBase() + "control.php",
                type: "POST",
                data: $(form).serialize() + "&descricao=" + descricao + "&action=OrderItem",
                dataType: "json",
                beforeSend: function () {
                    $.fn.Modal('Aguarde...');
                },
                success: function (data) {
                    // Verifica se já existe item no pedido
                    $('#TableProduct').find('tbody').find('tr').each(function () {
                        if ($(this).find('td').eq(0).text() === codigo) {
                            result = this;
                        }
                    });

                    if (result !== false) {
                        var desconto = (data.item.desc_valor > 0 ? " (desconto de R$ " + number_format(data.item.desc_valor, 2, ',', '.') + ")" : "");
                        $(result).find('td').eq(1).find('small').html(data.item.qtde + ' UN X R$ ' + number_format(data.item.preco, 2, ',', '.') + desconto);
                        $(result).find('td').eq(2).html('<span class="bold">R$ ' + number_format(data.item.total_liquido, 2, ',', '.') + '</span>');
                    } else {
                        var btn_edit = '<button type="button" class="btn btn-circle btn-default btn-xs" data-target="#ajax-modal" data-url="' + getBase() + '_modal/alter_item_pedido.php?NameOrder=' + NameOrder + '&codigo=' + codigo + '" data-toggle="modal"><i class="fa fa-pencil"></i></button>';
                        var btn_del = '<button type="button" class="btn btn-circle btn-danger btn-xs delete" value="' + codigo + '"><i class="fa fa-trash"></i></button>';
                        var desconto = (desc > 0 ? " (desconto de R$ " + number_format(valorDesc, 2, ',', '.') + ")" : "");

                        var row = '<tr><td>' + codigo + '</td>';
                        row += '<td><span>' + descricao + '</span> <br/> <small>' + qtde + ' UN X R$ ' + number_format(valor, 2, ',', '.') + desconto + '</small></td>';
                        row += '<td><span class="bold">R$ ' + number_format(TotalDesc, 2, ',', '.') + '</span></td>';
                        row += '<td class="center-align">' + btn_edit + ' ' + btn_del + '</td></tr>';

                        $('#TableProduct').find('tbody').append(row);
                    }

                    $('[class~="itens"]').html(data.sum.QtdeTotal);
                    $('[class~="subtotal"]').html('R$ ' + number_format(data.sum.TotalBruto, 2, ',', '.'));
                    $('[class~="total"]').html('R$ ' + number_format(data.sum.TotalLiquido, 2, ',', '.'));

                    $(form).trigger("reset");
                    $(form).find('.select2-selection__rendered').html('<span class="select2-selection__placeholder">Nome ou código do produto</span>');
                    $(form).find('.select2-selection__rendered').closest('.form-group').find('select[name="produto"]').val('');
                    $(form).find('select[name="produto"]').select2('open');

                    swal.closeModal();
                }
            });
        }
    });

    // REMOVE PRODUTO DO PEDIDO
    $('body').on('click', '[class~="delete"]', function () {
        var id = $(this).val();
        var elem = this;
        var NameOrder = $('input[name="NameOrder"]').val();

        $.fn.Confirm('', 'Deseja realmente excluir este item?', function () {
            $.ajax({
                url: getBase() + "control.php",
                type: "POST",
                data: {action: 'DeleteItem', codigo: id, NameOrder: NameOrder},
                dataType: "json",
                beforeSend: function () {
                    $.fn.Modal('Aguarde...');
                },
                success: function (data) {
                    swal.closeModal();
                    $(elem).closest('tr').remove();

                    $('[class~="itens"]').html(data.sum.QtdeTotal);
                    $('[class~="subtotal"]').html('R$ ' + number_format(data.sum.TotalBruto, 2, ',', '.'));
                    $('[class~="total"]').html('R$ ' + number_format(data.sum.TotalLiquido, 2, ',', '.'));
                }
            });
        });
    });

    // FINALIZA O PEDIDO
    $('form[name="form-items"]').FormValidate({
        submitHandler: function (form) {
            if ($('#TableProduct').find('tbody').find('tr').length > 0) {
                $.ajax({
                    url: getBase() + "control.php",
                    type: "POST",
                    data: $(form).serialize(),
                    beforeSend: function () {
                        $.fn.Modal('Aguarde...');
                    },
                    success: function (data) {
                        swal.closeModal();
                        $("#full-width").html(data).modal('show');
                    }
                });
            } else {
                $.fn.Alerta("Atenção!", "Adicione itens ao seu pedido.", "info");
            }
        }
    });

    // REMOVE TODOS ITENS DO PEDIDO
    $('[data-rel="ClearOrder"]').click(function () {
        if ($('#TableProduct').find('tbody').find('tr').length > 0) {
            $.fn.Confirm('', 'Deseja realmente excluir todos itens?', function () {
                var NameOrder = $('input[name="NameOrder"]').val();

                $.ajax({
                    url: getBase() + "control.php",
                    type: "POST",
                    data: {action: "ClearOrder", NameOrder: NameOrder},
                    dataType: "json",
                    beforeSend: function () {
                        $.fn.Modal('Aguarde...');
                    },
                    success: function (data) {
                        $('#TableProduct').find('tbody').html('');
                        $('[class~="itens"]').html(data.sum.QtdeTotal);
                        $('[class~="subtotal"]').html('R$ ' + number_format(data.sum.TotalBruto, 2, ',', '.'));
                        $('[class~="total"]').html('R$ ' + number_format(data.sum.TotalLiquido, 2, ',', '.'));

                        swal.closeModal();
                    }
                });
            });
        } else {
            $.fn.Alerta("Atenção!", "Não há itens à ser excluido.", "error");
        }
    });

    // EFETUA LANÇAMENTO DE NOTA
    $('[class~="order-close"]').click(function () {
        var regex = /^\d+$/;
        var numero = $('input[name="numero"]').val();
        var emissao = $('input[name="emissao"]').val();
        var entrada = $('input[name="entrada"]').val();
        var fornec = $('select[name="fornecedor"]').val();
        var serie = $('input[name="serie"]').val();
        var erro = 0;
        var msg = '';

        if ($('#TableProduct').find('tbody').find('tr').length > 0) {
            if ($.isEmptyObject(numero) ||
                    !regex.test(numero)) {
                msg += '<span class="display-block">- Número da Nota</span>';
                erro++;
            }
            if ($.isEmptyObject(emissao)) {
                msg += '<span class="display-block">- Data de Emissão</span>';
                erro++;
            }
            if ($.isEmptyObject(entrada)) {
                msg += '<span class="display-block">- Data de Entrada</span>';
                erro++;
            }
            if ($.isEmptyObject(fornec)) {
                msg += '<span class="display-block">- Fornecedor</span>';
                erro++;
            }
            if (!$.isEmptyObject(serie) && !regex.test(serie)) {
                msg += '<span class="display-block">- Série, digite apenas números.</span>';
                erro++;
            }

            if (erro > 0) {
                $.fn.Alerta("Campos obrigatório!", msg, "warning");
            } else {
                var $form = $('form[name="dados-nfe"]');
                
                $.ajax({
                    url: getBase() + "control.php",
                    type: "POST",
                    data: $form.serialize() + "&action=LancarNota",
                    dataType: "json",
                    beforeSend: function () {
                        $.fn.Modal('Aguarde...');
                    },
                    success: function (data) {
                        $.fn.Alerta(data.title, data.message, data.type);
                        
                        if (data.type === 'success') {
                            $('#TableProduct').find('tbody').html('');
                            $form.trigger("reset");
                            $form.find('.select2-selection__rendered').html('<span class="select2-selection__placeholder">Nome ou código do produto</span>');
                            $form.find('.select2-selection__rendered').closest('.form-group').find('select[name="produto"]').val('');
                            $('[class~="itens"]').html(0);
                            $('[class~="subtotal"]').html('R$ ' + number_format(0, 2, ',', '.'));
                            $('[class~="total"]').html('R$ ' + number_format(0, 2, ',', '.'));
                        }
                    }
                });
            }
        } else {
            $.fn.Alerta("Atenção!", "Adicione itens ao seu pedido.", "info");
        }

    });

});

