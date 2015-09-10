<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__)
    ->exclude('App/cache')
    ->exclude('App/config')
;

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->finder($finder)
;
