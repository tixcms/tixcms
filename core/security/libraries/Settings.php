<?php

namespace Security;

class Settings extends \Settings\Form
{
    function inputs()
    {
        return array(
            'security_ip_checker'=>array(
                'type'=>'text',
                'label'=>'Сайт для просмотра IP',
                'default'=>'http://www.ip-ping.ru/ipinfo/?ipinfo={ip}',
                'help'=>'На указанный сайт будет осуществлен переход для просмотра информации об IP адресе. Вместо <code>{ip}</code> будет подставлен IP адрес.'
            ),
            'security_captcha'=>new \Form\Input\Checkbox(array(
                'label'=>'Использовать капчу',
                'default'=>FALSE
            )),
            'security_attempts_limit_count'=>array(
                'type'=>'text',
                'label'=>'Количество попыток неверного ввода пароля до блокировки',
                'default'=>5,
                'rules'=>'trim|required|numeric|greater_than[-1]'
            ),
            'security_attempts_block_time'=>array(
                'type'=>'text',
                'label'=>'Время блокировки (мин)',
                'default'=>1,
                'rules'=>'trim|required|numeric|greater_than[-1]'
            ),
            'security_attempls_violation_notice'=>new \Form\Input\Checkbox(array(
                'label'=>'Отправлять уведомление на почту о попытке взлома',
                'default'=>FALSE
            )),
            'security_attempls_violation_notice_email'=>array(
                'type'=>'text',
                'label'=>'Почта для отправки уведомления о попытке взлома',
                'default'=>'',
                'placeholder'=>isset(\CI::$APP->settings->server_email) ? \CI::$APP->settings->server_email : '',
                'rules'=>'trim|valid_email'
            ),
        );
    }
}