<div class="row-fluid">
    <div class="span10">
        <div>
            <table class="table">
                <tbody>
                    <tr>
                        <td>Логин</td>
                        <td><?=$user->login?></td>
                    </tr>
                    <tr>
                        <td>Дата регистрации на сайте</td>
                        <td><?=\Helpers\Date::nice($user->register_date)?></td>
                    </tr>
                    <tr>
                        <td>Последний визит</td>
                        <td><?=$user->lastvisit_date ? \Helpers\Date::nice($user->lastvisit_date) : ''?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="span2">
        <div style="margin-left: 10px; text-align: right;">
            <?=HTML\Tag::img(
                $user->avatar_url, 
                array(
                    'class'=>'avatar-image img-polaroid',
                    'style'=>'width: 80px;'
                )
            )?>
        </div>
    </div>
</div>