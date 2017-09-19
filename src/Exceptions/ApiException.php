<?php

namespace Tebian\Exceptions;

class ApiException extends \Exception
{
	public function getName()
    {
        return 'ApiException';
    }
}

?>
