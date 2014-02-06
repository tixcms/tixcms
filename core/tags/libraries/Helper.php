<?php

namespace Tags;

class Helper
{
    /**
     * Создает из строки тегов ссылки на эти теги
     */
    public function links_from_tags($tags, $url, $delimiter = ', ')
    {
        $data = array();
        foreach(explode(',', $tags) as $tag)
        {
            $data[] = \CI::$APP->di->url->anchor($url . $tag, $tag);
        }
        
        return implode($delimiter, $data);
    }
}