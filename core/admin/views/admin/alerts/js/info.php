<script>
    $(function(){
        $.pnotify({
            type: 'info',
            text: <?=json_encode($message)?>
        });
    });
</script>