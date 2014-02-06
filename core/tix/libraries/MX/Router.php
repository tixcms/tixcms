<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX core module class */
require dirname(__FILE__).'/Modules.php';

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter router class.
 *
 * Install this file as application/third_party/MX/Router.php
 *
 * @copyright	Copyright (c) 2011 Wiredesignz
 * @version 	5.4
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Router extends CI_Router
{
	protected $module;
    public $page = false;
    public $all_pages = array();
    
    function fetch_method()
    {
        $class = str_replace($this->config->item('controller_suffix'), '', $this->fetch_class());
        
        if ($this->method == $class)
		{
			return 'action_index';
		}

		return 'action_'. $this->method;
    }
    
    /**
     * Возращает имя текущего контроллера
     */
    function fetch_controller()
    {
        return str_replace('_controller', '', $this->fetch_class());
    }
    
    /**
     * Возращает имя текущего экшена
     */
    function fetch_action()
    {
        return str_replace('action_', '', $this->fetch_method());
    }
	
	function fetch_module() {
		return $this->module;
	}
	
	function _validate_request($segments) 
    {
		if (count($segments) == 0)
        {
            return $segments;
        }
		
		/* locate module controller */
		if ($located = $this->locate($segments))
        {
            return $located;
        }
        
		/* use a default 404_override controller */
		if (isset($this->routes['404_override']) AND $this->routes['404_override']) 
        {
			$segments = explode('/', $this->routes['404_override']);

			if ($located = $this->locate($segments))
            {
                return $located;
            }
		}
		
		/* no controller found */
		show_404();
	}
    
    /** Locate the controller **/
	function locate($segments) 
    {
        $this->module = '';
		$this->directory = '';
		$ext = $this->config->item('controller_suffix').EXT;
        
        /* get the segments array elements */
		list($module, $directory, $controller) = array_pad($segments, 3, NULL);
        
        /**
         * Админский роутинг
         */
        if( $module == 'admin' AND $directory AND $directory != 'logout' )
        {
            $module = $segments[1];
            
            /* use module route if available */
    		if ($routes = Modules::parse_routes($module, implode('/', $segments))) 
            {
    			$segments = $routes;
                unset($segments[0]);
                $segments = array_values($segments);
    		}
            
            return $this->admin_locate($segments, $module, $ext);
        }
        
        if( $this->routes['default_controller'] == '/' )
        {
            list($module, $segments) = $this->page_route($module, $directory, $segments);
        }
        
        /* use module route if available */
		if ($routes = Modules::parse_routes($module, implode('/', $segments))) 
        {
			$segments = $routes;
            unset($segments[0]);
            $segments = array_values($segments);
		}
        
        // максимальная вложенность папок
        $max = 3;
        
        /* check modules */
		foreach (Modules::$locations as $location => $offset) 
        {
            /* module exists? */
            $source = $location.$module.'/controllers/';
            if (is_dir($source))
            {
                $this->module = $module;
				$this->directory = $offset.$module.'/controllers/';
                
                // определяем в скольких папках будем искать
                $dir_segments = array_slice($segments, 1, $max);
                
                // если есть подпаки, ищем в них
                if( count($dir_segments) > 0 )
                {
                    // всего сегментов
                    $total = count($dir_segments);
                    
                    for($i=$total; $i>0; $i--)
                    {
                        // существует ли контроллер
                        if( file_exists($source . implode(DIRECTORY_SEPARATOR, $dir_segments) . $ext ) )
                        {
                            $slice = $i;
                            
                            // путь до папки с контроллером
                            unset($dir_segments[$i - 1]);
                            $controller_folder = implode(DIRECTORY_SEPARATOR, $dir_segments) . '/';
                            
                            // устанавливаем директорию
                            $this->directory = $this->directory . $controller_folder;
                            
                            // определяем сегменты и останавливаем цикл
                            $segments = array_slice($segments, $slice);                            
                            break;
                        }
                        
                        unset($dir_segments[$i - 1]);
                    }
                }
                
                return $segments;
            }
        }
        
        return FALSE;
	}
    
    function admin_locate($segments, $module, $ext)
    {
        // максимальная вложенность папок
        $max = 3;
        
        if( count($segments) > 2 )
        {
            $foo = $segments;
            unset($foo[1]);
            array_unshift($foo, $module);
            $routes[] = $foo;
        }
        
        array_unshift($segments, $module);
        $routes[] = $segments;
        
        foreach($routes as $route)
        {
            $segments = $route;
        
            /* check modules */
    		foreach (Modules::$locations as $location => $offset) 
            {
                /* module exists? */
                $source = $location.$module.'/controllers/admin/';
                if (is_dir($source))
                {
                    $this->module = $module;
    				$this->directory = $offset.$module.'/controllers/admin/';
                    
                    // определяем в скольких папках будем искать
                    $dir_segments = array_slice($segments, 2, $max);
                    
                    // если есть подпаки, ищем в них
                    if( count($dir_segments) > 0 )
                    {
                        // всего сегментов
                        $total = count($dir_segments);
                        for($i=$total; $i>0; $i--)
                        {
                            // существует ли контроллер
                            if( file_exists($source . implode(DIRECTORY_SEPARATOR, $dir_segments) . $ext ) )
                            {
                                unset($dir_segments[$i - 1]);
                                
                                $slice = $i + 1;
                                
                                if( !empty($dir_segments) )
                                {
                                    $this->directory = $this->directory . implode(DIRECTORY_SEPARATOR, $dir_segments) .'/';
                                }
                                
                                return array_slice($segments, $slice);
                            }
                            unset($dir_segments[$i - 1]);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Возвращает все страницы, к которым прикреплен модуль
     * Нужен для формирования правильных ссылок
     */
    function get_all_pages($db)
    {
        $result = $db->query("SELECT * FROM ". $db->dbprefix('pages') ." WHERE module != ''");
        
        $pages = array();
        if( $result->num_rows() )
        {
            foreach($result->result() as $item)
            {
                $pages[$item->module] = $item;
            }
        }
        
        return $pages;
    }
    
    /**
     * Урлы для страниц
     */
    function page_route($module, $directory, $segments)
    {
        if( $this->is_system_installed() AND $module != 'admin' AND $directory != 'logout' )
        {
            /**
             * Сначала ищем урл в страницах по первому сегменту
             * Там может быть либо дополнение, либо страница
             * 
             * Если не найден, то проверяем модули с is_service = 1
             * Так называемые сервисы, например, комментарии
             * 
             * Если и там не найден, то 404
             */
            require_once(BASEPATH.'database/DB.php');        
            $db = DB('', true);
            
            $url = $db->escape($segments[0]);
            
            if( $segments[0] )
            {
                $where = "url = $url";
            }
            else
            {
                $where = 'is_main = 1';
            }
            
            $routes = $db->query("SELECT * FROM ". $db->dbprefix('pages') ." WHERE level != 0 AND is_active = 1 AND $where LIMIT 1");
            
            $this->all_pages = $this->get_all_pages($db);
            
            if( $routes->num_rows() )
            {
                $route = $routes->row();
                
                $this->page = $route;
                
                if( $route->module )
                {
                    if( !$segments[0] )
                    {
                        $segments[1] = 'index';
                    }
                    
                    $segments[0] = $route->module;
                    $module = $route->module;
                }
                else
                {
                    $segments[0] = 'pages';
                    $segments[1] = 'view';
                    $module = 'pages';
                }
            }
            else
            {
                $routes = $db->query("SELECT * FROM ". $db->dbprefix('modules') ." WHERE is_service = 1 AND url = $url LIMIT 1");
                if( $routes->num_rows() )
                {
                    $route = $routes->row();
                    
                    $this->page = $route;
                    
                    $segments[0] = $route->url;
                    $module = $route->url;
                }
                else
                {
                    $segments[0] = 'pages';
                    $segments[1] = 'errors';
                    $segments[2] = '404';
                }
            }
        }
        
        return array($module, $segments);
    }

	function set_class($class) 
    {
		$this->class = $class.$this->config->item('controller_suffix');
	}
    
    function is_system_installed()
    {
        return file_exists(APPPATH .'config/installed');
    }
}