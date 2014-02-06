<?php

class Captcha_Controller extends \App\Controller
{
    function action_index()
    {
        $captcha = new Form\Input\Captcha\SimpleCaptcha;

        $captcha->CreateImage();
    }
}