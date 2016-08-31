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