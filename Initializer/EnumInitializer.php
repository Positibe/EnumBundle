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
use Gedmo\Sluggable\Util\Urlizer;
use Positibe\Bundle\EnumBundle\Entity\Enum;
use Positibe\Bundle\EnumBundle\Entity\EnumType;


/**
 * Class EnumInitializer
 * @package Positibe\Bundle\EnumBundle\Initializer
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EnumInitializer
{
    private $enumTypes;
    private $em;

    public function __construct($enumTypes, EntityManager $entityManager)
    {
        $this->enumTypes = $enumTypes;
        $this->em = $entityManager;
    }

    public function initialize()
    {
        foreach ($this->enumTypes as $typeName => $enums) {
            if (!$type = $this->em->getRepository('PositibeEnumBundle:EnumType')->findOneBy(
                array('name' => $typeName)
            )) {
                $type = new EnumType();
                $type->setName($typeName);

                if (isset($enums['_name'])) {
                    $type->setText($enums['_name']);
                }

                $this->em->persist($type);
            }
            unset($enums['_name']);

            foreach ($enums as $name => $enumText) {
                $name = Urlizer::urlize($name, '_');
                if (!$enum = $this->em->getRepository('PositibeEnumBundle:Enum')->findEnumByType($name, $typeName)) {
                    $enum = new Enum();
                }

                $enum->setName($name);
                $enum->setDeletable(false);
                $enum->setText($enumText);
                $enum->setType($type);
                $count = count($type->getEnums());
                $enum->setPosition($count);
                $type->addEnum($enum);

                $this->em->persist($enum);
            }
            $this->em->flush();
        }
        $this->em->flush();
    }
} 