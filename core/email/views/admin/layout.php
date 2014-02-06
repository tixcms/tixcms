<ul class="nav nav-tabs">
    <li<?=($this->action == 'compose') ? ' class="active"' : ''?>>
        <?=URL::anchor(
            'admin/email/compose',
            'Написать письмо'
        )?>
    </li>
    <li<?=$this->controller == 'templates' ? ' class="active"' : ''?>>
        <?=URL::anchor(
            'admin/email/templates',
            'Шаблоны почты'
        )?>
    </li>
    <li<?=$this->controller == 'queue' ? ' class="active"' : ''?>>
        <?=URL::anchor(
            'admin/email/queue',
            'Письма в очереди'
        )?>
    </li>
    <li<?=($this->action == 'sent' ) ? ' class="active"' : ''?>>
        <?=URL::anchor(
            'admin/email/sent',
            'Отправленные письма'
        )?>
    </li>
</ul>

<?=$content?>