<ul <?=HTML\Tag::parse_attributes($attrs)?>>
    <?php foreach($items as $item):?>
        <li<?=(isset($item['active']) AND $item['active']) ? ' class="active"' : ''?>>
            <?php if( $dynamic ):?>
                <a href="#<?=$item['url']?>" data-toggle="tab">
                    <?=$item['label']?>
                </a>
            <?php else:?>
                <?php if( is_array($item['url']) ):?>
                    <?=URL::anchor(
                        $item['url'][0],
                        $item['label'],
                        '',
                        $item['url'][1]
                    )?>
                <?php else:?>
                    <?=URL::anchor(
                        $item['url'],
                        $item['label']
                    )?>
                <?php endif?>
            <?php endif?>
        </li>
    <?php endforeach?>
</ul>

<?php if( $dynamic ):?>
    <div class="tab-content">
        <?php foreach($items as $item):?> 
            <div class="tab-pane<?=(isset($item['active']) AND $item['active']) ? ' active' : ''?>" id="<?=$item['url']?>">
                <?=$item['content']?>
            </div>
        <?php endforeach?>
    </div>
<?php endif?>