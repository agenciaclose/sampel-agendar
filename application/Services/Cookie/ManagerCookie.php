<?php

namespace Agencia\Close\Services\Cookie;

class ManagerCookie
{

    public static function getOrderProduct(): string
    {
        $column = (!empty($_COOKIE['CookieOrderByColumn'])) ? $_COOKIE['CookieOrderByColumn'] : 'data';
        $orderBy = (!empty($_COOKIE['CookieOrderBy'])) ? $_COOKIE['CookieOrderBy'] : 'DESC';
        return 'ORDER BY '.$column.' '.$orderBy;
    }
}