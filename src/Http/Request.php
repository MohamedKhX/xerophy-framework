<?php

namespace Xerophy\Framework\Http;
use Symfony\Component\HttpFoundation\Request as symfonyRequest;

class Request extends symfonyRequest
{
    public function getIp(): ?string
    {
        return $this->getClientIp();
    }

    public function Url(): string
    {
        return $this->getBaseUrl();
    }
}