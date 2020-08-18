<?php

class Database
{

    public static $host = "localhost";
    public static $dbname = "covid-19";
    public static $username = "root";
    public static $password = "";

    public static function connectDB()
    {
        $conn = null;
        try {
            $conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8", self::$username, self::$password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             //echo "Connected successfully";
            }
        catch(PDOException $e)
            {
             //echo "Connection failed: " . $e->getMessage();
            } 

        return $conn;
    }


}
