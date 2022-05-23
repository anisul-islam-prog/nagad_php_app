<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('maybe_serialize'))
{
    /**
     * Serialize data, if needed.
     *
     * @param mixed $data Data that might be serialized.
     * @return mixed A scalar data
     */
    function maybe_serialize( $data ) {
        if ( is_array( $data ) || is_object( $data ) ) {
            return serialize( $data );
        } else {
            return $data;
        }
    }
}

if ( ! function_exists('maybe_unserialize'))
{
    /**
     * Unserialize value only if it was serialized.
     *
     * @param string $original Maybe unserialized original, if is needed.
     * @return mixed Unserialized data can be any type.
     */
    function maybe_unserialize( $original ) {
        if ( is_serialized( $original ) ) {
            return @unserialize( $original );
        }
        return $original;
    }
}


if ( ! function_exists('is_serialized'))
{
    /**
     * Check value to find if it was serialized.
     *
     * If $data is not an string, then returned value will always be false.
     * Serialized data is always a string.
     *
     * @param mixed $data Value to check to see if was serialized.
     * @return bool False if not serialized and true if it was.
     */
    function is_serialized( $data ) {
        // if it isn't a string, it isn't serialized
        if ( ! is_string( $data ) )
            return false;
        $data = trim( $data );
        if ( 'N;' == $data )
            return true;
        $length = strlen( $data );
        if ( $length < 4 )
            return false;
        if ( ':' !== $data[1] )
            return false;
        $lastc = $data[$length-1];
        if ( ';' !== $lastc && '}' !== $lastc )
            return false;
        $token = $data[0];
        switch ( $token ) {
            case 's' :
                if ( '"' !== $data[$length-2] )
                    return false;
            case 'a' :
            case 'O' :
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b' :
            case 'i' :
            case 'd' :
                return (bool) preg_match( "/^{$token}:[0-9.E-]+;\$/", $data );
        }
        return false;
    }
}

/* End of file serialize_helper.php */
/* Location: ./application/helpers/serialize_helper.php */