<?php

namespace Xerophy\Framework\Session;

use Xerophy\Framework\Http\Request;

class Session
{
    protected string $currentUrl;

    protected ?string $previosUrl;

    protected Request $request;

    /**
     * Create a new Session instance
     *
     * @retrun void
     * */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->currentUrl = $this->request->getUrl();
        $this->previosUrl = $_SESSION['Previos_Url'];
    }

    /**
     * Get the current url
     *
     * @return string
     * */
    public function getCurrentUrl(): string
    {
        return $this->currentUrl;
    }

    /**
     * Get the previos url
     *
     * @return ?string
     * */
    public function getPreviosUrl(): ?string
    {
        return $this->previosUrl ?? null;
    }

    public function __destruct()
    {
        $_SESSION['Previos_Url'] = $this->currentUrl;
    }
}