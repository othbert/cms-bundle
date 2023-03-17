<?php

namespace Softspring\CmsBundle\Data\EntityTransformer;

use Softspring\CmsBundle\Data\Exception\InvalidElementException;
use Softspring\CmsBundle\Data\ReferencesRepository;
use Softspring\CmsBundle\Model\ContentInterface;

interface ContentEntityTransformerInterface extends EntityTransformerInterface
{
    /**
     * @throws InvalidElementException
     */
    public function export(object $element, &$files = [], object $contentVersion = null, string $contentType = null): array;

    public function import(array $data, ReferencesRepository $referencesRepository, array $options = []): ContentInterface;
}