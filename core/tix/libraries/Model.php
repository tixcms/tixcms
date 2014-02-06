<?php

namespace Tix;

class Model 
{
    public $table = null;
    public $primary_key = 'id';
    public $as_array = false;
    public $entity = false;
    public $module = false;
    public $attributes = false;
    
    public $relation_object = false;
    public $relation_prefix = false;
    public $relation_entity = false;
    
    public $join_objects = array();

    function __construct($table = false)
    {
        // название модуля
        $this->module = str_replace('_m', '', get_class($this));

        if( $this->table == null AND $this->table !== FALSE )
        {
            $this->table = $table ? $table : strtolower($this->module);
        }
        
        $this->table = $this->db->dbprefix($this->table);
        
        if( !$this->entity )
        {
            $this->entity = $this->module . '\Entity';
        }
        
        /*
        if( $this->table !== FALSE AND !$this->attributes AND $this->table AND $this->table != 'my_model'  )
        {
            $this->_get_attributes();
        }
        */
    }
    
    function _relations()
    {
        return array();
    }
    
    function _attributes()
    {
        return array();
    }
    
    function _get_attributes()
    {
        $this->attributes = $this->db->list_fields($this->table);
    }

    function rand()
    {
        $this->order_by('RAND()');
        return $this;
    }
    
    function with($relation, $attributes = array(), $join_type = '')
    {
        $relations = $this->_relations();
        
        $data = $relations[$relation];
        
        $model_to_load = $data[0];
        $this->load->model($model_to_load);
        $temp = explode('/', $model_to_load);
        $relation_model = $temp[1];
        $relation_table = $this->$relation_model->table;
        $relation_pk = $this->$relation_model->primary_key;
        $relation_fk = $data[1];

        /*
        $this->relation_object = $data[2];
        $this->relation_prefix = $relation .'_prefix_';
        $this->relation_entity = $this->$relation_model->entity;
        */
        
        $this->join_objects[$relation .'_prefix_'] = array(
            'alias'=>$data[2],
            'prefix'=>$relation .'_prefix_',
            'entity'=>$this->$relation_model->entity
        );
        
        $this->$relation_model->_get_attributes();
        
        // определяем нужные аттрибуты
        $attrs = empty($attributes) ? $this->$relation_model->attributes : $attributes;
        $attributes = array();
        if( $attrs )
        {
            foreach($attrs as $attr)
            {
                $attributes[] = $relation_table .'.'. $attr .' AS '. $this->join_objects[$relation .'_prefix_']['prefix'] . $attr;
            }
        }
        
        $this->select($this->table.'.*');
        $this->select(implode(', ', $attributes));
        $this->db->join($relation_table, "$relation_table.$relation_pk = $this->table.$relation_fk", $join_type);

        return $this;
    }
    
    function _create_object($item)
    {        
        return $this->load->library($this->entity, array($item, $this->join_objects));
    }

    /**
     * Возвращает записи или FALSE если их нет
     */
    function get_all()
    {
        $query = $this->db->get($this->table);
        
        if( $query->num_rows() > 0 )
        {
            if( class_exists($this->entity) )
            {
                $items = array();
                
                foreach($query->result() as $item)
                {
                    $items[] = $this->_create_object($item);
                }
                
                return $items;
            }
            else
            {
                return $query->num_rows() > 0 ? $query->result() : FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Возвращает запись или FALSE если ее нет
     */
    function get_one()
    {
        $this->limit(1);
        $query = $this->db->get($this->table);
        
        if( $query->num_rows() == 1 )
        {
            if( class_exists($this->entity) )
            {
                return $this->_create_object($query->row());
            }
            else
            {
                return $query->row();
            }
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Возвращает количество записей
     */
    function count()
    {
        return $this->db->count_all_results($this->table);
    }

    function insert($data = false)
    {
        $this->before_save();
        $this->before_insert();
        
        if($data)
        {
            $this->db->insert($this->table, $data);
        }
        else
        {
            $this->db->insert($this->table);
        }

        return $this->db->insert_id();
    }

    function update($data = array())
    {
        $this->before_save();
        $this->before_update();        
        
        return $this->db->update($this->table, $data);
    }
    
    function before_save(){}
    function before_update(){}
    function before_insert(){}
    
    /**
     * Удаляет записи
     */
    function delete()
    {
        return $this->db->delete($this->table);
    }
    
    /**
     * Возвращает название таблицы с префиксом
     */
    function prefix($table)
    {
        return $this->db->dbprefix($table);
    }
    
    /**
     * Группирует элементы по какому-либо полю
     */
    function group($items, $field)
    {
        $result = array();
        
        if( $items )
        {
            foreach($items as $item)
            {
                $result[$item->$field][] = $item;
            }
        }
        
        return $result;
    }
    
    /**
	 * Sets the ORDER BY value
	 *
	 * @param	string
	 * @param	string	direction: asc or desc
	 * @return	object
	 */
	public function order_by($orderby, $direction = '', $protect = true)
	{
		if (strtolower($direction) == 'random')
		{
			$orderby = ''; // Random results want or don't need a field name
			$direction = $this->db->_random_keyword;
		}
		elseif (trim($direction) != '')
		{
			$direction = (in_array(strtoupper(trim($direction)), array('ASC', 'DESC'), TRUE)) ? ' '.$direction : ' ASC';
		}


		if (strpos($orderby, ',') !== FALSE)
		{
			$temp = array();
			foreach (explode(',', $orderby) as $part)
			{
				$part = trim($part);
				if ( ! in_array($part, $this->db->ar_aliased_tables))
				{
					$part = $protect ? $this->db->_protect_identifiers(trim($part)) : trim($part);
				}

				$temp[] = $part;
			}

			$orderby = implode(', ', $temp);
		}
		else if ($direction != $this->db->_random_keyword)
		{
			$orderby = $protect ? $this->db->_protect_identifiers($orderby) : $orderby;
		}

		$orderby_statement = $orderby.$direction;

		$this->db->ar_orderby[] = $orderby_statement;
		if ($this->db->ar_caching === TRUE)
		{
			$this->db->ar_cache_orderby[] = $orderby_statement;
			$this->db->ar_cache_exists[] = 'orderby';
		}

		return $this;
	}
    
    function __call($method, $args)
    {
        if( strstr($method, 'by_') )
        {
            $key = str_replace('by_', '', $method);
            $this->where($this->table .'.'. $key, $args[0]);
            return $this;
        }
        
        if( strstr($method, 'set_') )
        {
            $key = str_replace('set_', '', $method);
            $this->set($key, $args[0]);
            return $this;
        }
        
        if( method_exists($this->db, $method) )
        {
            call_user_func_array(array($this->db, $method), $args);
            
            return $this;
        }
        else
        {
            trigger_error('The required method "'. $method .'" does not exist for '. get_class($this), E_USER_ERROR);
        }
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}