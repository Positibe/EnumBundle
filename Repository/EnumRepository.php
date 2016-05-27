<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\EnumBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Positibe\Bundle\EnumBundle\Entity\Enum;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * Class EnumRepository
 * @package Positibe\Bundle\EnumBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EnumRepository extends EntityRepository
{
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        if (!empty($criteria['name'])) {
            $queryBuilder
                ->andWhere($this->getAlias() . '.name LIKE :name')
                ->setParameter('name', '%' . $criteria['name'] . '%');
            unset($criteria['name']);
        }

        if (!empty($criteria['text'])) {
            $queryBuilder
                ->andWhere($this->getAlias() . '.text LIKE :text')
                ->setParameter('text', '%' . $criteria['text'] . '%');
            unset($criteria['text']);
        }

        if (!empty($criteria['type'])) {
            $queryBuilder->join($this->getAlias() . '.type', 'type')
                ->andWhere('type.name = :type')
                ->setParameter('type', $criteria['type']);
            unset($criteria['type']);
        }
        parent::applyCriteria($queryBuilder, $criteria);
    }

    public function findEnumByType($enum, $type)
    {
        $qb = $this->getQueryBuilder()->join($this->getAlias() . '.type', 'type')
            ->andWhere($this->getAlias() . '.name = :name')
            ->andWhere('type.name = :type')
            ->setParameter('type', $type)
            ->setParameter('name', $enum);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findEnums($type)
    {
        $qb = $this->getQueryBuilder()->join($this->getAlias() . '.type', 'type')
            ->andWhere('type.name = :type')
            ->setParameter('type', $type);

        return $qb->getQuery()->getResult();
    }

    public function createNewByType($type)
    {
        /** @var Enum $enum */
        $enum =  $this->createNew();
        $enumTypeRepository = $this->getEntityManager()->getRepository('PositibeEnumBundle:EnumType');
        if ($enumType = $enumTypeRepository->findOneBy(array('name' => $type))) {
            $enum->setType($enumType);
        }

        return $enum;
    }
} 