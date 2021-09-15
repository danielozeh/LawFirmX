<?php

namespace App;

class Helper {

    static function generateCode($length) {
        $code = substr(md5(uniqid() . "" . time()), -$length);
        return $code;
    }

    static function imagePath() {
        return env('IMG_PATH');
    }

}