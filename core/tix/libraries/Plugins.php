<?php

namespace Tix;

class Plugins extends Plugin
{
    function breadcrumbs()
    {
        return $this->di->breadcrumbs->render();
    }
    
    function alert()
    {
        return $this->di->alert->render();
    }
}