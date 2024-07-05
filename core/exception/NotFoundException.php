<?php

namespace app\core\exception;

use http\Message;

class NotFoundException extends \Exception
{
    protected $message = 'Page not found';
    protected $code = 404;
}