<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\EnumBundle\Form\EventListener;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\EnumBundle\Entity\Enum;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


/**
 * Class NewGroupToEnumFormListener
 * @package AppBundle\Form\EventListener
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class NewEnumTextToEnumFormSubscriber implements EventSubscriberInterface
{
    /** @var  EntityManager */
    private $manager;
    private $enumTypeName;

    public function __construct(EntityManager $entityManager, $enumTypeName)
    {
        $this->manager = $entityManager;
        $this->enumTypeName = $enumTypeName;
    }

    public function onPreSetData(FormEvent $event)
    {
        $idOrNewText = $event->getData();
        if (empty($idOrNewText) || $group = $this->manager->getRepository('PositibeEnumBundle:Enum')->find($idOrNewText)) {
            return;
        }
        $enum = new Enum();
        $enum->setText($idOrNewText);
        $enum->setName($idOrNewText);

        if (!$enumType = $this->manager->getRepository('PositibeEnumBundle:EnumType')->findOneBy(
          array('name' => $this->enumTypeName)
        )
        ) {
            return;
        }
        $enum->setType($enumType);
        $this->manager->persist($enum);
        $this->manager->flush();

        $event->setData($enum->getId());
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SUBMIT => 'onPreSetData');
    }

} 