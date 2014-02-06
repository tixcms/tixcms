<?php

namespace HTML;

class Tag
{
    static function description($content)
    {
        return self::meta(array('name'=>'description', 'content'=>$content));
    }
    
    static function keywords($content)
    {
        return self::meta(array('name'=>'keywords', 'content'=>$content));
    }
    
    static function meta($attributes)
    {
        return '<meta '. self::parse_attributes($attributes) .' />';
    }
    
    static function a($href, $label, $attributes = '')
    {
        $attributes['href'] = $href;
        return self::tag('a', $label, $attributes);
    }
    
    /**
     * Generate script tag
     */
    static function script($attributes)
    {
        return '<script '. self::parse_attributes($attributes) .'></script>';
    }
    
    /**
     * Generate script tag for js file
     */
    static function js($src)
    {
        $attributes['src'] = $src;
        
        return self::script($attributes);
    }
    
    /**
     * Generate image tag
     * 
     * srting src path to image
     * mixed attributes
     */
    static function img($src, $attributes = '')
    {
        return '<img src="'. $src .'" '. self::parse_attributes($attributes) .' />';
    }
    
    /**
     * HTML doctype
     */
    static function doctype($doctype = '')
    {
        switch($doctype)
        {
            default:
                return '<!DOCTYPE html>';
        }
    }
    
    /**
     * Generate meta tag with charset
     */
    static function charset($charset)
    {
        return '<meta http-equiv="Content-Type" content="text/html; charset='. $charset .'" />';
    }
    
    /**
     * Generate title tag
     */
    static function title($title)
    {
        return '<title>'. $title .'</title>';
    }
    
    /**
     * Generate link tag
     */
    static function link($attributes)
    {
        return '<link '. self::parse_attributes($attributes) .'>';
    }
    
    /**
     * Generate link tag for css style
     */
    static function css($href, $attrs = array())
    {
        $attributes['href'] = $href;
        $attributes['type'] = 'text/css';
        $attributes['rel'] = 'stylesheet';
        
        if( $attrs )
        {
            foreach($attrs as $key=>$value)
            {
                $attributes[$key] = $value;
            }
        }
        
        return self::link($attributes);
    }
    
    static function tag($name, $value, $attributes)
    {
        return '<'. $name .' '. self::parse_attributes($attributes) .'>'. $value .'</'. $name .'>';
    }
    
    /**
     * parse attributes
     */
    static function parse_attributes($attributes = array())
    {
        $attributesResult = '';
        if( is_array($attributes) AND !empty($attributes) )
        {
            foreach($attributes as $attribute => $value)
            {
                $attributesResult[] = $attribute .'="'.$value .'"';
            }
            
            $attributesResult = implode(' ', $attributesResult);
        }
        
        return $attributesResult;
    }
    
    static function textarea($name, $value, $attributes = ''){
        return '<textarea name="'. $name .'" '. self::parse_attributes($attributes) .'>'. $value .'</textarea>';
    }
    
    static function options($options, $default = false)
    {
        $html = '';
        foreach($options as $value => $content)
        {
            $html .= self::option($value, $content, ($default !== false AND ($value == $default OR (is_array($default) AND in_array($value, $default)))) ? true : false);
        }
        
        return $html;
    }
    
    static function option($value, $content, $selected = false, $attributes = '')
    {
        return '<option value="'. $value .'"'. ($selected ? ' selected="selected" ' : ' ') . self::parse_attributes($attributes) .' >'. $content  .'</option>';
    }
    
    static function button($name, $value, $attributes = '')
    {
        $attributes['name'] = $name;
        
        return self::tag('button', $value, $attributes);
    }
}