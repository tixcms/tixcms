$(function(){   
    $("#modules-list").tableDnD({
        onDrop: function(table, row) {
            $.get(BASE_URL + 'admin/modules/reorder/?' + $.tableDnD.serialize());
        }
    });
});