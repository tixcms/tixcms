$(function(){
    $("[rel=popover]").popover();
    $("[rel=tooltip]").tooltip();
    $("input").tooltip({
        target: 'focus',
        placement: 'right'
    });
    
    $('.input-help').popover({
        title: 'Справка',
        trigger: 'hover',
        delay: { show: 200, hide: 0 }
    });
    
    $(".alert .close").click(function(){
         $(this).parent().fadeOut('slow');
         return false;
    });
    
    $('.dropdown-toggle').dropdown();
    
    $(".show-help").click(function(){
        $(".form-help").modal();
    });
    
    // confirm delete
    $(document).on('click', ".confirm, .delete", function(){
        if( !confirm($(this).attr("data-confirm")) ){
            return false;
        } else {
            if( $(this).hasClass("delete") && $(this).hasClass("ajax-delete") ){
                var item = $(this).closest(".item");
                item.css('opacity', 0.2);
                $.getJSON($(this).attr("href"), function(data){
                    if( data.type == 'success' ){
                        item.remove();
                        updateTable();
                    } else {
                        item.css('opacity', 1);
                    }
                    $.pnotify(data);
                });
                return false;
            }
        }
    });
    
    $("textarea").not('.wysiwyg').autoResize();
    
    $(document).on('click', "[rel=toggle]", function(){
        var $this = $(this);
        if( $this.data('mode') == 'on' ){
            $this.removeClass($this.data('on-class')).addClass($this.data('off-class')).data('mode', 'off');
            $this.html($this.data('off-text'));
            $.getJSON($this.data("url"), function(data){
                $.pnotify(data);
            });
        } else {
            $this.removeClass($this.data('off-class')).addClass($this.data('on-class')).data('mode', 'on');
            $this.html($this.data('on-text'));
            $.getJSON($this.data("url"), function(data){
                $.pnotify(data);
            });
        } 
    });
    
    $(document).on('click', ".check-all", function(){        
        var table = $(this).parents('table');
        var checked = $(this).prop('checked');        
        
        if( checked ){
            
            $(".item [type=checkbox]", table).prop('checked', true);
            $(".item", table).addClass('warning');
            
        } else {
            
            $(".item [type=checkbox]", table).prop('checked', false);
            $(".item", table).removeClass('warning');
            
        }
    }); 
    
    $(".item [type=checkbox]").click(function(){
        if( $(this).is(':checked') ){
            $(this).parents('.item').addClass('warning');
        } else {
            $(this).parents('.item').removeClass('warning');
        }
    });
    
    $.pnotify.defaults.delay = 2000;
    $.pnotify.defaults.history = false;
    $.pnotify.defaults.sticker = false;
    
    var sidebar = $(".content > .sidebar");
    var content = $(".content-main");
    var navbar = $(".navbar .row-fluid > div");
    var footer = $("footer .row-fluid > div");
    
    $(window).resize(function(){
        var width = $(window).width();
        
        if( width > 1400 ){
            $(".content").prepend(sidebar.show());
            if( sidebar.length ){
                content.removeClass('span12').addClass('span10');
            } else {
                content.removeClass('span12').addClass('span8 offset2');
                navbar.removeClass('span12').addClass('span8 offset2');
                footer.removeClass('span12').addClass('span8 offset2');
            }
        } else {
            $("body").append(sidebar.hide());
            sidebar = $("body > .sidebar");
            if( sidebar.length ){
                content.removeClass('span10').addClass('span12');
            } else {
                content.removeClass('span8 offset2').addClass('span12');
                navbar.removeClass('span8 offset2').addClass('span12');
                footer.removeClass('span8 offset2').addClass('span12');
            }
        }
    }).trigger('resize');

    linkable();
    
    $.extend($.fn.disableTextSelect = function() {
    	return this.each(function(){
            if( typeof $.browser === "undefined" ){
                $(this).mousedown(function(){return false;});
            }else if($.browser.mozilla){//Firefox
    			$(this).css('MozUserSelect','none');
    		}else if($.browser.msie){//IE
    			$(this).bind('selectstart',function(){return false;});
    		}
    	});
    });
	$('.no-select').disableTextSelect();//No text selection on elements with a class of 'noSelect
});

function linkable(){
    $("tr.linkable").each(function(){
        var td = $('td:first', this);
        var content = td.html();
        td.css('cursor', 'pointer');
        td.html('<a href="' + BASE_URL + $(this).data('url') + '" style="display: block;">' + content + '</a>');
    });
}