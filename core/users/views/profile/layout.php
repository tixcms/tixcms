<div class="page-header">        
    <h2>
        <?php if( $user->is_current ):?>
            <?php if( $this->controller == 'edit' ):?>
                Редактирование профиля
                <small class="small-link"><?=URL::anchor('users/profile', 'просмотр')?></small>
            <?php else:?>
                Личный профиль
                <small class="small-link"><?=URL::anchor('users/profile/edit', 'редактировать')?></small>
            <?php endif;?>
        <?php else:?>
            Профиль пользователя
        <?php endif;?>
        
    </h2>
</div>
                
<?=$content?>