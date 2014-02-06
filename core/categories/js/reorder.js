$(function(){
    $(".list").nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div',
        placeholder: 'placeholder',
        forcePlaceholderSize: true,
        tolerance: 'pointer',
        opacity: 0.6,
        update: function(event, ui) {
            arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});

            $.post(BASE_URL + 'admin/' + URI.module + '/categories/reorder', {
                ids: arraied
            }, function(data){
                data = $.parseJSON(data);
                $.pnotify(data);
            });
        }
    });
});