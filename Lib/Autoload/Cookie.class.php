<?php
class Cookie{
    static function cookieget($k, $default='') {
        $root_domain = D("Options")->getOption("root_domain");
        $pre = substr(md5($root_domain),0,4);
        $k = "{$pre}_{$k}";
        return isset($_COOKIE[$k]) ? strval($_COOKIE[$k]) : $default;
    }

    static function cookieset($k, $v, $expire=0) {
        $root_domain = D("Options")->getOption("root_domain");
        $pre = substr(md5($root_domain),0,4);
        $k = "{$pre}_{$k}";
        if ($expire==0) {
        $expire = time() + 365 * 86400;
        } else {
        $expire += time();
        }
        setCookie($k, $v, $expire, '/', self::get_cookie_domain());
    }

    function get_cookie_domain(){
        return "." . D("Options")->getOption("root_domain");
    }
}