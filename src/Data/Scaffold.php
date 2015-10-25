<?php namespace Aedart\Scaffold\Data;

use Aedart\DTO\DataTransferObject;
use Aedart\Model\Traits\Strings\BasePathTrait;
use Aedart\Model\Traits\Strings\DescriptionTrait;
use Aedart\Model\Traits\Strings\NameTrait;
use Aedart\Scaffold\Contracts\Data\Scaffold as ScaffoldInterface;
use Aedart\Scaffold\Traits\DataCollectionTrait;
use Aedart\Scaffold\Traits\DirectoryHandlerTrait;
use Aedart\Scaffold\Traits\TemplatesCollectionTrait;

/**
 * <h1>Scaffold</h1>
 *
 * @see \Aedart\Scaffold\Contracts\Data\Scaffold
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Data
 */
class Scaffold extends DataTransferObject implements ScaffoldInterface {

    use NameTrait,
        DescriptionTrait,
        BasePathTrait,
        DirectoryHandlerTrait,
        TemplatesCollectionTrait,
        DataCollectionTrait;

    public function toArray() {
        $arr = parent::toArray();

        // Make sure that collections are also exported as
        // array, rather than object instances
        $arr['templates'] = $this->getTemplates()->toArray();
        $arr['data'] = $this->getData()->toArray();

        return $arr;
    }
}