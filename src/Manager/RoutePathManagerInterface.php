<?php

namespace Softspring\CmsBundle\Manager;

use Softspring\CmsBundle\Model\RoutePathInterface;
use Softspring\Component\CrudlController\Manager\CrudlEntityManagerInterface;

interface RoutePathManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return RoutePathInterface
     */
    public function createEntity(): object;

    /**
     * @psalm-param RoutePathInterface $entity
     */
    public function saveEntity(object $entity): void;

    /**
     * @psalm-param RoutePathInterface $entity
     */
    public function deleteEntity(object $entity): void;
}
