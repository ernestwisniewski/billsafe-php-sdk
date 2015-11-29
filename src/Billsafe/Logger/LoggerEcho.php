<?php namespace ErnestWisniewski\Billsafe\Logger;

class LoggerEcho implements Logger
{
    public function log($message)
    {
        echo $message . "\r\n\r\n";
    }
}