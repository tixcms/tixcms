$(function(){
    var ajaxLoadingImg = $(".form-ajax-waiting-message");

    $("form.ajax").ajaxForm(function(data){
        data = $.parseJSON(data);
        
        if( data.type == 'error' ){
            $.each(data.errors, function(i, input){
                if( input.error ){
                    success = false;
                    $(".error-field-" + input.field).html(input.error);
                    $(".field-" + input.field).addClass('error');
                } else {
                    $(".error-field-" + input.field).html('');
                    $(".field-" + input.field).removeClass('error');
                }
            });
        } else {
            $(".control-group").removeClass('error');
            $(".error-string").html('');

            if( typeof data.onSuccess != 'undefined' ){
                if( data.onSuccess.action == 'redirect' ){
                    window.location.href = BASE_URL + data.onSuccess.url;
                }
            }
            
            if( data.images ){
                $.each(data.images, function(i, item){
                    $(".field-" + item.field + " img").attr('src', item.src);
                });
            }
        }

        ajaxLoadingImg.hide();

        $.pnotify(data);

        return false;
    });

    $("form.ajax [type=submit]").click(function(){
        ajaxLoadingImg.show();
    });
});