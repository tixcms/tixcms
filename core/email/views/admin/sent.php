<?php if( $items ):?>
    <?php foreach($items as $item):?>
        <div>
            <div>
                <strong>
                    Дата отправления:
                </strong>
                <?=Helpers\Date::nice($item->created_on)?>
            </div>
            <div>
                <strong>Отправил:</strong>
                <?=$item->login?>
            </div>
            <div>
                <strong>
                    От кого:
                </strong>
                <?=$item->from?>
            </div>
            <div>
                <strong>Кому:</strong>
                <?=$item->to == 'all' ? 'Всем' : $item->group_name?>                
            </div>
            <div>
                <strong>Тема:</strong>
                <?=$item->subject?>
            </div>
            <div>
                <strong>Писем отправлено:</strong>
                <?=$item->count?>
            </div>
            <div>
                <a href="#" onclick="$(this).next().toggle(); return false;" style="border-bottom: 1px dashed;">Сообщение</a>
                <div style="display: none;">
                    <?=nl2br($item->message)?>
                </div>
            </div>
        </div>
        <hr />
    <?php endforeach?>
<?php else:?>
    <p>Нет отправленных писем</p>
<?php endif?>