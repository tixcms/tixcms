<script>
    $(function(){
        $.pnotify({
            text: <?=json_encode($message)?>
        });
    });
</script>