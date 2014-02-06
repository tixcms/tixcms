<div class="page-header">
    <div class="header-actions">
        <?=URL::anchor(
            'admin/users/add',
            'Создать пользователя',
            array(
                'class'=>'btn btn-primary'
            )
        )?>
    </div>
</div>

<?=$table->render()?>