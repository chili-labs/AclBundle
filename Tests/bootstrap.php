<?php

use Symfony\Bundle\FrameworkBundle\Command\CacheWarmupCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/App/AppKernel.php';

$kernel = new AppKernel('test', true);
$kernel->boot();

$application = new Application($kernel);

$command = new CacheWarmupCommand();
$application->add($command);
$input = new ArrayInput(array(
    'command' => 'cache:warmup',
    '--env'   => 'test',
));
$command->run($input, new NullOutput());
