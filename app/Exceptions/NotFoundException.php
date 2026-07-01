<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when a route or resource does not exist. The global ErrorHandler
 * renders it as a 404 (negotiated HTML/JSON) instead of a 500.
 */
class NotFoundException extends RuntimeException
{
}
