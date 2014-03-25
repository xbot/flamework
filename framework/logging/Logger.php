<?php
namespace org\x3f\flamework\logging;

/**
 * Abstract logger
 *
 * @abstract
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
abstract class Logger {
    const LEVEL_LOG = 1;
    const LEVEL_DEBUG = 2;
    const LEVEL_ERROR = 3;

    /**
     * @var int Logging level
     */
    private $_level = self::LEVEL_LOG;

    /**
     * Basic logging method
     * @param string $msg
     * @param int $level
     * @return void
     * @since 1.0
     */
    abstract public function log($msg, $level=self::LEVEL_LOG);

    /**
     * Set logging level
     * @param const Logging level
     * @return void
     * @since 1.0
     */
    public function setLevel($level)
    {
        $this->_level = $level;
    }

    /**
     * Return logging level
     * @return const Logging level
     * @since 1.0
     */
    public function getLevel()
    {
        return $this->_level;
    }
    
    /**
     * Log message on debug level
     * @param string $msg
     * @return void
     * @since 1.0
     */
    public function debug($msg) {
        $this->log($msg, self::LEVEL_DEBUG);
    }

    /**
     * Log message on error level
     * @param string $msg
     * @return void
     * @since 1.0
     */
    public function error($msg) {
        $this->log($msg, self::LEVEL_ERROR);
    }

    /**
     * Get label of the given level
     * @param int $level Debug level value
     * @return string Debug level label
     * @since 1.0
     */
    public static function getLabelByLevel($level)
    {
        switch($level) {
            case self::LEVEL_LOG:
                return 'LOG';
            case self::LEVEL_DEBUG:
                return 'DEBUG';
            case self::LEVEL_ERROR:
                return 'ERROR';
            default:
                return 'UNKNOWN';
        }
    }
    
    /**
     * Get log level by label
     * @param string $label Log label
     * @return int Log level
     * @since 1.0
     */
    public static function getLevelByLabel($label)
    {
        switch(strtoupper($label)) {
            case 'DEBUG':
                return self::LEVEL_DEBUG;
            case 'ERROR':
                return self::LEVEL_ERROR;
            case 'LOG':
            default:
                return self::LEVEL_LOG;
        }
    }
    
}
?>
