<?php

namespace ProjectA\Bundle\AclBundle\Tests\Model;

class SomeObject
{
    private $id;
    private $foo;
    private $bar;

    private $secured;

    public function __construct($id, $foo = null, $bar = null)
    {
        $this->id = $id;
        $this->foo = $foo;
        $this->bar = $bar;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFoo($foo)
    {
        $this->foo = $foo;
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    public function getBar()
    {
        return $this->bar;
    }
}
