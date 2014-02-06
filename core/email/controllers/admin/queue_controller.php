<?php

class Queue_Controller extends Email\Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('queue_m');
    }
    
    function action_index($page = 1)
    {
        $limit = 20;
        $offset = ($page - 1)*$limit;
        
        $total = $this->queue_m->count();        
        $items = $this->queue_m->limit($limit)->offset($offset)->order_by('priority DESC, created_on ASC')->get_all();
        
        $pager = new Tix\Pagination;
        $pager->set_total($total);
        $pager->set_page($page);
        $pager->set_url('admin/email/queue/index');
        $pager->set_per_page($limit);
        
        $this->render(array(
            'items'=>$items,
            'total'=>$total,
            'pager'=>$pager
        ));
    }
    
    function action_delete_all()
    {
        $this->queue_m->where(1, 1, FALSE)->delete();
        
        echo json_encode(array(
            'type'=>'success',
            'text'=>'Письма удалены'
        ));
    }
}