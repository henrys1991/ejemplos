<?php

function convertir_charset_mix($input, $charset_to, $charset_from, $encode_keys=false){

        if(is_array($input))
        {
            $result = array();
            foreach($input as $k => $v)
            {
                $key = ($encode_keys)? mb_convert_encoding($k, $charset_to, $charset_from) : $k;
                $result[$key] = convertir_charset_mix( $v, $charset_to, $charset_from, $encode_keys);
            }
        }
        else
        {
            $result = mb_convert_encoding($input, $charset_to, $charset_from);
        }

        return $result;
}

function utf8_encode_mix($input, $encode_keys=false)
{
        if(is_array($input))
        {
            $result = array();
            foreach($input as $k => $v)
            {
                $key = ($encode_keys)? utf8_encode($k) : $k;
                $result[$key] = utf8_encode_mix( $v, $encode_keys);
            }
        }
        else
        {
            $result = utf8_encode($input);
        }

        return $result;
}

function utf8_decode_mix($input, $decode_keys=false)
{
        if(is_array($input))
        {
            $result = array();
            foreach($input as $k => $v)
            {
                $key = ($decode_keys)? utf8_decode($k) : $k;
                $result[$key] = utf8_decode_mix( $v, $decode_keys);
            }
        }
        else
        {
            $result = utf8_decode($input);
        }

        return $result;
}
?>