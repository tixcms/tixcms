<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter CI_Loader class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Loader.php
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
class MX_Loader extends CI_Loader
{
	protected $_module;
	
	public $_ci_plugins = array();
	public $_ci_cached_vars = array();
	
	function __construct() 
    {
		parent::__construct();
		
		/* set the module name */
		$this->_module = CI::$APP->router->fetch_module();
		
		/* add this module path to the loader variables */
		$this->_add_module_paths($this->_module);
	}
	
	/** Initialize the module **/
	function _init($controller)
    {
		/* references to ci loader variables */
		foreach (get_class_vars('CI_Loader') as $var => $val) 
        {
			if ($var != '_ci_ob_level') $this->$var =& CI::$APP->load->$var;
		}
		
		/* set a reference to the module controller */
 		$this->controller = $controller;
 		$this->__construct();
	}

	/** Add a module path loader variables **/
	function _add_module_paths($module = '') 
    {
		if (empty($module))
        {
            return;
        }
		
		foreach (Modules::$locations as $location => $offset)
        {
			/* only add a module path if it exists */
			if (is_dir($module_path = $location.$module.'/')) 
            {
				array_unshift($this->_ci_model_paths, $module_path);
			}
		}
	}	
	
	/** Load a module config file **/
	function config($file = 'config', $use_sections = FALSE, $fail_gracefully = FALSE) 
    {
		return CI::$APP->config->load($file, $use_sections, $fail_gracefully, $this->_module);
	}
    
    function config_merge($file = 'config', $use_sections = FALSE, $fail_gracefully = FALSE) 
    {
		return CI::$APP->config->merge($file, $use_sections, $fail_gracefully, $this->_module);
	}

	/** Load the database drivers **/
	function database($params = '', $return = FALSE, $active_record = NULL) 
    {
        if( class_exists('CI_DB', FALSE) 
                AND $return == FALSE
                AND $active_record == NULL 
                AND isset(CI::$APP->db) 
                AND is_object(CI::$APP->db) )
        {
            return;
        }	

		require_once BASEPATH.'database/DB'.EXT;

		if($return === TRUE)
        {
            return DB($params, $active_record);
        }
			
		CI::$APP->db = DB($params, $active_record);
		
		return CI::$APP->db;
	}

	/** Load a module helper **/
	function helper($helper)
    {
		if(is_array($helper))
        {
            return $this->helpers($helper);
        }
		
		if(isset($this->_ci_helpers[$helper]))
        {
            return;
        }

		list($path, $_helper) = Modules::find($helper.'_helper', $this->_module, 'helpers/');

		if($path === FALSE)
        {
            return parent::helper($helper);
        }

		Modules::load_file($_helper, $path);
		$this->_ci_helpers[$_helper] = TRUE;
	}

	/** Load a module language file **/
	function language($langfile, $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '', $module = false)
    {
		return CI::$APP->lang->load($langfile, $idiom, $return, $add_suffix, $alt_path, $module ? $module : $this->_module);
	}
	
	function languages($languages)
    {
		foreach($languages as $_language)
        {
            $this->language($language);
        }
	}
	
	/** Load a module library **/
    function library($library, $params = array(), $object_name = NULL)
    {
        // загружаем библиотеку из модулей
        if( class_exists($library) )
        {
            // проверяем существует ли пользовательский класс
            $extended_class = 'App\\'. $library;

            if( class_exists($extended_class) )
            {                    
                $class = new $extended_class($params);
            }
            else
            {
                $class = new $library($params);
            }            
            
            if( $object_name )
            {
                CI::$APP->$object_name = $class;
            }
            
            return $class;
        }
        // загружаем библиотеку CodeIgniter`а
        else
        {
    		$class = strtolower(basename($library));
    
    		if (isset($this->_ci_classes[$class]) AND $_alias = $this->_ci_classes[$class])
            {
                return CI::$APP->$_alias;
            }
            
    		($_alias = strtolower($object_name)) OR $_alias = $class;
    		
    		list($path, $_library) = Modules::find(strtolower($library), $this->_module, 'libraries/');
    		
    		/* load library config file as params */
    		if ($params == NULL) 
            {
    			list($path2, $file) = Modules::find($_alias, $this->_module, 'config/');	
    			($path2) AND $params = Modules::load_file($file, $path2, 'config');
    		}
    			
    		if ($path === FALSE)
            {
    			$this->_ci_load_class($library, $params, $object_name);
    			$_alias = $this->_ci_classes[$class];
    		}
    		
    		return CI::$APP->$_alias;
        }
    }

	/** Load an array of libraries **/
	function libraries($libraries)
    {
		foreach ($libraries as $_library)
        {
            $this->library($_library);
        }
	}

	/** Load a module model **/
	function model($model, $object_name = NULL, $connect = FALSE)
    {
		if (is_array($model))
        {
            return $this->models($model);
        }

		($_alias = $object_name) OR $_alias = basename($model);

		if (in_array($_alias, $this->_ci_models, TRUE)) 
        {
            return CI::$APP->$_alias;
        }
			
		/* check module */
		list($path, $_model) = Modules::find(strtolower($model), $this->_module, 'models/');
		
		if ($path == FALSE)
        {
			/* check application & packages */
			parent::model($model, $object_name);
			
		} 
        else 
        {
			class_exists('CI_Model', FALSE) OR load_class('Model', 'core');
			
			if ($connect !== FALSE AND ! class_exists('CI_DB', FALSE)) 
            {
				if ($connect === TRUE)
                {
                    $connect = '';
                } 
                
				$this->database($connect, FALSE, TRUE);
			}
			
			Modules::load_file($_model, $path);
			
			$model = ucfirst($_model);
			CI::$APP->$_alias = new $model();
			
			$this->_ci_models[] = $_alias;
		}
		
		return CI::$APP->$_alias;
	}

	/** Load an array of models **/
	function models($models)
    {
		foreach ($models as $_model)
        {
            $this->model($_model);
        }
	}

	/** Load a module controller **/
	function module($module, $params = NULL)
    {
		if (is_array($module))
        {
            return $this->modules($module);
        }

		$_alias = strtolower(basename($module));
		CI::$APP->$_alias = Modules::load(array($module => $params));
		return CI::$APP->$_alias;
	}

	/** Load an array of controllers **/
	function modules($modules)
    {
		foreach ($modules as $_module)
        {
            $this->module($_module);
        }
	}

	/** Load a module view **/
	function view($view, $vars = array(), $return = false, $templates_dirs = array())
    {
        $this->_ci_view_paths = array();
        foreach($templates_dirs as $dir)
        {
            $this->_ci_view_paths[] = $dir;            
        }

		return $this->_ci_load(array(
            '_ci_view'=>$view, 
            '_ci_vars'=>$this->_ci_object_to_array($vars), 
            '_ci_return'=>$return
        ));
	}

	function _ci_is_instance() {}

	function _ci_get_component($component)
    {
		return CI::$APP->$component;
	} 

	function __get($class)
    {
        if( isset(CI::$APP->di) )
        {
            if( CI::$APP->di->has_service($class) )
            {
                return CI::$APP->di->$class;
            }
        }
        
        if( (isset($this->controller)) )
        {
            return $this->controller->$class;
        }
        else
        {
            return CI::$APP->$class;
        }
	}

	function _ci_load($_ci_data)
    {
        
        $file_exists = FALSE;
		
		foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val)
        {
			$$_ci_val = ( ! isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
		}

		if ($_ci_path == '') 
        {
            // расширение
		    $_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
			$_ci_file = ($_ci_ext == '') ? $_ci_view.EXT : $_ci_view;
            
            // ищем файл
            foreach ($this->_ci_view_paths as $view_file)
    		{
    			if (file_exists($view_file.$_ci_file))
    			{
    				$_ci_path = $view_file.$_ci_file;
    				$file_exists = TRUE;
    				break;
    			}
    		}
		} 
        else 
        {
            $file_exists = TRUE;
			$_ci_file = basename($_ci_path);
		}
        
        // если не нашли, выводим ошибку
        if ( ! $file_exists && ! file_exists($_ci_path))
		{
			show_error('Unable to load the requested file: '.$_ci_file);
		}

		if (is_array($_ci_vars))
        {
            $this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
        }
		
		extract($this->_ci_cached_vars);

		ob_start();

		if ((bool) @ini_get('short_open_tag') === FALSE 
                AND CI::$APP->config->item('rewrite_short_tags') == TRUE) 
        {
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
		} 
        else 
        {
			include($_ci_path); 
		}

		log_message('debug', 'File loaded: '.$_ci_path);

		if ($_ci_return == TRUE)
        {
            return ob_get_clean();
        }

		if (ob_get_level() > $this->_ci_ob_level + 1)
        {
			ob_end_flush();
		} 
        else 
        {
			CI::$APP->output->append_output(ob_get_clean());
		}
	}		
	
	/** Autoload module items **/
	function _autoloader($autoload) {}
}

/** load the CI class for Modular Separation **/
(class_exists('CI', FALSE)) OR require dirname(__FILE__).'/Ci.php';