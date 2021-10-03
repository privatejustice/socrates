<?php

namespace Socrates\Services\Socrates;

class Check extends \Socrates\Resources\Check
{

    public function getResultAsIcon(): string
    {
        return $this->isFailed() ? "🔴" : "✅";
    }

    public function getTypeAsTitle(): string
    {
        $result = str_replace('_', ' ', $this->type);

        return ucwords($result);
    }

    private function isSuccess(): bool
    {
        return $this->latestRunResult === 'succeeded';
    }

    private function isFailed(): bool
    {
        return $this->latestRunResult === 'failed';
    }
}