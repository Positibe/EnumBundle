<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\EnumBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\ChoiceList\DoctrineChoiceLoader;
use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class ChoiceEnumType
 * @package Positibe\Bundle\EnumBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ChoiceEnumType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $choiceLoader = function (Options $options) {
            //query to find enum from a type
            $type = $options['enum_type'];
            $queryBuilder = $options['em']->getRepository($options['class'])->createQueryBuilder('u')
                ->join('u.type', 'type')
                ->where('type.name = :type')
                ->orderBy('u.position', 'ASC')
                ->setParameter('type', $type);
            $entityLoader = new ORMQueryBuilderLoader($queryBuilder);

            $doctrineChoiceLoader = new DoctrineChoiceLoader(
                $options['em'],
                $options['class'],
                $options['id_reader'],
                $entityLoader
            );

            return $doctrineChoiceLoader;
        };

        $resolver->setDefaults(
            array(
                'class' => 'PositibeEnumBundle:Enum',
                'choice_loader' => $choiceLoader,
                'attr' => ['data-widget' => 'select2'],
            )
        );

        $resolver->setRequired(['enum_type']);
        $resolver->addAllowedTypes('enum_type', ['string']);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}