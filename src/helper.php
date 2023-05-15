<?php

class helper
{
    public static function GenerateUNIQUE($data)
    {
        $result = date("d/m/Y-H:i:s") . $data;
        return md5($result);
    }
}