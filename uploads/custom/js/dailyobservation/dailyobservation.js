
$(document).ready(function () {
    //intialize scroll
    $('.tile .slimScroll-120').slimscroll({
                height: 120,
                size: 3,
                alwaysVisible: true,
                color: '#2196f3'
            });
    $('.tile .slimScroll').slimscroll({
        height: 60,
        size: 3,
        alwaysVisible: true,
        color: '#2196f3'
    });
    //intialize date
     $("#create_date").datepicker({
        format: 'yyyy-mm-dd',
    });
    
     
});
$('#awake_time').timepicker({defaultTime: ''});
$('#bed_time').timepicker({defaultTime: ''});
$('body').delegate('[data-toggle="ajaxModal"]', 'click',
        function (e) {
            $('#ajaxModal').remove();
            e.preventDefault();
            var $this = $(this)
                    , $remote = $this.data('remote') || $this.attr('data-href') || $this.attr('href')
                    , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
            $('body').append($modal);
            $modal.modal({backdrop: true});
            $modal.load($remote);
            $("body").css("padding-right", "0 !important");
        }

);
/*delete image*/
function delete_img(obj,img,inputfield)
{
    var input = $("#"+inputfield);
    BootstrapDialog.show(
        {
            title: 'Confirm!',
            message: "<strong> Are you sure want to delete file ? <strong>",
            buttons: [{
                    label: 'Cancel',
                    action: function (dialog) {
                        dialog.close();

                    }
                }, {
                    label: 'Ok',
                    action: function (dialog) {
                        input.val((input.val() != '')?input.val()+","+img:img);
                        $(obj).parent().remove();
                        dialog.close();
                    }

                }]
        });
}


