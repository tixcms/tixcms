<?php

namespace Categories;

class Tree {

    /**
     * Массив категорий
     */
    protected $categories;
    
    /**
     * Текущая категория
     */
    protected $currentCategory;
    
    /**
     * Текущий элемент
     */
    protected $current;
    
    /**
     * 
     */
    protected $output = '';
    
    /**
     * Номер первого элмента
     */
    protected $firstElement = 0;
    
    /**
     * Номер последнего элемента
     */
    protected $lastElement;
    
    /**
     * Всего элменентов
     */
    protected $totalElements;

    /**
     * Открывающий тег списков, включая вложенные
     */
    protected $listOpenTag = '<ul>';
    
    /**
     * Закрывающий тег списков, включая вложенные
     */
    protected $listCloseTag = '</ul>';
    
    /**
     * Открывающий тег элемента
     */
    protected $elementOpenTag = '<li>';
    
    /**
     * Закрывающий тег элемента
     */
    protected $elementCloseTag = '</li>';
    
    /**
     * Ссылка, на которую будет вести элемент
     * {id} заменяется на id элемента
     */
    protected $url = '{id}';
    
    protected $content = '{title}';
    
    protected $indent = '&nbsp;';
    
    protected $start_level = 0;
    
    /**
     * Класс, который будет присвоен элемент без потомков
     */
    protected $leafClass = 'leaf';
    
    static function create($categories, $currentCategory = false, $params = array())
    {
        return new static($categories, $currentCategory, $params);
    }

    function __construct($categories, $currentCategory = false, $params = array())
    {
        if( $params )
        {
            foreach($params as $key=>$value)
            {
                $this->$key = $value;
            }
        }
        
        $this->currentCategory = $currentCategory;
        $this->categories = (array)$categories;
        $this->totalElements = count($categories);
        $this->lastElement = $this->totalElements - 1;
    }

    function render()
    {
        for($this->current=0; $this->current < $this->totalElements; $this->current++)
        {
            if( $this->current == 0 )
            {
                $this->start_level = $this->categories[$this->current]->level;
            }
            
            $prev = $this->current - 1;
            $next = $this->current + 1;

            $this->categories[$this->current] = (array)$this->categories[$this->current];
            
            if( isset($this->categories[$next]) )
            {
                $this->categories[$next] = (array)$this->categories[$next];
            }
            
            $url = str_replace('{id}', $this->categories[$this->current]['id'], $this->url);

            if($this->_isFirstElement())
            {
                $this->output .= $this->listOpenTag
                    . str_replace('{id}', $this->categories[$this->current]['id'], $this->elementOpenTag);
            }

            $this->output .= $this->replace($this->categories[$this->current]);

            if($this->_isLastElement())
            {
                $this->output .= $this->elementCloseTag . $this->listCloseTag;
                $this->output .= str_repeat(
                    $this->elementCloseTag . $this->listCloseTag, 
                    $this->categories[$this->current]['level'] - $this->categories[$this->firstElement]['level']
                );
            }
            else
            {
                if($this->categories[$next]['level'] == $this->categories[$this->current]['level'])
                {
                    $this->output .= $this->elementCloseTag 
                        . str_replace('{id}', $this->categories[$next]['id'], $this->elementOpenTag);
                }
                elseif($this->categories[$next]['level'] > $this->categories[$this->current]['level'])
                {
                    $this->output .= $this->listOpenTag 
                        . str_replace('{id}', $this->categories[$next]['id'], $this->elementOpenTag);
                }
                elseif($this->categories[$next]['level'] < $this->categories[$this->current]['level'])
                {
                    $this->output .= str_repeat(
                        $this->elementCloseTag . $this->listCloseTag, 
                        $this->categories[$this->current]['level'] - $this->categories[$next]['level']
                    ) . $this->elementCloseTag 
                        . str_replace('{id}', $this->categories[$next]['id'], $this->elementOpenTag);
                }
            }
        }

        return $this->output;
    }
    
    function replace($content)
    {
        $search = array(
            '{id}', 
            '{title}', 
            '{url}', 
            '{indent}',
            '{level}',
            '{items}'
        );
        
        $replace = array(
            $content['id'], 
            $content['title'], 
            '', 
            str_repeat($this->indent, $content['level'] - $this->start_level),
            $content['level'],
            isset($content['items']) ? $content['items'] : ''
        );
        
        return str_replace(
            $search, 
            $replace,
            $this->content
        );
    }

    function _isLeafElement()
    {
        return $this->categories[$this->current]['rgt'] - $this->categories[$this->current]['lft'] == 1;
    }

    function _isFirstElement()
    {
        return $this->current == $this->firstElement;
    }

    function _isLastElement()
    {
        return $this->current == $this->lastElement;
    }
}