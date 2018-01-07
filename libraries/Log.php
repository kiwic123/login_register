<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Log {
    function error($page, $msg){        
        $logger = new Logger($page);
        $logger->pushHandler(new StreamHandler('log/error.log', Logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());
        $logger->error(Ip::get().': '.$msg);
    }

    function info($page, $msg){ 
        $logger = new Logger($page);
        $logger->pushHandler(new StreamHandler('log/info.log', Logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());
        $logger->info(Ip::get().': '.$msg);
    }

    function warning($page, $msg){
        $logger = new Logger($page);
        $logger->pushHandler(new StreamHandler('log/warning.log', Logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());
        $logger->warning(Ip::get().': '.$msg);
    }
}