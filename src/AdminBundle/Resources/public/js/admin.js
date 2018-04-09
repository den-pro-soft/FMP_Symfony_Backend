// datepicker 1
var datePicker = $('#date-picker');

datePicker.datepicker({
    format: "mm-yyyy",
    defaultViewDate: 0,
    startView: "months",
    minViewMode: "months",
    autoclose: true,
    todayHighlight: false
});

var month = $('#month').text();
var year = $('#year').text();
var day = $('#day').text();
var user = $('#user-id').text();

datePicker.val(month + '-' + year);

$('#picker-submit').click(function(event){
    event.preventDefault();
    var pickerValue = datePicker.val().split('-');
    window.location.href = '/admin/time-table/' + user + '/' + pickerValue[1] + '-' + pickerValue[0] + '-' + '01';
});


mask();

$('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
});

$('#time_table').slimScroll({
    height: '750px'
});

$('#time_table a').click(function (event) {
    event.preventDefault();

    $.ajax(this.href, {
        success: function(data) {
            $('#checkbox-table').html($(data).find('#checkbox-table'));

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            mask();
        }
    });
});
