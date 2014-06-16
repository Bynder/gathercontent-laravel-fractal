<?php namespace GatherContent\LaravelFractal;

use Illuminate\Support\Facades\Facade;

class LaravelFractalFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'fractal';
    }
}
