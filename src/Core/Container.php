<?php

namespace App\Core;

class Container
{
    private array $bind = [];

    public function bind(string $abstract, callable $factory): void
    {
        $this->bind[$abstract] = $factory;
    }

    public function make(string $abstract): mixed
    {
        if(!isset($this->bind[$abstract])){
            throw new \Exception("{$abstract} is not bound");
        }
        return ($this->bind[$abstract])($this);
    }
}