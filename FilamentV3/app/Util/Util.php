<?php

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Facade;

class Util extends Facade
{
    public function teste(): string
    {
        return 'toto';
    }

}