<?php

namespace Users\Auth;

class Services
{
    private $provider;
    
    function __construct($provider)
    {
        $providers = array(
            'twitter'=>'Services\Twitter\Auth',
            'google'=>'Services\Google\Auth'
        );
        
        $this->provider = new $providers[$provider];
    }
    
    function authenticate()
    {
        $this->provider->authenticate();
        
        if( $this->registered() )
        {
            $this->auth->login();
            
            \URL::redirect('');
        }
        else
        {
            $this->register();
        }
    }
    
    function register()
    {
        
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}