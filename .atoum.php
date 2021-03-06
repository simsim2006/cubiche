<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once __DIR__.DIRECTORY_SEPARATOR.'.bootstrap.atoum.php';

use mageekguy\atoum\visibility\extension as Extension;

/* @var \mageekguy\atoum\configurator $script */
$script->excludeDirectoriesFromCoverage(array(__DIR__.'/vendor'));

/* @var \mageekguy\atoum\runner $runner */
$runner->addTestsFromPattern(__DIR__.'/src/**/**/**/Tests/');
$runner->addExtension(new Extension($script));
