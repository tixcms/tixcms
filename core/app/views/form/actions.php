<?php if( $actions ):?>
    <?php foreach($actions as $type=>$action):?>

        <?php if( $type == 'submit' ):?>
            <input
                type="submit"
                name="<?=$action['name']?>"
                value="<?=$action['value']?>"
                <?=HTML\Tag::parse_attributes($action['attrs'])?>
            />
        <?php endif?>

    <?php endforeach?>
<?php endif?>

