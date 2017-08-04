/* Add here all your JS customizations */
function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + (Math.round(n * k) / k).toFixed(prec);
            };

    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');

    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

(function () {

    "use strict";

    $.fn.Modal = function (text) {
        swal({
            title: '',
            html: text,
            allowOutsideClick: false,
            showConfirmButton: false,
            showCancelButton: false,
            width: 400,
            imageUrl: getbase()+'assets/images/preload.gif'
        });
    };

    $.fn.Alerta = function (title, text, error) {
        swal({
            title: title,
            type: error,
            html: text,
            allowOutsideClick: false,
            showCancelButton: false
        });
    };

    /**
     * 
     * @param {type} title
     * @param {type} text
     * @param {type} Callback = Função que ira ser chamada
     */
    $.fn.Confirm = function (title, text, Callback) {
        swal({
            title: title,
            html: text,
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não',
            allowOutsideClick: false
        }).then(function () {
            Callback();
        });
    };

//    $.fn.FormValidate = function (settings) {
//        var elem = this;
//        
//        var options = {
//            errorElement: 'span', //default input error message container
//            errorClass: 'help-block', // default input error message class
//            focusInvalid: false, // do not focus the last invalid input
//            ignore: "", // validate all fields including form hidden input
//            errorPlacement: function (error, element) { // render error placement for each input type
//                if (element.parent(".input-group").size() > 0) {
//                    error.insertAfter(element.parent(".input-group"));
//                } else if (element.attr("data-error-container")) {
//                    error.appendTo(element.attr("data-error-container"));
//                } else if (element.parents('.radio-list').size() > 0) {
//                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
//                } else if (element.parents('.radio-inline').size() > 0) {
//                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
//                } else if (element.parents('.checkbox-list').size() > 0) {
//                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
//                } else if (element.parents('.checkbox-inline').size() > 0) {
//                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
//                }else if(element.parent('.input-icon').size() > 0){
//                    error.insertAfter(element.closest('.input-icon'));
//                }else {
//                    error.insertAfter(element); // for other inputs, just perform default behavior
//                }
//            },
//            highlight: function (element) { // hightlight error inputs
//                $(element).closest('[class~="form-group"]').addClass('has-error'); // set error class to the control group
//            },
//            unhighlight: function (element) { // revert the change done by hightlight
//                $(element).closest('[class~="form-group"]').removeClass('has-error'); // set error class to the control group
//            },
//            success: function (label) {
//                label.closest('[class~="form-group"]').removeClass('has-error'); // set success class to the control group
//            }
//        };
//
//        $.extend(options, settings);
//
//        $(elem).validate(options);
//    };
    
    
    $.fn.AjaxModal = function(){        
        $('body').on('click', '[data-toggle="modal"]', function(e){
            e.preventDefault();
            var url   = ($(this).attr('data-url') === undefined ? $(this).attr('href') : $(this).attr('data-url'));
            var modal = $(this).attr('data-target');
            
            $(modal).load(url, function(){
               $(modal).modal(); 
            });            
        });
    };
    

})(jQuery);