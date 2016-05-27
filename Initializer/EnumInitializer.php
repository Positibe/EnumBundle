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
use Positibe\Bundle\EnumBundle\Entity\Enum;
use Positibe\Bundle\EnumBundle\Entity\EnumType;
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
    private $enumRepository;
    /** @var  EntityManagerInterface */
    private $enumManager;
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
            if (null === $type = $this->enumTypeRepository->findOneBy(array('name' => $typeName))) {
                $type = $this->enumTypeRepository->createNew();
                $type->setName($typeName);

                $this->enumTypeManager->persist($type);
            }

            foreach ($enums as $enumName => $enumText) {
                /** @var Enum $enum */
                if (null === $enum = $this->enumRepository->findOneBy(array('name' => $enumName))) {
                    $enum = $this->enumRepository->createNew();

                }
                $enum->setName($enumName);
                $enum->setText($enumText);
                $enum->setType($type);

                $this->enumManager->persist($enum);
            }
            $this->enumManager->flush();
        }
        $this->enumTypeManager->flush();
    }
} 