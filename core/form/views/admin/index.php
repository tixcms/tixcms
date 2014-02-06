<div class="page-header">
    <div class="header-actions">
        <?=$this->url->anchor(
            'admin/'. $this->module->url .'/add',
            'Создать форму',
            array(
                'class'=>'btn btn-primary'
            )
        )?>
    </div>
</div>

<?=$list->render()?>