<?php

namespace Tix;

class Profiler extends \CI_Profiler
{
    protected $_available_sections = array(
                                        'controller_info',
                                        'uri_string',                                        
										'benchmarks',
										'memory_usage',
                                        'get',
										'post',
										'queries',
										//'http_headers',
										//'session_data',
										//'config'
										);
                                        
    // --------------------------------------------------------------------

	/**
	 * Compile $_POST Data
	 *
	 * @return	string
	 */
	protected function _compile_post()
	{
        $output = 
        
        \CI::$APP->di->assets->render_css('tix::profiler.css')
        . \CI::$APP->di->assets->render_js('tix::profiler.js').
        
        '
        
        
        <style>.ci-profiler {
    height: 20px;
    width: 99.9%;
    position: fixed;
    bottom: 0px;
    -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.1),0 1px 10px rgba(0, 0, 0, 0.1);
    -moz-box-shadow: inset 0 -1px 0 rgba(0,0,0,0.1),0 1px 10px rgba(0,0,0,0.1);
    box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.1),0 1px 10px rgba(0, 0, 0, 0.1);
    background-image: -webkit-linear-gradient(top,white,#F2F2F2);
    background-image: -o-linear-gradient(top,white,#F2F2F2);
    background-image: linear-gradient(to bottom,white,#F2F2F2);
    background-repeat: repeat-x;
    border: 1px solid #D4D4D4;
    padding: 0;
    margin: 0;
}
.profiler-inner {
    padding: 0px 5px;
}
.profiler-section {
    margin-right: 20px;
}
.profiler-data {
    display: none;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    z-index: 100000;
    height: 100%;
}
.profiler-show-data {
    cursor: pointer;
}</style>'; 	   
		$output .= '<span class="profiler-section">';

		if (count($_POST) == 0)
		{
			$output .= '<span>$_POST[](0)</span>';
		}
		else
		{
            $output .= '<span class="profiler-show-data" data-id="profiler-post">$_POST[]('. count($_POST) .')</span>';
			$output .= "\n\n<table style='width:100%' class='profiler-data' id='profiler-post'>\n";

			foreach ($_POST as $key => $val)
			{
				if ( ! is_numeric($key))
				{
					$key = "'".$key."'";
				}

				$output .= "<tr><td style='width:50%;padding:5px;color:#000;background-color:#ddd;'>&#36;_POST[".$key."]&nbsp;&nbsp; </td><td style='width:50%;padding:5px;color:#009900;font-weight:normal;background-color:#ddd;'>";
				if (is_array($val))
				{
					$output .= "<pre>" . htmlspecialchars(stripslashes(print_r($val, TRUE))) . "</pre>";
				}
				else
				{
					$output .= htmlspecialchars(stripslashes($val));
				}
				$output .= "</td></tr>\n";
			}

			$output .= "</table>\n";
		}
		$output .= '</span>';

		return $output;
	}
    
    // --------------------------------------------------------------------

	/**
	 * Compile $_GET Data
	 *
	 * @return	string
	 */
	protected function _compile_get()
	{
		$output  = '<span class="profiler-section">';

		if (count($_GET) == 0)
		{
			$output .= '<span>$_GET[](0)</span>';
		}
		else
		{
            $output .= '<span class="profiler-show-data" data-id="profiler-get">$_GET[]('. count($_GET) .')</span>';
            
			$output .= "\n\n<table style='width:100%; border:none' class='profiler-data' id='profiler-get'>\n";

			foreach ($_GET as $key => $val)
			{
				if ( ! is_numeric($key))
				{
					$key = "'".$key."'";
				}

				$output .= "<tr><td style='width:50%;color:#000;background-color:#ddd;padding:5px'>&#36;_GET[".$key."]&nbsp;&nbsp; </td><td style='width:50%;padding:5px;color:#cd6e00;font-weight:normal;background-color:#ddd;'>";
				if (is_array($val))
				{
					$output .= "<pre>" . htmlspecialchars(stripslashes(print_r($val, true))) . "</pre>";
				}
				else
				{
					$output .= htmlspecialchars(stripslashes($val));
				}
				$output .= "</td></tr>\n";
			}

			$output .= "</table>\n";
		}
		$output .= '</span>';

		return $output;
	}
                                        
    /**
	 * Compile Queries
	 *
	 * @return	string
	 */
	protected function _compile_queries()
	{
		$dbs = array();

		// Let's determine which databases are currently connected to
		foreach (get_object_vars($this->CI) as $CI_object)
		{
			if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') )
			{
				$dbs[] = $CI_object;
			}
		}

        

		if (count($dbs) == 0)
		{	
            $output  = '<span class="profiler-section">';
            $output .= "Нет данных";
			$output .= "</span>";

			return $output;
		}

		// Load the text helper so we can highlight the SQL
		$this->CI->load->helper('text');

		// Key words we want bolded
		$highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR&nbsp;', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');

		$output  = "\n\n";

		$count = 0;

        $output  = '<span class="profiler-section profiler-show-data" data-id="profiler-queries">Запросов: '. count($dbs[0]->queries);
        $output .= '<div class="profiler-data" id="profiler-queries" style="overflow: scroll;">';
		foreach ($dbs as $db)
		{
			$count++;

			$hide_queries = (count($db->queries) > $this->_query_toggle_count) ? ' display:none' : '';

			$show_hide_js = '(<span style="cursor: pointer;" onclick="var s=document.getElementById(\'ci_profiler_queries_db_'.$count.'\').style;s.display=s.display==\'none\'?\'\':\'none\';this.innerHTML=this.innerHTML==\''.$this->CI->lang->line('profiler_section_hide').'\'?\''.$this->CI->lang->line('profiler_section_show').'\':\''.$this->CI->lang->line('profiler_section_hide').'\';">'.$this->CI->lang->line('profiler_section_hide').'</span>)';

			if ($hide_queries != '')
			{
				$show_hide_js = '(<span style="cursor: pointer;" onclick="var s=document.getElementById(\'ci_profiler_queries_db_'.$count.'\').style;s.display=s.display==\'none\'?\'\':\'none\';this.innerHTML=this.innerHTML==\''.$this->CI->lang->line('profiler_section_show').'\'?\''.$this->CI->lang->line('profiler_section_hide').'\':\''.$this->CI->lang->line('profiler_section_show').'\';">'.$this->CI->lang->line('profiler_section_show').'</span>)';
			}

			$output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
			$output .= "\n";
			$output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_database').':&nbsp; '.$db->database.'&nbsp;&nbsp;&nbsp;'.$this->CI->lang->line('profiler_queries').': '.count($db->queries).'&nbsp;&nbsp;'.$show_hide_js.'</legend>';
			$output .= "\n";
			$output .= "\n\n<table style='width:100%;' id='ci_profiler_queries_db_{$count}'>\n";

			if (count($db->queries) == 0)
			{
				$output .= "<tr><td style='width:100%;color:#0000FF;font-weight:normal;background-color:#eee;padding:5px;'>".$this->CI->lang->line('profiler_no_queries')."</td></tr>\n";
			}
			else
			{
				foreach ($db->queries as $key => $val)
				{
					$time = number_format($db->query_times[$key], 4);

					$val = highlight_code($val, ENT_QUOTES);

					foreach ($highlight as $bold)
					{
						$val = str_replace($bold, '<strong>'.$bold.'</strong>', $val);
					}

					$output .= "<tr><td style='padding:5px; vertical-align: top;width:1%;color:#900;font-weight:normal;background-color:#ddd;'>".$time."&nbsp;&nbsp;</td><td style='padding:5px; color:#000;font-weight:normal;background-color:#ddd;'>".$val."</td></tr>\n";
				}
			}

			$output .= "</table>\n";
			$output .= "</fieldset>";

		}
        $output .= '</div>';
        $output .= '</span>';

		return $output;
	}
                                        
    // --------------------------------------------------------------------

	/**
	 * Show query string
	 *
	 * @return	string
	 */
	protected function _compile_uri_string()
	{
		$output  = '<span class="profiler-section">';

		if ($this->CI->uri->uri_string == '')
		{
			$output .= '<span>'.$this->CI->lang->line('profiler_no_uri').'</span>';
		}
		else
		{
			$output .= '<span>'.$this->CI->uri->uri_string.'</span>';
		}

		$output .= '</span>';

		return $output;
	}
    
    /**
	 * Run the Profiler
	 *
	 * @return	string
	 */
	function run()
	{
		$output = '<div class="ci-profiler"><div class="profiler-inner">';
		$fields_displayed = 0;

		foreach ($this->_available_sections as $section)
		{
			if ($this->_compile_{$section} !== FALSE)
			{
				$func = "_compile_{$section}";
				$output .= $this->{$func}();
				$fields_displayed++;
			}
		}

		if ($fields_displayed == 0)
		{
			$output .= '<p>Нет данных</p>';
		}

		$output .= '</div></div>';

		return $output;
	}
    
    // --------------------------------------------------------------------

	/**
	 * Auto Profiler
	 *
	 * This function cycles through the entire array of mark points and
	 * matches any two points that are named identically (ending in "_start"
	 * and "_end" respectively).  It then compiles the execution times for
	 * all points and returns it as an array
	 *
	 * @return	array
	 */
	protected function _compile_benchmarks()
	{
		$profile = array();
		foreach ($this->CI->benchmark->marker as $key => $val)
		{
			// We match the "end" marker so that the list ends
			// up in the order that it was defined
			if (preg_match("/(.+?)_end/i", $key, $match))
			{
				if (isset($this->CI->benchmark->marker[$match[1].'_end']) AND isset($this->CI->benchmark->marker[$match[1].'_start']))
				{
					$profile[$match[1]] = $this->CI->benchmark->elapsed_time($match[1].'_start', $key);
				}
			}
		}

		// Build a table containing the profile data.
		// Note: At some point we should turn this into a template that can
		// be modified.  We also might want to make this data available to be logged

		$output  = '<span class="profiler-benchmarks profiler-section">Time: ';
        $profile = array_reverse($profile);
        $i=0;
		foreach ($profile as $key => $val)
		{
			$key = ucwords(str_replace(array('_', '-'), ' ', $key));
            if( $i == 0 )
            {
                $output .= '<strong title="'. $key .'">'.$val.'</strong>&nbsp;(';
            }
            else
            {
                $output .= '<small title="'. $key .'">'.$val.'</small>' . ($i == 1 ? '/' : '');
            }
            $i++;
		}
        $output .= ')</span>';

		return $output;
	}
    
    // --------------------------------------------------------------------

	/**
	 * Show the controller and function that were called
	 *
	 * @return	string
	 */
	protected function _compile_controller_info()
	{
		$output  = '<span class="profiler-controller-info profiler-section">';
		$output .= "<span>".$this->CI->router->fetch_class()."/".$this->CI->router->fetch_method()."</span>";
		$output .= '</span>';

		return $output;
	}
    
    /**
	 * Compile memory usage
	 *
	 * Display total used memory
	 *
	 * @return	string
	 */
	protected function _compile_memory_usage()
	{
		$output = '<span class="profiler-memory-usage profiler-section">Memory: ';

		if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
		{
			$output .= "<span><strong>".(number_format($usage/1024)).'</strong> Kb</span>';
		}
		else
		{
			$output .= "<span>Нет данных</span>";
		}

		$output .= '</span>';

		return $output;
	}
}