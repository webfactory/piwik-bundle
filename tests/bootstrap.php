<?php

use Symfony\Component\ErrorHandler\DebugClassLoader;
use Symfony\Component\ErrorHandler\ErrorHandler;

require_once __DIR__.'/../vendor/autoload.php';

// Work around https://github.com/symfony/symfony/issues/53812 for the time being (issue starting with Symfony 6.4 or 7.1 and PHPUnit 11 or 12?)
set_exception_handler([new ErrorHandler(), 'handleException']);

// Use DebugClassLoader to catch certain deprecations that can only be found through source code analysis
DebugClassLoader::enable();
