<?php

namespace Softspring\CmsBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Softspring\CmsBundle\Model\Traits\SiteSimpleCountriesTrait as SiteSimpleCountriesTraitModel;

trait SiteSimpleCountriesTrait
{
    use SiteSimpleCountriesTraitModel;

    /**
     * @var array
     * @ORM\Column(name="countries", type="simple_array", nullable=false)
     */
    protected $countries = [];
}