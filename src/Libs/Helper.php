<?php

namespace UberCrawler\Libs;

class Helper
{
    /**
    * [$type description].
    *
    * @var string
    */
    public static function printOut($message, $type = 'INFO')
    {
        echo "{$type}::: {$message} \n";
    }

    /**
    * [makedirs description].
    *
    * @param [type]  $dirpath [description]
    * @param int $mode    [description]
    *
    * @return [type]  [description]
    */
    public static function makedirs($dirpath,
                                  $mode = 0755)
    {
        return is_dir($dirpath) || mkdir($dirpath, $mode, true);
    }
}
