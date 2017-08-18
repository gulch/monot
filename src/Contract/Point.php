<?php

namespace Monot\Contract;

interface Point
{
    public function getTarget(): string;
    public function check(): bool;
}