<?php

namespace Dashboard;

class Settings extends \Settings\Form
{
    const FORM_ACTIONS_POSITION_TOP = 1;
    const FORM_ACTIONS_POSITION_BOTTOM = 2;
    const FORM_ACTIONS_POSITION_BOTH = 3;

    function inputs()
    {
        $this->load->language('dashboard/settings');

        return array(
            'dashboard_left_sidebar'=>new \Form\Input\Checkbox(array(
                'label'=>lang('dashboard:settings_left_sidebar'),
                'default'=>true,
                'personal'=>true
            )),
            'admin_form_actions_position'=>array(
                'label'=>'Расположение кнопок действий в формах',
                'type'=>'select',
                'options'=>array(
                    self::FORM_ACTIONS_POSITION_TOP=>'Только сверху',
                    self::FORM_ACTIONS_POSITION_BOTTOM=>'Только снизу',
                    self::FORM_ACTIONS_POSITION_BOTH=>'Сверху и снизу'
                ),
                'personal'=>true,
                'default'=>self::FORM_ACTIONS_POSITION_TOP
            ),
            'admin_language'=>array(
                'type'=>'hidden',
                'default'=>'ru',
                'personal'=>true,
            )
        );
    }
}