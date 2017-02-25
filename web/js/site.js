/**
 * Created by AxeDream on 25.02.2017.
 */
$(function(){
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
        success: function (data) {
            $('#load_form_mess').html(data);
        },
        error: function () {
            $('#load_form_mess').html(data);
        }
    });
}
