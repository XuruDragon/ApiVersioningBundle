<?php

namespace XuruDragon\VersioningBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use XuruDragon\VersioningBundle\Factory\ChangesFactory;

/**
 * Class VersioningListener
 * @package XuruDragon\VersioningBundle\EventListener
 */
class VersioningListener
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var ChangesFactory
     */
    protected $changesFactory;

    /**
     * @var string
     */
    protected $headerName;

    /**
     * Constructor.
     *
     * @param RequestStack   $requestStack
     * @param ChangesFactory $changesFactory
     * @param string         $headerName
     */
    public function __construct(RequestStack $requestStack, ChangesFactory $changesFactory, $headerName)
    {
        $this->requestStack = $requestStack;
        $this->changesFactory = $changesFactory;
        $this->headerName = $headerName;
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $version = $this->getRequest()->headers->get($this->headerName);

        $versionChanges = $this->changesFactory->getHistory($version);

        if (!$versionChanges) {
            return null;
        }

        $data = json_decode($event->getResponse()->getContent(), true);
        $data = $this->apply($versionChanges, $data);

        $response = $event->getResponse();
        $response->setContent(json_encode($data));

        $event->setResponse($response);
    }

    /**
     * Apply given version changes for given data.
     *
     * @param array $versionChanges
     * @param array $data
     *
     * @return null|array
     */
    private function apply($versionChanges = [], $data = [])
    {
        foreach ($versionChanges as $version => $changes) {
            if (!$changes->supports($data)) {
                continue;
            }

            $data = $changes->apply($data);
        }

        return $data;
    }

    /**
     * @return Request
     */
    private function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}
