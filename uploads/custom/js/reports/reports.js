$(document).ready(function () {

    $('#admin_report_start_date').datetimepicker({format: 'L'});
    $('#admin_report_end_date').datetimepicker({format: 'L'});

    // On Form Submit
    $("#generate_report_form").on('submit', function (e) {

        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: 'showReportList',
            data: $(this).serialize(),
            type: 'POST',
            success: function (data) {
                $('#common_div').html(data);
            },
            error: function (data) {
                //$("#error").show().fadeOut(20000);
            }
        });
    });

    // On clcik generate excel file
    $(document).on('click', '#exportFile', function (evt, params) {

        $.ajax({
            type: 'POST',
            url: 'generateExcelFileUrl',
            data: $("#generate_report_form").serialize(),
        }).done(function (data) {
            window.open(data, '_blank');
        });

    });
});