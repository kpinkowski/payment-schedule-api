<?php

declare(strict_types=1);

namespace App\Tests\Common\TestCase;

use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

abstract class UnitTestCase extends TestCase
{
    protected function setUp(): void
    {
        date_default_timezone_set('UTC');
        BypassFinals::enable();
        parent::setUp();
    }
}
