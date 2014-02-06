<?php if( $actions ):?>

    <?php foreach($actions as $type=>$action):?>

        <?php if( !isset($action['visible']) OR $action['visible'] ):?>

            <?php if( $type == 'submit' ):?>

                <input
                    type="submit"
                    name="<?=$type?>"
                    value="<?=$action['value']?>"
                    <?=HTML\Tag::parse_attributes($action['attrs'])?>
                />

            <?php elseif( $type == 'submit-more' ):?>

                <input
                    type="submit"
                    name="<?=$type?>"
                    value="<?=$action['value']?>"
                    <?=HTML\Tag::parse_attributes($action['attrs'])?>
                />

            <?php elseif( $type == 'submit-stay' ):?>

                <input
                    type="submit"
                    name="<?=$type?>"
                    value="<?=$action['value']?>"
                    <?=HTML\Tag::parse_attributes($action['attrs'])?>
                />
            <?php elseif( $type == 'apply' ):?>

                <input
                    type="submit"
                    name="<?=$type?>"
                    value="<?=$action['value']?>"
                    <?=HTML\Tag::parse_attributes($action['attrs'])?>
                />

            <?php elseif( $type == 'back_url' ):?>

                <?=URL::anchor(
                    $action['href'],
                    $action['label'],
                    $action['attrs']
                )?>

            <?php elseif( $type == 'ajax-img-loader' ):?>

                <span class="form-ajax-waiting-message hide">
                    <?=$this->di->assets->img($action['src'])?>
                </span>

            <?php elseif( $action['type'] == 'view' ):?>

                <?=$this->template->view($action['view'])?>

            <?php endif?>

       <?php endif?>

    <?php endforeach?>
<?php endif?>