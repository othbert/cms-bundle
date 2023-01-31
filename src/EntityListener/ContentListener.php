<?php

namespace Softspring\CmsBundle\EntityListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Softspring\CmsBundle\Model\ContentInterface;

class ContentListener
{
    use TransformEntityValuesTrait;

    public function postLoad(ContentInterface $content, LifecycleEventArgs $event)
    {
        $this->untransform($content, $event);
    }

    public function preUpdate(ContentInterface $content, PreUpdateEventArgs $event)
    {
        $this->transform($content, $event);
    }

    public function prePersist(ContentInterface $content, LifecycleEventArgs $event)
    {
        $this->transform($content, $event);
    }

    public function transform(ContentInterface $content, LifecycleEventArgs $event)
    {
        if (!$content->getExtraData()) {
            return;
        }

        $extraData = $content->getExtraData();
        foreach ($extraData as $field => $value) {
            $extraData[$field] = $this->transformEntityValues($value, $event->getObjectManager());
        }
        $content->setExtraData($extraData);
    }

    public function untransform(ContentInterface $content, LifecycleEventArgs $event)
    {
        if (!$content->getExtraData()) {
            return;
        }

        $extraData = $content->getExtraData();
        foreach ($extraData as $field => $value) {
            $extraData[$field] = $this->untransformEntityValues($value, $event->getObjectManager());
        }
        $content->setExtraData($extraData);
    }
}
