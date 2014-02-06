$(function(){
    $("[rel=tooltip]").tooltip();
    $("input").tooltip({
        target: 'focus',
        placement: 'right'
    });
    
    $(".alert .close").click(function(){
         $(this).parent().fadeOut('slow');
         return false;
    });
    
    $('.dropdown-toggle').dropdown();
    
    // confirm delete
    $("table .confirm, .delete").click(function(){
        if( !confirm($(this).attr("data-confirm")) ){
            return false;
        } else {
            if( $(this).hasClass("delete") && $(this).hasClass("ajax-delete") ){
                $(this).parents("tr").css('background', '#FCF8E3').fadeOut();
                $.get($(this).attr("href"));
                return false;
            }
        }
    });
});