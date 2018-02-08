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

use Doctrine\ORM\EntityRepository;
use Positibe\Bundle\EnumBundle\Entity\Enum;

/**
 * Class EnumRepository
 * @package Positibe\Bundle\EnumBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EnumRepository extends EntityRepository
{
    /**
     * @param $enum
     * @param $type
     * @return Enum
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findEnumByType($enum, $type)
    {
        $qb = $this->createQueryBuilder('u')
            ->join('u.type', 'type')
            ->andWhere('u.name = :name')
            ->andWhere('type.name = :type')
            ->setParameter('type', $type)
            ->setParameter('name', $enum);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $type
     * @return Enum[]|array
     */
    public function findEnums($type)
    {
        $qb = $this->createQueryBuilder('u')
            ->join('u.type', 'type')
            ->andWhere('type.name = :type')
            ->setParameter('type', $type);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $criteria
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByNameAndType($criteria)
    {
        $name = $criteria['name'];
        $type = $criteria['type'];

        $qb = $this->createQueryBuilder('u')
            ->join('u.type', 'type')
            ->andWhere('u.name = :name')
            ->andWhere('type.name = :type')
            ->setParameter('type', $type)
            ->setParameter('name', $name);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $criteria
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByNameAndType($criteria)
    {
        $name = $criteria['name'];
        $type = $criteria['type'];

        $qb = $this->createQueryBuilder('u')
            ->join('u.type', 'type')
            ->andWhere('u.name = :name')
            ->andWhere('type.name = :type')
            ->setParameter('type', $type)
            ->setParameter('name', $name);
        if (isset($criteria['deletable']) && (Boolean)$criteria['deletable']) {
            $qb->andWhere('u.deletable IS TRUE');
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
} 