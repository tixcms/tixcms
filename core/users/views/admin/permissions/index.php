<?php if( $groups ):?>

    <form action="<?=$this->url->site_url('admin/users/permissions/save')?>" method="post" class="form-horizontal">
    
        <div class="page-header">
            <div class="header-actions">
                <input type="submit" name="submit" value="Сохранить" class="btn btn-primary ajax-submit" />
            </div>
        </div>
    
        <?=Admin\Table::create(array(
            'headings'=>$headings,
            'items'=>$modules,
            'item_view'=>'permissions/_item',
            'search'=>false,
            'per_page'=>false
        ))->render()?>
    </form>
    
<?php else:?>

    <p></p>
    
<?php endif?>