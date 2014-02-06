var timeout;
var currentUrl = CURRENT_URL;

$(function(){
    $(document).on('click', ".table th a, .pagination a, .per-page-options a, .filters a", function(){        
        var scrollToTop = function(){
            $('html, body').animate({
                 scrollTop: $(".table").offset().top - 150
             }, 500);
        };
        
        updateTable(
            $(this).attr('href'), 
            $(this).parents('.pagination').length > 0 ? scrollToTop : false
        );
        
        return false;  
    });

    if( $(".search-form").length ){
        $("[name=search]").keyup(function(){
            clearTimeout(timeout);
            timeout = setTimeout(function(){
                $(".search-form").ajaxSubmit({
                    beforeSubmit: function(arr, form, options){
                        search = $("[name=search]").val();
                        var href = options.url + '?' + $("[name=search]").fieldSerialize() + '&per_page=' + tableData.per_page;
                        
                        updateTable(href);
                        return false;
                    }
                });
            }, 350);
        });
    }
    
    $(document).on('click', '.check', function(){
        var tr = $(this).parents('.item');
        var checked = $(this).prop('checked');        
        
        if( checked ){
            tr.addClass('warning');
        } else {
            tr.removeClass('warning');
        }
    });
    
    $(document).on('click', '.mass-actions', function(){        
        var url = $(this).data('url');
        var ids = new Array;
        
        $(".check:checked").each(function(index, item){
            ids.push($(item).data('id'));
            
            $(item).parents('.item').css('opacity', 0.2);
        });
        
        if( ids.length > 0 ){            
            $.post(url, {ids: ids}, function(data){
                data = $.parseJSON(data);                    
                $(".table .item").css('opacity', 1);                    
                updateTable(currentUrl, false);                    
                $.pnotify(data);
            });
        }
    });
    
    $(document).on('click', '.mass-delete', function(){

        var url = $(this).data('url');
        var ids = new Array;
        
        $(".check:checked").each(function(index, item){
            ids.push($(item).data('id'));
            
            $(item).parents('.item').css('opacity', 0.2);
        });
        
        if( ids.length > 0 ){
            
            if( !confirm('Удалить?') ){
                return false;
            }
            
            $.post(url, {ids: ids}, function(data){
                data = $.parseJSON(data);
                
                $(".table .item").css('opacity', 1);
                
                $.each(data.deleted_ids, function(index, value){
                    $(".table [data-id=" + value + "].item").remove();
                });
                
                updateTable(currentUrl, false);
                
                $.pnotify(data);
            });
        }
    });
});

function formSearch(search){
    $('[name=search]').val(search).trigger('keyup');
    return false;
}

function updateTable(href, callback){
    $('.table tbody').css('opacity', 0.6);
    currentUrl = href;
    $.getJSON(href, function(data){
        $(".table thead").html(data.head);
        $(".table tbody").html(data.body);
        $(".table-pagination").html(data.pager);
        $('.table tbody').css('opacity', 1);
        $(".per-page-options").html(data.per_page_options);
        $(".filters").html(data.filters);
        
        $(".table-total").html(data.total);
        
        tableData = data.data;
        
        if(typeof callback === 'function'){
            callback();
        }
        
        $(".table tbody").highlight($("[name=search]").val());

        linkable();
    });
}

jQuery.fn.highlight = function(pat) {
    function innerHighlight(node, pat) {
        var skip = 0;
        if (node.nodeType == 3) {
            var pos = node.data.toUpperCase().indexOf(pat);
            if (pos >= 0) {
                var spannode = document.createElement('span');
                spannode.className = 'highlight';
                var middlebit = node.splitText(pos);
                var endbit = middlebit.splitText(pat.length);
                var middleclone = middlebit.cloneNode(true);
                spannode.appendChild(middleclone);
                middlebit.parentNode.replaceChild(spannode, middlebit);
                skip = 1;
            }
        } else if (node.nodeType == 1 && node.childNodes && !/(script|style)/i.test(node.tagName)) {
            for (var i = 0; i < node.childNodes.length; ++i) {
                i += innerHighlight(node.childNodes[i], pat);
            }
        }
        return skip;
    }
    return this.length && pat && pat.length ? this.each(function() {
        innerHighlight(this, pat.toUpperCase());
    }) : this;
};

jQuery.fn.removeHighlight = function() {
    return this.find("span.highlight").each(function() {
        this.parentNode.firstChild.nodeName;
        with(this.parentNode) {
            replaceChild(this.firstChild, this);
            normalize();
        }
    }).end();
};