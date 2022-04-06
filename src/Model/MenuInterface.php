<?php

namespace Softspring\CmsBundle\Model;

use Doctrine\Common\Collections\Collection;

interface MenuInterface
{
    public function setType(string $type): void;

    public function getType(): ?string;

    public function setName(string $name): void;

    public function getName(): ?string;

    /**
     * @return Collection|null|MenuItemInterface[]
     */
    public function getItems(): ?Collection;

    public function addItem(MenuItemInterface $item): void;

    public function removeItem(MenuItemInterface $item): void;
}