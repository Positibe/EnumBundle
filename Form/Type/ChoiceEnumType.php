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

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Positibe\Bundle\EnumBundle\Model\HasEnumsInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class ChoiceEnumType
 * @package Positibe\Bundle\EnumBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ChoiceEnumType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $loader = function (Options $options) {
            //query to find enum from a type
            $queryBuilder = function (EntityRepository $er) use ($options) {
                $type = $options['enum_type'];
                return $er->createQueryBuilder('u')
                    ->join('u.type', 'type')
                    ->where('type.name = :type')
                    ->orderBy('u.position', 'ASC')
                    ->setParameter('type', $type);
            };

            //loader function
            return new ORMQueryBuilderLoader(
                $queryBuilder,
                $options['em'],
                $options['class']
            );
        };

        $resolver->setDefaults(
            array(
                'class' => 'PositibeEnumBundle:Enum',
                'loader' => $loader
            )
        );

        $resolver->setRequired(array('enum_type'));
        $resolver
            ->addAllowedTypes('enum_type')
            ->addAllowedTypes('enum_type', array('string'));
    }

    public function getParent()
    {
        return 'entity';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'positibe_choice_enum';
    }

} 