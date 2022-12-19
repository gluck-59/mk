<?php
class Profiler
{
    static $_lastTime;
    static $_sumTime;
    
    static function start() {
        self::$_lastTime = self::_getMicrotime();
        self::$_sumTime = 0;
    }

    static function showTime($message = '') {
        $curTime = self::_getMicrotime();        
        $time = bcsub($curTime, self::$_lastTime, 6);
        self::$_lastTime = self::_getMicrotime();
        self::$_sumTime += $time;

        echo ($message ? $message . ': ' : '') . $time . PHP_EOL;
    }

    static function stop($message = '') {
        self::showTime($message ? $message : 'Stop');
        echo 'Total time: ' . number_format(self::$_sumTime, 6) . PHP_EOL;
        self::$_lastTime = 0;
        self::$_sumTime = 0;
    }
    
    static function _getMicrotime() {
        list($usec, $sec) = explode(" ", microtime());
        $time = ((float) $usec + (float) $sec);

        return $time;
    }
}
?>