<?php

namespace Stolt\Tjson\Exceptions;

use \Exception;
use Stolt\Tjson\Exceptions\TjsonException;

class NonUtf8Content extends Exception implements TjsonException
{
}
