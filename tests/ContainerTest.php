<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Venjoy\Pedy\Container;

class ContainerTest extends TestCase
{
    public function testContainer()
    {
        $container = new Container;
        $job = $container->get(__NAMESPACE__.'\\Job');
        
        $this->assertTrue($job->user->name == 'someone');
    }
}


class Job 
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}

class User 
{
    public $name = 'someone';
}
