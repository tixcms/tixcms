<?php

namespace Users;

class Gravatar
{
    const BASE_URL = 'http://www.gravatar.com/avatar/';

    static function get($email, $size = 80, $imageset = 'mm', $rating = 'g', $return_img = false, $atts = array())
    {
        $url = self::BASE_URL;
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$size&d=$imageset&r=$rating";

        if ( $return_img )
        {
            $url = '<img src="' . $url . '"';

            foreach ( $atts as $key => $val )
            {
                $url .= ' ' . $key . '="' . $val . '"';
            }

            $url .= ' />';
        }

        return $url;
    }
}