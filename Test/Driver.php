<?php

namespace Test;

class Driver
{
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }
}
