<?php
namespace org\x3f\flamework\exceptions;

/**
 * Http exception
 *
 * @see Exception
 *
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
class HttpException extends FlameException
{
    public function __construct($code, $message='')
    {
        parent::__construct($message, $code);
    }
}

?>
