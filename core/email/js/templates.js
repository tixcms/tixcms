$(function(){    
    $(".template-edit").click(function(){
        $('#template-edit .modal-body').html('Загрузка...');
        $('#template-edit').modal('show');
        $('#template-edit .modal-header h3').html($(this).data('header'));

        $.get($(this).attr('href'), function(data){
            $('#template-edit .modal-body').html(data);
        });
        return false; 
    });
    
    $(".template-save").click(function(){
        var button = $(this);
        button.attr('disabled', 'disabled').html('Подождите...');
        var queryString = $(".modal form").formSerialize();

        $.post($(".modal form").attr('action'), queryString, function(response) {
            button.removeAttr('disabled').html('Сохранить');
            response = $.parseJSON(response);
            $.pnotify(response);
        });
        
        return false;
    });
});

function insertAtCaret(areaId,text) {
	var txtarea = document.getElementById(areaId);
	var scrollPos = txtarea.scrollTop;
	var strPos = 0;
	var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
		"ff" : (document.selection ? "ie" : false ) );
	if (br == "ie") { 
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -txtarea.value.length);
		strPos = range.text.length;
	}
	else if (br == "ff") strPos = txtarea.selectionStart;
	
	var front = (txtarea.value).substring(0,strPos);  
	var back = (txtarea.value).substring(strPos,txtarea.value.length); 
	txtarea.value=front+text+back;
	strPos = strPos + text.length;
	if (br == "ie") { 
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -txtarea.value.length);
		range.moveStart ('character', strPos);
		range.moveEnd ('character', 0);
		range.select();
	}
	else if (br == "ff") {
		txtarea.selectionStart = strPos;
		txtarea.selectionEnd = strPos;
		txtarea.focus();
	}
	txtarea.scrollTop = scrollPos;
}