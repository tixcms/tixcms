if(window.jQuery)
{
    $(function(){
        $(".profiler-show-data").click(function(){
            var data = $(".profiler-data[id="+ $(this).data('id') + "]");
            if( data.css('display') == 'none' ){
                $(".profiler-data").hide();
                data.show();
            } else {
                data.hide();
            }
        });
    });
}