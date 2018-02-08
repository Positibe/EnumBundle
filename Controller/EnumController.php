<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\EnumBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use Positibe\Bundle\EnumBundle\Entity\Enum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class EnumController
 * @package Positibe\Bundle\EnumBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EnumController extends AdminController
{
    public function moveUpAction()
    {
        $easyadmin = $this->request->attributes->get('easyadmin');
        /** @var Enum $entity */
        $entity = $easyadmin['item'];

        $entity->setPosition($entity->getPosition() - 1);

        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->request->server->get('HTTP_REFERER'));
    }

    public function moveDownAction()
    {
        $easyadmin = $this->request->attributes->get('easyadmin');
        /** @var Enum $entity */
        $entity = $easyadmin['item'];

        $entity->setPosition($entity->getPosition() + 1);

        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->request->server->get('HTTP_REFERER'));
    }

    protected function createNewEntity()
    {
        $enum = new Enum();

        $type = $this->getDoctrine()->getRepository('PositibeEnumBundle:EnumType')->find(
            $this->request->query->get('type')
        );
        $enum->setType($type);

        return $enum;
    }



    // Creates the Doctrine query builder used to get all the items. Override it
    // to filter the elements displayed in the listing
    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        if (!$dqlFilter) {
            $dqlFilter = '';
        } else {
            $dqlFilter .= ' AND ';
        }
        $dqlFilter .= sprintf("entity.type = '%s'", $this->request->query->get('type'));

        return parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);
    }


    /**
     * @param $typeSelected
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTypeFilterAction($typeSelected)
    {
        $types = $this->getDoctrine()->getRepository('PositibeEnumBundle:EnumType')->findAll();

        return $this->render(
            '@PositibeEnum/Enum/_type_selected.html.twig',
            array(
                'types' => $types,
                'type_selected' => $typeSelected,
            )
        );
    }

    /**
     * Change an enum of an entity
     *
     * Warning: You most configure it in your routing.yml because it has not routing configuration
     *
     * @param Request $request
     * @param $class
     * @param $id
     * @param $field
     * @param $enum
     * @param $type
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeEnumAction(Request $request, $class, $id, $field, $enum, $type)
    {
        $enum = $this->getDoctrine()->getRepository('PositibeEnumBundle:EnumType')->findEnumByType($enum, $type);
        if ($enum !== null && $repository = $this->getDoctrine()->getRepository($class)
        ) {
            if ($object = $repository->find($id)) {
                $manager = $this->get('doctrine.orm.entity_manager');
                $accessor = PropertyAccess::createPropertyAccessor();
                $accessor->setValue($object, $field, $enum);
                $manager->persist($object);
                $manager->flush();
            }
        }

        $referer = $request->get('referer') ?: $request->server->get('HTTP_REFERER');

        return $this->redirect($referer);
    }
}