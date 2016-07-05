<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\EnumBundle\Factory;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\EnumBundle\Entity\Enum;
use Sylius\Component\Resource\Factory\Factory;


/**
 * Class EnumFactory
 * @package Positibe\Bundle\EnumBundle\Factory
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EnumFactory extends Factory
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    public function createNewByType($type)
    {
        /** @var Enum $enum */
        $enum = $this->createNew();
        $enumTypeRepository = $this->getEm()->getRepository('PositibeEnumBundle:EnumType');
        if ($enumType = $enumTypeRepository->findOneBy(array('name' => $type))) {
            $enum->setType($enumType);
        }

        return $enum;
    }
} 