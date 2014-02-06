<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter URL Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/url_helper.html
 */

// ------------------------------------------------------------------------

class URL {

    /**
     * Site URL
     *
     * Create a local URL based on your basepath. Segments can be passed via the
     * first parameter either as a string or an array.
     *
     * @access	public
     * @param	string
     * @return	string
     */
	public static function site_url($uri = '', $show_suffix = true)
	{
		$CI =& get_instance();
	    if (is_array($uri))
		{
			$uri = implode('/', $uri);
		}
        
        if (strpos($uri, 'http://') === 0) 
        {
            return $uri;    
        }

		if ($uri == '')
		{
			return $CI->config->item('base_url').$CI->config->item('index_page').$CI->config->item('url_suffix');
		}
		else
		{
			$suffix = ($CI->config->item('url_suffix') == false OR $show_suffix == false) ? '' : $CI->config->item('url_suffix');
            
            $uri = (ENVIRONMENT == 'development' AND strpos($uri, '.') !== false) ? '../' . $uri : $uri;
            
			return $CI->config->slash_item('base_url').$CI->config->slash_item('index_page').trim($uri, '/') . $suffix;
		}
	}

    /**
     * Base URL
     *
     * Returns the "base_url" item from your config file
     *
     * @access	public
     * @return	string
     */
	public static function base_url()
	{
		$CI =& get_instance();
		return $CI->config->slash_item('base_url');
	}

    /**
     * Current URL
     *
     * Returns the full URL (including segments) of the page where this
     * public static function is placed
     *
     * @access	public
     * @return	string
     */
	public static function current_url()
	{
		$CI =& get_instance();
		return $CI->config->site_url($CI->uri->uri_string());
	}

    /**
     * URL String
     *
     * Returns the URI segments.
     *
     * @access	public
     * @return	string
     */
	public static function uri_string()
	{
		$CI =& get_instance();
		return $CI->uri->uri_string();
	}

    /**
     * Index page
     *
     * Returns the "index_page" from your config file
     *
     * @access	public
     * @return	string
     */
	public static function index_page()
	{
		$CI =& get_instance();
		return $CI->config->item('index_page');
	}

    /**
     * Anchor Link
     *
     * Creates an anchor based on the local URL.
     *
     * @access	public
     * @param	string	the URL
     * @param	string	the link title
     * @param	mixed	any attributes
     * @return	string
     */
	static function anchor($uri = '', $title = '', $attributes = '', $extra = '')
	{
		$title = (string) $title;

        $CI =& get_instance();

		if ( ! is_array($uri))
		{
			$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? $CI->config->site_url($uri) : $uri;
		}
		else
		{
			$site_url = $CI->config->site_url($uri);
		}

		if ($title == '')
		{
			$title = $site_url;
		}

		if ($attributes != '')
		{
			$attributes = self::_parse_attributes($attributes);
		}

		return '<a href="'.$site_url.$extra.'"'.$attributes.'>'.$title.'</a>';
	}
    
    static function anchor_protected($uri = '', $title = '', $attributes = '', $extra = '')
    {
        $extra .= (strpos($extra, '?') === false ? '?' : '&') .'csrf_token='. \Security::csrf_generate_hash(); 
        
        return self::anchor($uri, $title, $attributes, $extra);
    }

    /**
     * Anchor Link - Pop-up version
     *
     * Creates an anchor based on the local URL. The link
     * opens a new window based on the attributes specified.
     *
     * @access	public
     * @param	string	the URL
     * @param	string	the link title
     * @param	mixed	any attributes
     * @return	string
     */
	public static function anchor_popup($uri = '', $title = '', $attributes = FALSE)
	{
		$title = (string) $title;

		$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? URL::site_url($uri) : $uri;

		if ($title == '')
		{
			$title = $site_url;
		}

		if ($attributes === FALSE)
		{
			return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank');\">".$title."</a>";
		}

		if ( ! is_array($attributes))
		{
			$attributes = array();
		}

		foreach (array('width' => '800', 'height' => '600', 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0', ) as $key => $val)
		{
			$atts[$key] = ( ! isset($attributes[$key])) ? $val : $attributes[$key];
			unset($attributes[$key]);
		}

		if ($attributes != '')
		{
			$attributes = URL::_parse_attributes($attributes);
		}

		return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank', '"._parse_attributes($atts, TRUE)."');\"$attributes>".$title."</a>";
	}

    /**
     * Mailto Link
     *
     * @access	public
     * @param	string	the email address
     * @param	string	the link title
     * @param	mixed 	any attributes
     * @return	string
     */
	public static function mailto($email, $title = '', $attributes = '')
	{
		$title = (string) $title;

		if ($title == "")
		{
			$title = $email;
		}

		$attributes = URL::_parse_attributes($attributes);

		return '<a href="mailto:'.$email.'"'.$attributes.'>'.$title.'</a>';
	}

    /**
     * Encoded Mailto Link
     *
     * Create a spam-protected mailto link written in Javascript
     *
     * @access	public
     * @param	string	the email address
     * @param	string	the link title
     * @param	mixed 	any attributes
     * @return	string
     */
	public static function safe_mailto($email, $title = '', $attributes = '')
	{
		$title = (string) $title;

		if ($title == "")
		{
			$title = $email;
		}

		for ($i = 0; $i < 16; $i++)
		{
			$x[] = substr('<a href="mailto:', $i, 1);
		}

		for ($i = 0; $i < strlen($email); $i++)
		{
			$x[] = "|".ord(substr($email, $i, 1));
		}

		$x[] = '"';

		if ($attributes != '')
		{
			if (is_array($attributes))
			{
				foreach ($attributes as $key => $val)
				{
					$x[] =  ' '.$key.'="';
					for ($i = 0; $i < strlen($val); $i++)
					{
						$x[] = "|".ord(substr($val, $i, 1));
					}
					$x[] = '"';
				}
			}
			else
			{
				for ($i = 0; $i < strlen($attributes); $i++)
				{
					$x[] = substr($attributes, $i, 1);
				}
			}
		}

		$x[] = '>';

		$temp = array();
		for ($i = 0; $i < strlen($title); $i++)
		{
			$ordinal = ord($title[$i]);

			if ($ordinal < 128)
			{
				$x[] = "|".$ordinal;
			}
			else
			{
				if (count($temp) == 0)
				{
					$count = ($ordinal < 224) ? 2 : 3;
				}

				$temp[] = $ordinal;
				if (count($temp) == $count)
				{
					$number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);
					$x[] = "|".$number;
					$count = 1;
					$temp = array();
				}
			}
		}

		$x[] = '<'; $x[] = '/'; $x[] = 'a'; $x[] = '>';

		$x = array_reverse($x);
		ob_start();

	?><script type="text/javascript">
	//<![CDATA[
	var l=new Array();
	<?php
	$i = 0;
	foreach ($x as $val){ ?>l[<?php echo $i++; ?>]='<?php echo $val; ?>';<?php } ?>

	for (var i = l.length-1; i >= 0; i=i-1){
	if (l[i].substring(0, 1) == '|') document.write("&#"+unescape(l[i].substring(1))+";");
	else document.write(unescape(l[i]));}
	//]]>
	</script><?php

		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

    /**
     * Auto-linker
     *
     * Automatically links URL and Email addresses.
     * Note: There's a bit of extra code here to deal with
     * URLs or emails that end in a period.  We'll strip these
     * off and add them after the link.
     *
     * @access	public
     * @param	string	the string
     * @param	string	the type: email, url, or both
     * @param	bool 	whether to create pop-up links
     * @return	string
     */
	public static function auto_link($str, $type = 'both', $popup = FALSE)
	{
		if ($type != 'email')
		{
			if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches))
			{
				$pop = ($popup == TRUE) ? " target=\"_blank\" " : "";

				for ($i = 0; $i < count($matches['0']); $i++)
				{
					$period = '';
					if (preg_match("|\.$|", $matches['6'][$i]))
					{
						$period = '.';
						$matches['6'][$i] = substr($matches['6'][$i], 0, -1);
					}

					$str = str_replace($matches['0'][$i],
										$matches['1'][$i].'<a href="http'.
										$matches['4'][$i].'://'.
										$matches['5'][$i].
										$matches['6'][$i].'"'.$pop.'>http'.
										$matches['4'][$i].'://'.
										$matches['5'][$i].
										$matches['6'][$i].'</a>'.
										$period, $str);
				}
			}
		}

		if ($type != 'url')
		{
			if (preg_match_all("/([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches))
			{
				for ($i = 0; $i < count($matches['0']); $i++)
				{
					$period = '';
					if (preg_match("|\.$|", $matches['3'][$i]))
					{
						$period = '.';
						$matches['3'][$i] = substr($matches['3'][$i], 0, -1);
					}

					$str = str_replace($matches['0'][$i], URL::safe_mailto($matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i]).$period, $str);
				}
			}
		}

		return $str;
	}

    /**
     * Prep URL
     *
     * Simply adds the http:// part if missing
     *
     * @access	public
     * @param	string	the URL
     * @return	string
     */
	public static function prep_url($str = '')
	{
		if ($str == 'http://' OR $str == '')
		{
			return '';
		}

		if (substr($str, 0, 7) != 'http://' && substr($str, 0, 8) != 'https://')
		{
			$str = 'http://'.$str;
		}

		return $str;
	}

    /**
     * Create URL Title
     *
     * Takes a "title" string as input and creates a
     * human-friendly URL string with either a dash
     * or an underscore as the word separator.
     *
     * @access	public
     * @param	string	the string
     * @param	string	the separator: dash, or underscore
     * @return	string
     */
	public static function url_title($str, $separator = 'dash', $lowercase = FALSE)
	{
		if ($separator == 'dash')
		{
			$search		= '_';
			$replace	= '-';
		}
		else
		{
			$search		= '-';
			$replace	= '_';
		}

		$trans = array(
						'&\#\d+?;'				=> '',
						'&\S+?;'				=> '',
						'\s+'					=> $replace,
						'[^a-z0-9\-\._]'		=> '',
						$replace.'+'			=> $replace,
						$replace.'$'			=> $replace,
						'^'.$replace			=> $replace,
						'\.+$'					=> ''
					  );

		$str = strip_tags($str);

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = strtolower($str);
		}

		return trim(stripslashes($str));
	}

    /**
     * Header Redirect
     *
     * Header redirect in two flavors
     * For very fine grained control over headers, you could use the Output
     * Library's set_header() public static function.
     *
     * @access	public
     * @param	string	the URL
     * @param	string	the method: location or redirect
     * @return	string
     */
	public static function redirect($uri = '', $extra = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
		    $CI =& get_instance();
            
            $uri .= $uri != '' ? $CI->config->item('url_suffix').$extra : '';
			$uri = self::site_url($uri);
		}

		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}

    public static function referer()
    {
        self::redirect($_SERVER['HTTP_REFERER']);
    }

    public static function refresh()
    {
        self::redirect($_SERVER['HTTP_REFERER'], '', 'refresh');
    }

    /**
     * Parse out the attributes
     *
     * Some of the public static functions use this
     *
     * @access	private
     * @param	array
     * @param	bool
     * @return	string
     */
	public static function _parse_attributes($attributes, $javascript = FALSE)
	{
		if (is_string($attributes))
		{
			return ($attributes != '') ? ' '.$attributes : '';
		}

		$att = '';
		foreach ($attributes as $key => $val)
		{
			if ($javascript == TRUE)
			{
				$att .= $key . '=' . $val . ',';
			}
			else
			{
				$att .= ' ' . $key . '="' . $val . '"';
			}
		}

		if ($javascript == TRUE AND $att != '')
		{
			$att = substr($att, 0, -1);
		}

		return $att;
	}
}

/* End of file URL.php */
/* Location: ./system/libraries/URL.php */