<form action="" method="post">
<div class="login-form">
    <div class="login-form-header">
        <h3>Вход в панель управления</h3>
    </div>
    
    <div class="login-form-body form-horizontal">
        <?=$this->di->alert->render()?>
        
        <div class="control-group">
            <label class="control-label">Логин</label>
            <div class="controls">
                <input type="text" name="login" value="" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Пароль</label>
            <div class="controls">
                <input type="password" name="password" value="" />
            </div>
        </div>
        <?php if( $this->settings->security_captcha ):?>
            <div class="control-group">
                <label class="control-label">Код</label>
                <div class="controls">
                    <?=HTML\Tag::img(URL::site_url('form/captcha'))?>
                    <input type="text" name="captcha" value="" placeholder="Введите текст на картинке">
                </div>
            </div>
        <?php endif?>
    </div>
    
    <div class="login-form-footer">        
        <?=URL::anchor(
            '',
            'Перейти на сайт',
            array(
                'class'=>'btn'
            )
        )?>
        <input type="submit" name="submit" value="Войти" class="btn btn-primary" />
    </div>
</div>
</form>