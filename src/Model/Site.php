<?php

namespace Softspring\CmsBundle\Model;

class Site implements SiteInterface
{
    protected ?string $id;

    protected ?array $config = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return "{$this->getId()}";
    }

    public function getConfig(): ?array
    {
        return $this->config;
    }

    public function setConfig(?array $config): void
    {
        $this->config = $config;
    }

    public function getCanonicalHost(): ?string
    {
        foreach ($this->getConfig()['hosts'] as $hostConfig) {
            if ($hostConfig['canonical']) {
                return $hostConfig['domain'];
            }
        }

        return null;
    }

    public function getCanonicalScheme(): ?string
    {
        foreach ($this->getConfig()['hosts'] as $hostConfig) {
            if ($hostConfig['canonical'] && $hostConfig['scheme']) {
                return $hostConfig['scheme'];
            }
        }

        return null;
    }
}
