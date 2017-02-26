/**
 * Created by AxeDream on 25.02.2017.
 */
$(function(){
    $("#ajax_img").hide();
    $("#load_txt").on('click',function(){
        var url =  $('#link_from_load_txt_file').val();
        var fd = new FormData(document.forms.create_file_form);
        loadFormFile(url,fd);
    });
});

function loadFormFile(url,fd){
    $.ajax({
        processData: false,
        contentType: false,
        type: 'POST',
        url: url,
        data:fd,
        beforeSend : function(){
            $("#load_txt").hide();
            $("#ajax_img").show();
            $('#load_form_mess').html('<h3 class="text-info">Идет обработка файла! Подождите...</h3><div class="clearfix"></div>');
        },
        success : function(data) {
            $('#load_form_mess').html('');
            $('#load_form_mess').html(data);(data);
        },
        error : function() {
            $('#load_form_mess').html('');
            $('#load_form_mess').html(data);
        },
        complete : function () {
            $("#ajax_img").hide();
            $("#load_txt").show();

        }
    });
}
