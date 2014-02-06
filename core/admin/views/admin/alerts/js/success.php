<script>
    $(function(){
        $.pnotify({
            type: 'success',
            text: <?=json_encode($message)?>
        });
    });
</script>