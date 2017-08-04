var AppCalendar = function () {

    return {
        //main function to initiate the module
        init: function () {
            this.initCalendar();
        },
        initCalendar: function () {

            if (!jQuery().fullCalendar) {
                return;
            }

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            var options = {
                header: {
                    left: 'title',
                    center: '',
                    right: 'prev,next,today,month,agendaWeek,agendaDay,listWeek'
                },
                defaultView: 'month',
                editable: false,
                droppable: false,
                businessHours: true,
                selectable: true,
                events: getbase() + 'control.php?action=GetEvent',
                eventClick: function (event, element) {
                    $("#ajax-modal").load(getbase() + 'modal/evento.php?id=' + event.id, function () {
                        $("#ajax-modal").modal();
                    });
                }
            };

            $('#calendar').fullCalendar('destroy'); // destroy the calendar
            $('#calendar').fullCalendar(options);

            // ADICIONA NOVO EVENTO
            $('form[name="form-events"]').submit(function (e) {
                e.preventDefault();
                var form = this;

                $.ajax({
                    url: 'control.php',
                    type: 'POST',
                    data: $(form).serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        $.fn.Modal('Aguarde...');
                    },
                    success: function (data) {
                        $.fn.Alerta('', data.message, data.type);
                        if (data.erro === 0) {
                            $(form).trigger('reset');
                            $('#calendar').fullCalendar('destroy');
                            $('#calendar').fullCalendar(options);
                        }
                    }
                });
            });

            // ATUALIZA EVENTO
            $('body').on('submit', 'form[name="alterar_item"]', function (e) {
                e.preventDefault();
                var form = this;

                $.ajax({
                    url: 'control.php',
                    type: 'POST',
                    data: $(form).serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        $("#ajax-modal").modal('hide');                     
                        $.fn.Modal('Aguarde...');
                    },
                    success: function (data) {
                        $.fn.Alerta('', data.message, data.type);
                        if (data.erro === 0) {
                            $('#calendar').fullCalendar('destroy');
                            $('#calendar').fullCalendar(options);
                        }
                    }
                });
            });
            
            // REMOVE EVENTO
            $('body').on('click', 'button[name="RemoveEvent"]', function(){
                var id = $(this).attr('data-id');
                
                $.ajax({
                    url: 'control.php',
                    type: 'POST',
                    data: {action: 'RemoveEvent', id: id},
                    dataType: 'json',
                    beforeSend: function () {
                        $("#ajax-modal").modal('hide');                     
                        $.fn.Modal('Aguarde...');
                    },
                    success: function (data) {
                        $.fn.Alerta('', data.message, data.type);
                        if (data.erro === 0) {
                            $('#calendar').fullCalendar('destroy');
                            $('#calendar').fullCalendar(options);
                        }
                    }
                });                
            });
        }

    };

}();

jQuery(document).ready(function () {
    AppCalendar.init();
});