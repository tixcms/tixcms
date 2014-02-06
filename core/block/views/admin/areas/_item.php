<?php if( $i > 0 AND $i%2 == 0 ):?>
    <div class="row-fluid">
<?php endif?>

<div class="span6 item">
    <div class="page-header area-item" data-id="<?=$item->alias?>">
        <?php if( ENVIRONMENT == 'development' ):?>
            <ul class="header-actions hide actions pull-right">
                <li>
                    <?=URL::anchor(
                        'admin/block/areas/delete/'. $item->id,
                        'Удалить',
                        array(
                            'class'=>'confirm delete ajax-delete',
                            'data-confirm'=>'При удалении области будут также удалены все блоки в ней. Удалить область?',
                        )
                    )?>
                </li>
            </ul>
        <?php endif?>
        <h3>
            <?php if( ENVIRONMENT == 'development' ):?>
                <?=URL::anchor(
                     'admin/block/areas/edit/'. $item->id,
                    $item->name
                )?>
            <?php else:?>
                <?=$item->name?>
            <?php endif?>
            
            <small class="muted" title="Код для вставки">{{block:area alias="<?=$item->alias?>"}}</small>
        </h3>
    </div>    
    
    <form>
        <table class="table">
        	<thead>
        		<tr>
                    <th></th>
                    <th>Название</th>
                    <th>Статус</th>
                    <th>Действия</th>
        		</tr>
        	</thead>
        	<tbody class="sortable" id="<?=$item->alias?>">
                <tr>
                    <td colspan="4">
                    </td>
                </tr>
                    
                <?=Admin\TList::create(array(
                    'item_view'=>'_row',
                    'items'=>isset($blocks[$item->alias]) ? $blocks[$item->alias] : FALSE,
                    'no_items'=>'',
                    'per_page'=>false
                ))->render()?>
            </tbody>
            <tfoot>
        	</tfoot>
        </table>
    </form>
</div>

<?php if( $i%2 != 0 ):?>
    </div>
<?php endif?>