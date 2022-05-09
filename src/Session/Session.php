<?php

namespace Xerophy\Framework\Session;

use Xerophy\Framework\Http\Request;

class Session
{

    /*
     * when we create the error save it to the next redierct->back->with(errors -> errors)
     * */


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

    /*
     * Error if there
     * */
    protected array|null $errors;


    public bool $saveErrorsToNextRedirection = false;

    /**
     * Create a new Session instance
     *
     * @retrun void
     * */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->currentUrl = $this->request->getUrl();
        $this->previousUrl = $_SESSION['Previous_Url'] ?? null;
        $this->errors = isset($_SESSION['Errors']) ? $_SESSION['Errors'] : null;
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

    /**
     * Get errors if there
     *
     * @return array|null
     * */
    public function getErrors(): array|null
    {
        return $this->errors;
    }

    public function createError(string $errorName, string $errorMessage)
    {
        $_SESSION['Errors'][$errorName] = $errorMessage;
    }

    public function __destruct()
    {
        if(!$this->saveErrorsToNextRedirection) {
            unset($_SESSION['Errors']);
        } else {
            $this->saveErrorsToNextRedirection = false;
        }

        $_SESSION['Previous_Url'] = $this->currentUrl;
    }
}