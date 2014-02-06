<div class="tabbable tabs-left">
    <ul <?=HTML\Tag::parse_attributes($attrs)?>>
        <?php foreach($items as $item):?>
        
            <li<?=(isset($item['active']) AND $item['active']) ? ' class="active"' : ''?>>
                <?php if( $dynamic ):?>
                    <a href="#<?=$item['url']?>" data-toggle="tab">
                        <?=$item['label']?>
                    </a>
                <?php else:?>
                    <?=URL::anchor(
                        $item['url'],
                        $item['label']
                    )?>
                <?php endif?>
            </li>
            
        <?php endforeach?>
    </ul>
    
    <?php if( $dynamic ):?>
        <div class="item area-sortable tab-content">
            <?php foreach($items as $item):?>            
                <div class="tab-pane<?=(isset($item['active']) AND $item['active']) ? ' active' : ''?>" id="<?=$item['url']?>">
                    <?=$item['content']?>
                </div>
            <?php endforeach?>    
        </div>
    <?php endif?>
</div>

<script>
    $(function(){
        $(".nav-pills li.active a").trigger('click'); 
    });
</script>