<?php

namespace Source\Event\WebHook\Command;

class BashPrint{
    
    /**
     *
     * @var string
     */
    private static string $output = "";
    /**
     *
     * @var integer
     */
    private static int $success = 0;
    /**
     *
     * @var integer
     */
    private static int $fails = 0;
    /**
     *
     * @var boolean
     */
    private static bool $initialized = false;

    public static function initialize() : void
    {
        if(!self::$initialized){
            self::$initialized = true;
        }
    }

    public static function isInitialized() : bool
    {
        return self::$initialized;
    }

    public static function addPrint(string $message) : void
    {
        self::$output .= $message;
    }

    public static function printOutput() : void
    {   
        echo  "\n ...webhook report: \n";
        $string = "\033[32m seccess: \033[0m%s\n\033[31m fails: \033[m%s\033[m";
        printf($string, self::$success, self::$fails);
    }

    public static function addSuccess() : void
    {
       self::$success++;
    }

    public static function addFail() : void
    {
       self::$fails++;
    }

    public static function reset() : void 
    {
        self::$initialized = false;
        self::$output = "";
        self::$success = 0;
        self::$fails = 0;
        
    }
}