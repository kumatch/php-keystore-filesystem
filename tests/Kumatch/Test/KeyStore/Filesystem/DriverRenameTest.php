<?php

namespace Kumatch\Test\KeyStore\Filesystem;

class DriverRenameTest extends DriverCopyTest
{
    protected $methodName = "rename";
    protected $isExistsAfterInvoke = false;

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}