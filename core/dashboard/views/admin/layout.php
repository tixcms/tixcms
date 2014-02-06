<div class="page-header">
    <h2>
        Панель управления
        <small>
            <?=URL::anchor(
                'admin/'. $this->module->url .'/settings',
                'Настройки',
                array(
                    'class'=>'muted'
                )
            )?>
        </small>
    </h2>
</div>

<?=$content?>