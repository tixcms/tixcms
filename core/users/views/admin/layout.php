<div class="page-header">
    <h2>
        Пользователи
        <small>
            <?=URL::anchor(
                'admin/users/settings',
                'Настройки',
                array(
                    'class'=>'muted'
                )
            )?>
        </small>
    </h2>
</div>

<?=$content?>