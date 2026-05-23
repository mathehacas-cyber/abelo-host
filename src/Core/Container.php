<?php

namespace App\Core;

class Container
{
    private array $bind = [];

    /**
     * @param string $abstract
     * @param callable $factory
     * @return void
     */
    public function bind(string $abstract, callable $factory): void
    {
        $this->bind[$abstract] = $factory;
    }

    /**
     * @param string $abstract
     * @return mixed
     * @throws \Exception
     */
    public function make(string $abstract): mixed
    {
        if(!isset($this->bind[$abstract])){
            throw new \Exception("{$abstract} is not bound");
        }
        return ($this->bind[$abstract])($this);
    }
}