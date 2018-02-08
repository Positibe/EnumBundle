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

/**
 * Class EnumTypeController
 * @package Positibe\Bundle\EnumBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EnumTypeController extends AdminController
{
    public function enumsAction()
    {
        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'type' => $this->request->query->get('id'),
            'entity' => 'Enum',
        ));
    }
}