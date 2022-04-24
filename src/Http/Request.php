<?php

namespace Xerophy\Framework\Http;


class Request
{

    /**
     * Get the request method
     *
     * @return string
     * */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get the field content from the submitted form
     *
     * @param string $fieldName
     * @return string|array|null
     * */
    public function fieldContent(string $fieldName): string|array|null
    {
        return $_POST[$fieldName] ?? null;
    }

    /**
     * Get the content from the submitted form
     *
     * @return ?array
     * */
    public function content(): ?array
    {
        return $_POST ?? null;
    }

    /**
     * Get the root URL for the application
     *
     * @return string
     * */
    public function root(): string
    {
        return rtrim($this->getRequestScheme().$this->getHttpHost(), '/');
    }

    /**
     * Get url (without query string)
     *
     * @return string
     * */
    public function getUrl(): string
    {
        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
        return rtrim(preg_replace('/\?.*/', '', $uri), '/');
    }

    /**
     * Get url with the query string
     *
     * @return string
     * */
    public function getUrlWithQuery(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Get full url with query string
     *
     * @return string
     * */
    public function getFullUrlWithQuery(): string
    {
        return $this->getRequestScheme() . '://' . $this->getHttpHost() . $this->getFullUrl();
    }

    /**
     * get full url (without query string)
     *
     * @return string
     * */
    public function getFullUrl(): string
    {
        return $this->getRequestScheme() . '://' . $this->getHttpHost() . $this->getUrl();
    }

    /**
     * Get the content type
     *
     * @return ?string
     * */
    public function getContentType(): ?string
    {
        return $_SERVER['CONTENT_TYPE'] ?? null;
    }

    /**
     * Get the request time
     *
     * @return ?string
     * */
    public function getRequestTime(): ?string
    {
        return $_SERVER['REQUEST_TIME'] ?? null;
    }

    /**
     * get the request scheme
     *
     * @return ?string
     * */
    public function getRequestScheme(): ?string
    {
        return $_SERVER['REQUEST_SCHEME'] ?? null;
    }

    /**
     * Get the http host
     *
     * @return ?string
     * */
    public function getHttpHost(): ?string
    {
        return $_SERVER['HTTP_HOST'] ?? null;
    }

    /**
     * Get the script name
     *
     * @return ?string
     * */
    public function getScriptName(): ?string
    {
        return $_SERVER['SCRIPT_NAME'] ?? null;
    }
}