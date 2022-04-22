<?php

namespace Xerophy\Framework\Http;


class Request
{

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function fieldContent(string $fieldName): string|array|null
    {
        return $_POST[$fieldName] ?? null;
    }

    public function content(): ?array
    {
        return $_POST ?? null;
    }

    public function getUrl()
    {
        
    }

    public function getUrlWithQuery()
    {
        
    }

    public function getFullUrlWithQuery()
    {
        
    }

    public function getFullUrl()
    {
        
    }

    public function getContentType(): ?string
    {
        return $_SERVER['CONTENT_TYPE'] ?? null;
    }

    public function getRequestTime(): ?string
    {
        return $_SERVER['REQUEST_TIME'] ?? null;
    }

    public function getRequestScheme(): ?string
    {
        return $_SERVER['REQUEST_SCHEME'] ?? null;
    }

    public function getHttpHost(): ?string
    {
        return $_SERVER['HTTP_HOST'] ?? null;
    }

    public function getScriptName(): ?string
    {
        return $_SERVER['SCRIPT_NAME'] ?? null;
    }
}