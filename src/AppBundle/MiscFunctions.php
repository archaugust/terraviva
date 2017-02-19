<?php
namespace AppBundle;

class MiscFunctions {
    public function slug($str)
    {
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', "-", $str);
        if ($str == '-')
            $str = time();
        return $str;
    }

    public function unslug($str)
    {
        $str = str_replace('-', ' ', $str);
        $str = ucwords(trim($str));
        return $str;
    }
}