<?php
namespace org\x3f\flamework\logging;
use org\x3f\flamework\Flame as Flame;

/**
 * File logger
 *
 * @see Logger
 *
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
class Filelogger extends Logger
{
    /**
     * @see Logger::log()
     */
    public function log($msg, $level=self::LEVEL_LOG)
    {
        if ($level < Flame::app()->getLogLevel())
            return;
        $content = date('Y-m-d H:i:s').' ['.self::getLabelByLevel($level).'] '.$msg."\n";
        file_put_contents(Flame::app()->getTempPath().DIRECTORY_SEPARATOR.'application.log', $content, FILE_APPEND);
    }
    
}

?>
