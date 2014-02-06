<?php

namespace Helpers;

class File
{
    /**
     * Удаляет папку или файл
     * Папку удаляет со всем содержимым
     */
    static function remove($path)
    {
        if( file_exists($path) )
        {
            if( is_dir($path) )
            {
                $dir = opendir($path);
        
                while( ($item = readdir($dir)) !== FALSE )
                {
                    if( $item != '.' AND $item != '..' )
                    {
                        $item_path = $path . '/'. $item;
                        
                        if( is_dir($item_path) )
                        {                    
                            self::remove($item_path);
                        }
                        else
                        {
                            unlink($item_path);
                        }
                    }
                }
                
                rmdir($path);
                closedir($dir);
            }
            else
            {
                unlink($path);
            }
        }
    }
    
    /**
     * Создает дерево папок если их не существует
     */
    static function make_path($pathname, $is_filename = false)
    {
        $segments = explode('/', $pathname);
        $path = '';
        
        $i=0;
        foreach($segments as $segment)
        {
            $path .= $segment .'/';
            
            // если подпапка, проверяем существует ли она и если нет, то создаем
            if( !$is_filename OR ($is_filename AND $i != count($segments) - 1) )
            {
                if( !file_exists($path) )
                {                    
                    mkdir($path);
                }
            }
            
            $i++;
        }
        
        return TRUE;     
    }
    
    function get_mime_by_extension($file)
	{
		$extension = strtolower(substr(strrchr($file, '.'), 1));

		global $mimes;

		if ( ! is_array($mimes))
		{
			if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/mimes.php'))
			{
				include(APPPATH.'config/'.ENVIRONMENT.'/mimes.php');
			}
			elseif (is_file(APPPATH.'config/mimes.php'))
			{
				include(APPPATH.'config/mimes.php');
			}

			if ( ! is_array($mimes))
			{
				return FALSE;
			}
		}

		if (array_key_exists($extension, $mimes))
		{
			if (is_array($mimes[$extension]))
			{
				// Multiple mime types, just give the first one
				return current($mimes[$extension]);
			}
			else
			{
				return $mimes[$extension];
			}
		}
		else
		{
			return FALSE;
		}
	}
}