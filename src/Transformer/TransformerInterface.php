<?php

namespace Softspring\CmsBundle\Transformer;

use Doctrine\Persistence\ObjectManager;

interface TransformerInterface
{
    /**
     * @throws UnsupportedException
     */
    public function transform(object $entity, ObjectManager $em): void;

    /**
     * @throws UnsupportedException
     */
    public function untransform(object $entity, ObjectManager $em): void;
}
