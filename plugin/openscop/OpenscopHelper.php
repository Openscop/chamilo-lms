<?php


class OpenscopHelper
{
    public static function debug($data, $die = false){
        print('<pre>'.print_r($data, true).'</pre>');
        if($die){
            die;
        }
    }
}
