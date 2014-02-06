<?php if( $this->module->url != 'email' ):?>
    <div class="page-header">
        <div class="header-actions">
            <?=URL::anchor(
                'admin/'. $this->module->url,
                'Обратно',
                array(
                    'class'=>'btn'
                )
            )?>
        </div>
    </div>
<?php endif?>

<?php if( isset($templates) ):?>

    <?php foreach( $templates as $module=>$templates_data ):?>
        <h3>
            <?=Modules\Helper::name($module)?>
        </h3>
        
        <?=Admin\Table::create(array(
            'headings'=>array(
                'Название',
                'Описание'
            ),
            'item_view'=>'email::templates/_item',
            'items'=>$templates_data,
            'no_items'=>'<p>Нет шаблонов</p>',
            'search'=>false,
            'per_page'=>false,
            'show_total_counter'=>false
        ))->render()?>
        
    <?php endforeach?>
    
<?php else:?>

  <?=$table->render()?>
  
<?php endif?>

<div class="modal hide" id="template-edit" style="width: 800px; left: 45%; top: 40%;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Modal header</h3>
  </div>
  <div class="modal-body">
    Загрузка...
  </div>
  <div class="modal-footer">
    <a href="" class="btn btn-primary template-save">Сохранить</a>
    <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
  </div>
</div>