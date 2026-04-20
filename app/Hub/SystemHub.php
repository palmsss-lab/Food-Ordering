<?php

namespace App\Hub;

use Illuminate\Support\Facades\Log;

class SystemHub
{
    private array $spokes = [];

    public function registerSpoke(string $name, object $spoke): void
    {
        $this->spokes[$name] = $spoke;
    }

    public function spoke(string $name): ?object
    {
        return $this->spokes[$name] ?? null;
    }

    public function dispatch(object $event): void
    {
        Log::info('SystemHub: dispatching [' . class_basename($event) . ']');
        event($event);
    }

    public function registeredSpokes(): array
    {
        return array_keys($this->spokes);
    }
}
