<?php

namespace Aliemam\Tebian\Exceptions;

class ApiException extends \Exception
{
	public function getName()
    {
        return 'ApiException';
    }
}

?>
