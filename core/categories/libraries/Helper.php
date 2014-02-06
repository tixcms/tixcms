<?php

namespace Categories;

use CI;

class Helper 
{
    function options($where, $default = FALSE, $indent_string = '', $indent_start_level = 1)
    {
        CI::$APP->load->model('categories/categories_m');
        
        $categories = CI::$APP->categories_m->where($where)->order_by('lft', 'ASC')->get_all();
        
        if( $default )
        {
            $options = is_array($default) ? $default : array($default);
        }
        else
        {
            $options = array();
        }
        
        if( $categories )
        {
            foreach($categories as $category)
            {
                $indent = $category->level >= $indent_start_level 
                            ? str_repeat($indent_string, $category->level - $indent_start_level) 
                            : '';
                
                $options[$category->id] = $indent . $category->title;
            }
        }
                
        return $options;
    }
    
    /**
     * Пересчет количества элементов в категориях
     * 
     * @param string Путь для загрузки модели связанной таблице [news.news_m] (через точку вместо слеша)
     * @param string Имя модели связанной таблице [news_m]
     * @param string Имя поля id категории в связанной таблице [cat_id]
     * @param string Метод для фильтрации значений [visible]
     */
    function items_recount($module, $model_path, $model_alias, $category_field, $filter = false)
    {        
        \CI::$APP->load->model($model_path);
        \CI::$APP->load->model('categories/categories_m');
        
        if( $cats = \CI::$APP->categories_m->by_module($module)->order_by('lft', 'ASC')->get_all() )
        {
            foreach($cats as $cat)
            {
                \CI::$APP->categories_m->by_module($module);                
                $childs = \CI::$APP->categories_m->get_childs($cat);
                $ids = array($cat->id);
                
                if( $childs )
                {
                    foreach($childs as $child)
                    {                    
                        $ids[] = $child->id;
                    }
                }
                
                if( $filter )
                {
                    \CI::$APP->$model_alias->$filter();
                }
                
                $count = \CI::$APP->$model_alias->where_in($category_field, $ids)->count();
            
                \CI::$APP->categories_m->where('id', $cat->id)->set_items($count)->update();
            }
        }
    }
}