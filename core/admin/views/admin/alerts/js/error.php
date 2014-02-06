<script>
    $(function(){
        $.pnotify({
            type: 'error',
            text: <?=json_encode($message)?>
        });
    });
</script>