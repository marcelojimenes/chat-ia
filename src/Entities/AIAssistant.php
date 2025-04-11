<?php

namespace App\Entities;

final class AIAssistant {
    public function __construct(
        private readonly string $name,
        private readonly string $model,
        private readonly string $temperature = '0.7',
        private readonly int $maxTokens = 2048
    ) {
    }

    public function getName(): string {
        return $this->name;
    }

    public function getModel(): string {
        return $this->model;
    }

    public function getTemperature(): string {
        return $this->temperature;
    }

    public function getMaxTokens(): int {
        return $this->maxTokens;
    }
}