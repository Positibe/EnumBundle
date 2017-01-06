<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\EnumBundle\Initializer;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Positibe\Bundle\EnumBundle\Entity\Enum;
use Positibe\Bundle\EnumBundle\Entity\EnumType;
use Positibe\Bundle\EnumBundle\Repository\EnumRepository;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * Class EnumInitializer
 * @package Positibe\Bundle\EnumBundle\Initializer
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EnumInitializer
{
    private $enumTypes;
    /** @var EnumRepository */
    private $enumRepository;
    /** @var  EntityManagerInterface */
    private $enumManager;
    /** @var EntityRepository */
    private $enumTypeRepository;
    /** @var  EntityManagerInterface */
    private $enumTypeManager;

    public function __construct(
        $enumTypes,
        $enumTypeManager,
        EntityRepository $enumTypeRepository,
        $enumManager,
        EntityRepository $enumRepository
    ) {
        $this->enumTypes = $enumTypes;
        $this->enumManager = $enumManager;
        $this->enumRepository = $enumRepository;
        $this->enumTypeRepository = $enumTypeRepository;
        $this->enumTypeManager = $enumTypeManager;
    }

    public function initialize()
    {
        foreach ($this->enumTypes as $typeName => $enums) {
            /** @var EnumType $type */
            if (!$type = $this->enumTypeRepository->findOneBy(array('name' => $typeName))) {
                $type = new EnumType();
                $type->setName($typeName);

                if (isset($enums['_name'])) {
                    $type->setText($enums['_name']);
                }

                $this->enumTypeManager->persist($type);
            }
            unset($enums['_name']);

            foreach ($enums as $enumName => $enumText) {
                $name = Urlizer::urlize($enumName);
                if (!$enum = $this->enumRepository->findEnumByType($name, $typeName)) {
                    $enum = new Enum();
                }

                $enum->setName($name);
                $enum->setDeletable(false);
                $enum->setText($enumText);
                $enum->setType($type);
                $count = count($type->getEnums());
                $enum->setPosition($count);
                $type->addEnum($enum);

                $this->enumManager->persist($enum);
            }
            $this->enumManager->flush();
        }
        $this->enumTypeManager->flush();
    }
} 