<!DOCTYPE html>
<html>
    <head>
        <title>FullCalendar</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel='stylesheet' type='text/css' href='css/style.css' />
        <link rel='stylesheet' type='text/css' href='css/fullcalendar.css' />
        <link rel='stylesheet' type='text/css' href='css/jquery-ui-1.8.11.custom.css' />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
        <script src="js/jquery-ui-1.8.11.custom.min.js"></script>
        <script src='js/fullcalendar.min.js'></script>
        <script src="js/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
           
            var event_start = $('#event_start');
            var event_end = $('#event_end');
            var event_type = $('#event_type');
            var calendar = $('#calendar');
            var form = $('#dialog-form');
            var event_id = $('#event_id');
            var format = "MM/DD/YYYY HH:mm";
         
            $('#add_event_button').button().click(function(){
                formOpen('add');
            });
           
            function emptyForm() {
                event_start.val("");
                event_end.val("");
                event_type.val("");
                event_id.val("");
            }
       
            function formOpen(mode) {
                if(mode == 'add') {
                   
                    $('#add').show();
                    $('#edit').hide();
                    $("#delete").button("option", "disabled", true);
                }
                else if(mode == 'edit') {
                  
                    $('#edit').show();
                    $('#add').hide();
                    $("#delete").button("option", "disabled", false);
                }
                form.dialog('open');
            }
         
            event_start.datetimepicker({hourGrid: 4, minuteGrid: 10, dateFormat: 'mm/dd/yy'});
            event_end.datetimepicker({hourGrid: 4, minuteGrid: 10, dateFormat: 'mm/dd/yy'});
         
            calendar.fullCalendar({
                firstDay: 1,
                height: 500,
                editable: true,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                monthNames: ['Січень','Лютий','Березень','Ківтень','Травень','Червень','Липень','Серпень','Вересень','Жовтень','Листопад','Грудень'],
                monthNamesShort: ['Січ.','Лют.','Бер','Квіт.','Трав','Черв','Лип','Сер.','Вер.','Жов.','Лист.','Груд.'],
                dayNames: ["Неділя","Понеділок","Вівторок","Середа","Четвер","П’ятниця","Субота"],
                dayNamesShort: ["ВС","ПН","ВТ","СР","ЧТ","ПТ","СБ"],
                buttonText: {
                    prev: "&nbsp;&#9668;&nbsp;",
                    next: "&nbsp;&#9658;&nbsp;",
                    prevYear: "&nbsp;&lt;&lt;&nbsp;",
                    nextYear: "&nbsp;&gt;&gt;&nbsp;",
                    today: "Сьогодні",
                    month: "Місяць",
                    week: "Тиждень",
                    day: "День"
                },
             
               
                timeFormat: 'H:mm',
                
                dayClick: function(date, allDay, jsEvent, view) {
                    var newDate = $.fullCalendar.formatDate(date, format);
                    event_start.val(newDate);
                    event_end.val(newDate);
                    formOpen('add');
                },
                
                eventClick: function(calEvent, jsEvent, view) {
                    event_id.val(calEvent.id);
                    event_type.val(calEvent.title);
                    event_start.val($.fullCalendar.formatDate(calEvent.start, format));
                    event_end.val($.fullCalendar.formatDate(calEvent.end, format));
                    formOpen('edit');
                },
               
                eventSources: [{
                    url: 'ajax.php',
                    type: 'POST',
                    data: {
                        op: 'source'
                    },
                    error: function() {
                        alert('Помилка бази даних');
                    }
                }]
            });
           
            form.dialog({ 
                autoOpen: false,
                buttons: [{
                    id: 'add',
                    text: 'Додати',
                    click: function() {
                        $.ajax({
                            type: "POST",
                            url: "ajax.php",
                            data: {
                                start: event_start.val(),
                                end: event_end.val(),
                                type: event_type.val(),
                                op: 'add'
                            },
                            success: function(id){
                                calendar.fullCalendar('renderEvent', {
                                                                        id: id,
                                                                        title: event_type.val(),
                                                                        start: event_start.val(),
                                                                        end: event_end.val(),
                                                                        allDay: false
                                                                    });
                                
                            }
                        });
			emptyForm();
                    }
                },
                {   id: 'edit',
                    text: 'Змінити',
                    click: function() {
                        $.ajax({
                            type: "POST",
                            url: "ajax.php",
                            data: {
                                id: event_id.val(),
                                start: event_start.val(),
                                end: event_end.val(),
                                type: event_type.val(),
                                op: 'edit'
                            },
                            success: function(id){
                                calendar.fullCalendar('refetchEvents');
                                
                            }
                        });
                        $(this).dialog('close');
			emptyForm();
                    }
                },
                {   id: 'cancel',
                    text: 'Скасувати',
                    click: function() { 
                        $(this).dialog('close');
                        emptyForm();
                    }
                },
                {   id: 'delete',
                    text: 'Видалити',
                    click: function() { 
                        $.ajax({
                            type: "POST",
                            url: "ajax.php",
                            data: {
                                id: event_id.val(),
                                op: 'delete'
                            },
                            success: function(id){
                                calendar.fullCalendar('removeEvents', id);
                            }
                        });
                        $(this).dialog('close');
                        emptyForm();
                    },
                    disabled: true
                }]
            });
	});
        </script>
    </head>
    <body style=\"color: ##E8F9FF">
        <div id="calendar"></div>
        <button id="add_event_button">Додати подію</button>
        <div id="dialog-form" title="Подія">
            <p class="validateTips"></p>
            <form>
                <p><label for="event_type">Тип  події</label>
                <input type="text" id="event_type" name="event_type" value=""></p>
                <p><label for="event_start">Початок</label>
                <input type="text" name="event_start" id="event_start"/></p>
                <p><label for="event_end">Кінець</label>
                <input type="text" name="event_end" id="event_end"/></p>
                <input type="hidden" name="event_id" id="event_id" value="">
            </form>
        </div>
    </body>
</html>
