<div class="theme-<?=$theme?>">
    <div class="slider-wrapper">
        <div id="slider-<?=$id?>" class="nivoSlider">
            <?php foreach($items as $item):?>
                <?=$item?>
            <?php endforeach?>
        </div>
    </div>
</div>
    
<script type="text/javascript">
    $(window).load(function() {
        $('#slider-<?=$id?>').nivoSlider(<?=json_encode($options)?>);
    });
</script>