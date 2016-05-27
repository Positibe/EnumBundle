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

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EnumController
 * @package Positibe\Bundle\EnumBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EnumController extends ResourceController
{
    public function showTypeFilterAction($typeSelected)
    {
        $types = $this->get('positibe.repository.enum_type')->findAll();

        return $this->render(
            '@PositibeEnum/Enum/_type_selected.html.twig',
            array(
                'types' => $types,
                'type_selected' => $typeSelected
            )
        );
    }

    /**
     * Load the correct locale for seo and menus depend of data_locale http parameter
     *
     * @param Request $request
     * @param array $criteria
     * @return object|void
     */
    public function findOr404(Request $request, array $criteria = array())
    {
        $enum = parent::findOr404($request, $criteria);

        if ( $dataLocale = $request->get('data_locale')) {
            $enum->setLocale($dataLocale);
            $this->get('doctrine.orm.entity_manager')->refresh($enum);
        }

        return $enum;
    }
} 