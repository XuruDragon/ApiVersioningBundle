<?php

namespace XuruDragon\ApiVersioningBundle\Changes;

/**
 * Interface ChangesInterface.
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
