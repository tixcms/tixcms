$(function(){
    $("[data-form=ajax]").validate({
        errorClass: 'error',
        validClass: '',
        submitHandler: function(form) {
            $('[type=submit]', form).attr('disabled', 'disabled');
            $.post($(form).attr('action'), $(form).serialize(), function(response) {
                response = $.parseJSON(response);
                if( response.type == 'success' ){
                    window.location.reload();
                } else {
                    alert(repsonse.message);
                }
            });
        },
        highlight: function(element, errorClass, validClass) {
            $(element).parents("div[class='control-group']").addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".error").removeClass(errorClass).addClass(validClass);
            $(element).siblings('.help-block').remove();
        },
        errorPlacement: function(err, element) {
            element.siblings('.help-block').remove();
            element.after('<span class="help-block">' + err.text() + '</span>');
        }
    });
    
    // confirm delete
    $(".delete").unbind('click').bind('click', (function(){
        if( !confirm($(this).attr("data-confirm")) ){
            return false;
        } else {
            if( $(this).hasClass("delete") && $(this).hasClass("ajax-delete") ){
                var item = $(this).closest(".item");
                var link = $(this).attr("href");
                var level = item.data("level");
                
                while(item){
                    var next_item = item.next();
                    
                    if( next_item.data('level') > level ){
                        item.css('background', '#FCF8E3').fadeOut();
                        item = next_item;
                    } else {
                        item.css('background', '#FCF8E3').fadeOut();
                        item = false;
                    }
                }
                
                $.getJSON(link, function(data){
                    $.pnotify(data);
                });
                return false;
            }
        }
    }));
});