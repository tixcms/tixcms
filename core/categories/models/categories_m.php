<?php

class Categories_m extends Tix\Model
{
    const TOP_LEVEL = 0;

    function create_base_category()
    {
        $this->set_lft(1);
        $this->set_rgt(2);
        $this->set_is_active(1);
        $this->set_level(self::TOP_LEVEL);
        
        return parent::insert();
    }
    
    function get_subcategories_ids($category)
    {
        $cats = $this->get_childs($category);
        $cat_ids = array();
        if( $cats )
        {
            $cat_ids = \Helpers\CArray::map($cats, 'id', 'id');
        }
        $cat_ids[] = $category->id;
        
        return $cat_ids;
    }

    /**
     * Удаление категории
     */
    function delete($id)
    {
        // выбираем категорию из таблицы
        $this->where('id', $id);
        $node = $this->get_one();
        
        $this->where('lft >=', $node->lft);
        $this->where('rgt <=', $node->rgt);
        $all_categories = $this->get_all();

        // удаляем потомков категории
        $this->where('lft >=', $node->lft);
        $this->where('rgt <=', $node->rgt);
        parent::delete();

        $subtract = $node->rgt - $node->lft + 1;

        // пересчитываем правые значения
        $this->where('rgt > ', $node->rgt);
        $this->set('rgt', 'rgt - '.$subtract, false);
        $this->update();

        // пересчитываем левые значения
        $this->where('lft > ', $node->rgt);
        $this->set('lft', 'lft - '.$subtract, false);
        $this->update();
        
        return $all_categories;
    }
    
    function delete_by_module($module)
    {
        // удаляем старые категории
        $items = $this->where('module', $module)->get_all();
        
        if( $items )
        {
            foreach($items as $item)
            {
                $this->delete($item->id);
            }
        }
        
        return true;
    }    

    function insert($data, $parent_id)
    {
        if( $parent_id != 0 )
        {
            $this->where('id', $parent_id);
        }
        else
        {
            $this->where('level', 0);
        }

        $parent = $this->get_one();

        // пересчитываем остальные
        $this->where('lft > ', $parent->rgt - 1);
        $this->set('lft', 'lft + 2', false);
        $this->update();

        $this->where('rgt > ', $parent->rgt - 1);
        $this->set('rgt', 'rgt + 2', false);
        $this->update();

        $data['lft']    = $parent->rgt;
        $data['rgt']    = $parent->rgt + 1;
        $data['level']  = $parent->level + 1;

        return parent::insert($data);
    }
    
    /**
     * Либо ID, либо элемент
     */
    function get_parent($item)
    {
        if( is_numeric($item) )
        {
            $item = $this->by_id($item)->get_one();
        }
        
        return $this->where('lft <', $item->lft)
                        ->where('rgt >', $item->rgt)
                        ->by_level($item->level - 1)
                        ->get_one();
    }
    
    function get_parents($item, $params = array())
    {
        if( is_numeric($item) )
        {
            $item = $this->by_id($item)->get_one();
        }
        
        $this->where($params);        
        return $this->where('lft <', $item->lft)
                        ->where('rgt >', $item->rgt)
                        ->get_all();
    }
    
    /**
     * Возвращает дочерние категории
     * 
     * @param mixed Либо объект, либо ID категории
     * @param bool Вовзращать только прямые дочерние категории
     */
    function get_childs($parent, $direct_childs = false)
    {
        if( is_numeric($parent) )
        {
            $parent = $this->by_id($parent)->get_one();
        }

        if( $direct_childs )
        {
            $this->by_level($parent->level + 1);
        }
        
        return $this->where('lft > ', $parent->lft)
                    ->where('rgt < ', $parent->rgt)
                    ->get_all();
    }
    
    function get_direct_childs($parent)
    {
        return $this->get_childs($parent, true);
    }

    /**
     * Увеличение количества элементов в категории на единицу,
     * включая родительские категории
     *
     * @param $category_id id категории
     */
    function increment_items($category_id)
    {
        $this->change_items_count($category_id, '+');
    }

    /**
     * Уменьшение количества элементов в категории на единицу,
     * включая родительские категории
     *
     * @param $category_id id категории
     */
    function decrement_items($category_id)
    {
        $this->change_items_count($category_id, '-');
    }

    /**
     * Increment and decrement items
     *
     * @param $category_id id категории
     * @param $operation [+, -]
     */
    private function change_items_count($category_id, $operation)
    {
        $category = $this->by_id($category_id)->get_one();

        $this->where('lft <=', $category->lft);
        $this->where('rgt >=', $category->rgt);

        $this->set('items', "items $operation 1", false);

        $this->update();
    }

    /*
    private function get_direction($changed_id, $old_order_ids, $new_order_ids)
    {
        $old_position = false;
        $new_position = false;
        
        $i=0;
        foreach($old_order_ids as $id)
        {
            if( $id == $changed_id )
            {
                $old_position = $i;
                break;
            }
            
            $i++;
        }
        
        $i=0;
        foreach($new_order_ids as $id)
        {
            if( $id == $changed_id )
            {
                $new_position = $i;
                break;
            }
            
            $i++;
        }
        
        return $new_position > $old_position ? 'down' : 'up';
    }
    
    function reorder($changed_id, $ids)
    {
        // находим родителя
        $parent = $this->get_parent($changed_id);
        
        // берем порядок элементов как они были до перестановки
        $this->order_by('lft', 'ASC');
        $items_as_before = $this->get_direct_childs($parent);
        
        $items_ids_as_before = array();
        foreach($items_as_before as $item)
        {
            $items_ids_as_before[] = $item->id;
        }
        
        // куда переместили категорию, вверх или вниз
        $direction = $this->get_direction($changed_id, $items_ids_as_before, $ids);

        if( $direction == 'up' )
        {
            // находим side элемент переместился
            $changed_id_new_position = false;
            $after_id = false;
            
            $i=0;
            foreach($ids as $id)
            {
                if( $id == $changed_id )
                {
                    $changed_id_new_position = $i;
                    break;
                }
                
                $i++;
            }
            
            $after_id = $ids[$changed_id_new_position + 1];
            
            // находим на сколько надо изменить левые элементы
            $changed = $this->by_id($changed_id)->get_one();
            $after = $this->by_id($after_id)->get_one();
            
            $changed_delta = $changed->lft - $after->lft;
            $after_delta = $changed->rgt - $changed->lft + 1;
            
            // находим id, которые надо изменить
            $changed_items = $this->where('lft >=', $changed->lft)
                                ->where('rgt <=', $changed->rgt)
                                ->get_all();
                  
            $changed_ids = array();       
            foreach($changed_items as $item)
            {
                $changed_ids[] = $item->id;
            }
            
            $after_items = $this->where('lft >=', $after->lft)
                                ->where('rgt <', $changed->lft)
                                ->get_all();
                  
            $after_ids = array();              
            foreach($after_items as $item)
            {
                $after_ids[] = $item->id;
            }
            
            // изменяем значения
            $this->where_in('id', $changed_ids)
                            ->set('lft', 'lft - '. $changed_delta, false)
                            ->set('rgt', 'rgt - '. $changed_delta, false)
                            ->update();
                            
            $this->where_in('id', $after_ids)
                            ->set('lft', 'lft + '. $after_delta, false)
                            ->set('rgt', 'rgt + '. $after_delta, false)
                            ->update();   
        }
        else
        {
            // находим side элемент переместился
            $changed_id_new_position = false;
            $before_id = false;
            
            $i=0;
            foreach($ids as $id)
            {
                if( $id == $changed_id )
                {
                    $changed_id_new_position = $i;
                    break;
                }
                
                $i++;
            }
            
            $before_id = $ids[$changed_id_new_position - 1];
            
            // находим на сколько надо изменить левые элементы
            $changed = $this->by_id($changed_id)->get_one();
            $before = $this->by_id($before_id)->get_one();
            
            $changed_delta = $before->rgt - $changed->rgt;
            $before_delta = $changed->rgt - $changed->lft + 1;
            
            // находим id, которые надо изменить
            $changed_items = $this->where('lft >=', $changed->lft)
                                ->where('rgt <=', $changed->rgt)
                                ->get_all();
                  
            $changed_ids = array();       
            foreach($changed_items as $item)
            {
                $changed_ids[] = $item->id;
            }
            
            $before_items = $this->where('lft >', $changed->rgt)
                                ->where('rgt <=', $before->rgt)
                                ->get_all();
                  
            $before_ids = array();              
            foreach($before_items as $item)
            {
                $before_ids[] = $item->id;
            }
            
            // изменяем значения
            $this->where_in('id', $changed_ids)
                            ->set('lft', 'lft + '. $changed_delta, false)
                            ->set('rgt', 'rgt + '. $changed_delta, false)
                            ->update();
                            
            $this->where_in('id', $before_ids)
                            ->set('lft', 'lft - '. $before_delta, false)
                            ->set('rgt', 'rgt - '. $before_delta, false)
                            ->update(); 
        }
    }
    */
}