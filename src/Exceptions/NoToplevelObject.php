<?php

namespace Stolt\Tjson\Exceptions;

use \Exception;
use Stolt\Tjson\Exceptions\TjsonException;

class NoToplevelObject extends Exception implements TjsonException
{
}
