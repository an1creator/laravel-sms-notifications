<?php

namespace N1Creator\LaravelSmsNotifications\Zelenin;

abstract class AbstractAuth implements AuthInterface
{

    /**
     * @var Api
     */
    private $context;

    /**
     * @return array
     */
    abstract public function getAuthParams();

    /**
     * @return null|string
     */
    abstract public function getPartnerId();

    /**
     * @return Api
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Api $context
     */
    public function setContext(Api $context)
    {
        $this->context = $context;
    }
}
