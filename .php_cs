<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__)
    ;
return Symfony\CS\Config\Config::create()
    ->fixers(
        Symfony\CS\FixerInterface::ALL_LEVEL
    )
    ->finder($finder)
    ;
