<?php

namespace XuruDragon\VersioningBundle\Changes;

/**
 * Class Changes000
 * @package XuruDragon\VersioningBundle\Changes
 */
class Changes000 extends AbstractChanges
{
    /**
     * {@inheritdoc}
     */
    public function apply(array $data = [])
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(array $data = [])
    {
        return true;
    }
}