<?php

class ProductUtils
{

    static function imageExists($image) {
        return file_exists(dirname(__FILE__). "/..". $image) && is_readable(dirname(__FILE__). "/..". $image) && !empty($image);
    }

}