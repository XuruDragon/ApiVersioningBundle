<?php

namespace XuruDragon\ApiVersioningBundle\Changes;

/**
 * Class Changes000.
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
