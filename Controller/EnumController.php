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

use Positibe\Bundle\EnumBundle\Entity\Enum;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class EnumController
 * @package Positibe\Bundle\EnumBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EnumController extends ResourceController
{
    /**
     * @param $typeSelected
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTypeFilterAction($typeSelected)
    {
        $types = $this->get('positibe.repository.enum_type')->findAll();

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
     * @throws \Throwable
     * @throws \TypeError
     */
    public function changeEnumAction(Request $request, $class, $id, $field, $enum, $type)
    {
        $enum = $this->get('positibe.repository.enum')->findEnumByType($enum, $type);
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

    /**
     * Load the correct locale for seo and menus depend of data_locale http parameter
     *
     * @param RequestConfiguration $configuration
     * @return Enum|\Sylius\Component\Resource\Model\ResourceInterface
     */
    protected function findOr404(RequestConfiguration $configuration)
    {
        /** @var Enum $enum */
        $enum = parent::findOr404($configuration);
        if ($dataLocale = $configuration->getRequest()->get('data_locale')) {
            $enum->setLocale($dataLocale);
            $this->get('doctrine.orm.entity_manager')->refresh($enum);
        }

        return $enum;
    }

}