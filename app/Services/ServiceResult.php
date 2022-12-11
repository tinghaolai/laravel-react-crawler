<?php

namespace App\Services;

class ServiceResult
{
    protected bool $result = false;
    protected array $data;

    public static function successResult(array $data = []): ServiceResult
    {
        $result = new self();
        $result->setSuccess()
            ->setData($data);

        return $result;
    }

    public static function failResult(array $data = []): ServiceResult
    {
        $result = new self();
        $result->setFail()
            ->setData($data);

        return $result;
    }

    public function success(): bool
    {
        return $this->result;
    }

    public function fail(): bool
    {
        return !$this->result;
    }

    public function setSuccess(): static
    {
        $this->result = true;

        return $this;
    }

    public function setFail(): static
    {
        $this->result = false;

        return $this;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function data(): array
    {
        return $this->data;
    }
}
