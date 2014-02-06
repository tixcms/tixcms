<div class="page-header">
    <div class="header-actions">
        <?=URL::anchor(
            'admin/'. $this->module->url,
            'Обратно',
            array(
                'class'=>'btn'
            )
        )?>
        <?=URL::anchor(
            'admin/'. $this->module->url .'/categories/add',
            'Создать категорию',
            array(
                'class'=>'btn btn-primary'
            )
        )?>
        <?=URL::anchor(
            'admin/'. $this->module->url .'/categories/reorder',
            'Изменить порядок',
            array(
                'class'=>'btn'
            )
        )?>
    </div>
</div>

<?=$table->render()?>