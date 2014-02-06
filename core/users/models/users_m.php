<?php

class Users_m extends Tix\Model {

    public $table = 'users';
    public $entity = 'Users\Entity';
    
    const STATUS_ACTIVATED = 1;
    const STATUS_NOT_ACTIVATED = 2;
    
    function get_all()
    {
        return parent::get_all();
    }
    
    function by_group($group)
    {
        $this->where('group_alias', $group);
        return $this;
    }
    
    function by_group_not($group)
    {
        $this->where('group_alias !=', $group);
        return $this;
    }
    
    /**
     * Пользователи онлайн
     */
    function get_online()
    {
        $cache_file = 'users_online';
        $this->load->library('cache');
        
        $users = $this->cache->get($cache_file);
        
        if( !$users )
        {
            $expires = 5; // in minutes
            $last_activity_time = time() - $expires*Date::SECONDS_IN_MINUTE;
            
            $this->db->select('sessions.last_activity, u.name, u.id');
            $this->db->where('user_id >', 0);
            $this->db->where('last_activity >', $last_activity_time);
            $this->db->join($this->table.' AS u', 'u.id = sessions.user_id');
            $query = $this->db->get('sessions');
            
            $data = $query->num_rows() > 0 ?$query->result() : false;
            
            if( $data )
            {
                $users = array();
                foreach($data as $item)
                {
                    $users[$item->id] = array(
                        'name'=>$item->name,
                        'time'=>$item->last_activity
                    );
                }
            }
            
            // записываем в кеш
            $this->cache->write($users, $cache_file, $expires);
        }
        
        return $users;
    }
    
    /**
     * Обовляет время последнего посещения текущего пользователя
     */
    function last_visit_time_update()
    {
        $this->where('id', $this->user->id);
        $this->set('lastvisit_date', time());
        $this->update();
    }
    
    function deleted()
    {
        $this->where('is_deleted', 1);
        return $this;
    }
    
    function not_deleted()
    {
        $this->where('is_deleted', 0);
        return $this;
    }
    
    function all()
    {
        return $this;
    }
    
    function count_new()
    {
        return $this->not_moderated()->count();
    }
    
    function not_moderated()
    {
        $this->where('is_moderated', 0);
        $this->not_deleted();
        return $this;
    }
    
    function not_activated()
    {
        $this->where('is_active', 0);
        $this->not_deleted();
        return $this;
    }
    
    function activated()
    {
        $this->where('is_active', 1);
        $this->not_deleted();
        return $this;
    }
}