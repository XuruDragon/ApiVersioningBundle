<?php

namespace XuruDragon\VersioningBundle\Changes;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AbstractChanges
 * @package XuruDragon\VersioningBundle\Changes
 */
abstract class AbstractChanges implements ChangesInterface
{
    /**
     * RequestStack.
     */
    protected $requestStack;

    /**
     * Constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return null|Request
     */
    public function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}