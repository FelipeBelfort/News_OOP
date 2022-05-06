<?php

class DBFactory
{
    public static function getPDOConnexion()
    {
        $db = new PDO('mysql:host=localhost; dbname=db_news', 'root','root');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }
}
