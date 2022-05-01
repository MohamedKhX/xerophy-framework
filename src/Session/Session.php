<?php

namespace Xerophy\Framework\Session;

use Xerophy\Framework\Http\Request;

class Session
{
    /*
     * The current Url.
     * */
    protected string $currentUrl;

    /*
     * The previous Url.
     * */
    protected ?string $previousUrl;

    /*
     * The request instance
     * */
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
        $this->previousUrl = $_SESSION['Previous_Url'];
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
     * Get the previous url
     *
     * @return ?string
     * */
    public function getPreviousUrl(): ?string
    {
        return $this->previousUrl ?? null;
    }

    public function __destruct()
    {
        $_SESSION['Previous_Url'] = $this->currentUrl;
    }
}