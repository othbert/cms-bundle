<?php

namespace Softspring\CmsBundle\Manager;

use Softspring\CmsBundle\Model\RouteInterface;
use Softspring\Component\CrudlController\Manager\CrudlEntityManagerInterface;

interface RouteManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return RouteInterface
     */
    public function createEntity(): object;

    /**
     * @psalm-param RouteInterface $entity
     */
    public function saveEntity(object $entity): void;

    /**
     * @psalm-param RouteInterface $entity
     */
    public function deleteEntity(object $entity): void;

    public function getRoutePathManager(): RoutePathManagerInterface;
}
