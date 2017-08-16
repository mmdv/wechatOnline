<?php
    namespace app\index\controller;
//    echo .DIRECTORY_SEPARATOR;
//
    class Path
    {
        public function path()
        {
            echo "path:".dirname(__FILE__).'<br>';
            echo __DIR__."<BR>";
            echo "<img src='".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."qrcode".DIRECTORY_SEPARATOR."00.jpg"."'>";
            echo "akali";
        }
    }