$(function(){    
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
    
    $(".is-main").click(function(){
        var isMain = $(this).hasClass('btn-success');
        
        if( !isMain ){
            $(".is-main").removeClass('btn-success').html('Нет');
            
            $(this).addClass('btn-success').html('Да');
            
            $.getJSON($(this).attr('href'), function(data){
                $.pnotify(data);
            });
        }
        
        return false; 
    });
});