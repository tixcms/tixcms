<?php

\CI::$APP->load->model('categories/categories_m');

class Pages_m extends Categories_m
{
    public $table = 'pages';

    function __construct()
    {
        parent::__construct();
    }
    
    function get_default()
    {
        return $this->by_is_main(1)->get_one();
    }
    
    /**
     * Исправляет вложенные ссылки на страницы
     * 
     * Ссылки на страницы имеют вид parent/parent/page_url
     * При изменении иерархии, нужно изменить ссылки в соответствии с новой вложенностью
     */
    function check_url_consistency()
    {
        $pages = $this->order_by('lft', 'ASC')->where('level !=', 0)->get_all();

        if( $pages )
        {
            $url[] = '';
            $parent_url = '';
            $level = 1;
            foreach($pages as $page)
            {
                // уровень вложенности увеличивается
                if( $page->level > $level )
                {
                    $url[] = $parent_url;
                }
                // уровень вложенности уменьшается
                else if( $page->level < $level )
                {
                    // удаляем на соответствующий уровень pre_url
                    for($i=0; $i<($level - $page->level); $i++)
                    {
                        array_pop($url);
                    }
                }
                
                $preurl = implode('/', $url);                
                $this->pages_m->by_id($page->id)->set_pre_url($preurl)->update();
                
                $level = $page->level;
                $parent_url = $page->url;
            }
        }
    }

    /**
     * Метод возвращает категории в таком порядке:
     * - все корневые
     * - первого потомка
     * - соседей первого потомка, если не корневая
     * - если не корневая, родителей
     *
     * @param $parent
     * @param $current_page
     * @return mixed
     */
    function get_tree_childs($parent, $current_page)
    {
        if( is_numeric($parent) )
        {
            $parent = $this->by_id($parent)->get_one();
        }
        
        $more = '';
        if( $current_page->level > 1 )
        {
            $direct_parent = $this->where('lft <', $current_page->lft)->where('rgt >', $current_page->rgt)->get_one();
            
            $extra_sql = $direct_parent ? "OR (lft > $direct_parent->lft AND rgt < $direct_parent->rgt)" : '';
            
            $more = " OR (lft > $current_page->lft AND rgt < $current_page->rgt AND level = $current_page->level + 1) 
                OR (lft <= $current_page->lft AND rgt >= $current_page->rgt AND level > 1)
                $extra_sql
            ";
        }
        
        $this->where(
            "(lft > $parent->lft AND rgt < $parent->rgt AND level = 2)
            $more
            ",
            '',
            false
        );
        
        return $this->order_by('lft', 'ASC')->get_all();
    }       
}