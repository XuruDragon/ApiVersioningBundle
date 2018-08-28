<?php

namespace XuruDragon\VersioningBundle\Changes;

/**
 * Interface ChangesInterface
 * @package XuruDragon\VersioningBundle\Changes
 */
interface ChangesInterface
{
    /**
     * Apply version changes for current request.
     *
     * @param array $data
     *
     * @return null|array
     */
    public function apply(array $data = []);

    /**
     * Returns if this version changes is supported for current request.
     *
     * @param array $data
     *
     * @return null|bool
     */
    public function supports(array $data = []);
}