<?php

namespace XuruDragon\ApiVersioningBundle\Factory;

use Symfony\Component\HttpFoundation\RequestStack;
use XuruDragon\VersioningBundle\Changes\AbstractChanges;

/**
 * Class ChangesFactory.
 */
class ChangesFactory
{
    /**
     * @var array
     */
    private $versions;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * ChangesFactory constructor.
     *
     * @param RequestStack $requestStack
     * @param array        $versions
     *
     * @throws \ReflectionException
     */
    public function __construct(RequestStack $requestStack, array $versions)
    {
        $this->versions = $versions;
        $this->requestStack = $requestStack;

        $this->prepare();
    }

    /**
     * Returns compatibility changes history for a given version.
     *
     * @param string $version
     *
     * @return null|array
     */
    public function getHistory($version)
    {
        if (!$this->has($version)) {
            return null;
        }

        $index = array_search($version, array_keys($this->versions), true);

        return \array_slice($this->versions, 0, $index + 1);
    }

    /**
     * @param string $version
     *
     * @return bool
     */
    public function has($version)
    {
        return isset($this->versions[$version]);
    }

    /**
     * @param string $version
     *
     * @return null|AbstractChanges
     */
    public function get($version)
    {
        if (!$this->has($version)) {
            return null;
        }

        return $this->versions[$version];
    }

    /**
     * Prepares class instances from class name.
     *
     * @throws \RuntimeException    when version changes class does not exist or does not implement VersionChangesInterface
     * @throws \ReflectionException
     */
    protected function prepare()
    {
        foreach ($this->versions as $version => $class) {
            if (!class_exists($class)) {
                throw new \RuntimeException(sprintf('Unable to find class "%s".', $class));
            }

            /*
             * the "instanceof" operator does not work on class names as strings.
             * $class instanceof VersionChangesInterface; Will return false.
             * So we should use reflection in this case.
             */
            $reflectionClass = new \ReflectionClass($class);
            if (!$reflectionClass->implementsInterface('XuruDragon\ApiVersioningBundle\Changes\ChangesInterface')) {
                throw new \RuntimeException(sprintf('Class "%s" does not implement VersionChangesInterface.', $class));
            }

            $instance = new $class($this->requestStack);

            $this->versions[$version] = $instance;
        }
    }
}
