<?php namespace ErnestWisniewski\Billsafe\Logger;

class LoggerNull implements Logger
{
    public function log($message)
    {
        //do nothing!
    }
}