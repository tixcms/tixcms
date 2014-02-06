<?php

class Nav_m extends Tix\Model 
{
    const TYPE_URL = 'text';
    const TYPE_PAGE = 'page';
    const TYPE_CATEGORY = 'categories';
    const TYPE_ADDON = 'module';
    
    public $entity = 'Nav\Entities\Link';
}