<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\EnumBundle\Model;

/**
 * Class HasEnumsInterface
 * @package Positibe\Bundle\EnumBundle\Model
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface HasEnumsInterface
{
    /**
     * Get a dictionary of field enum and its enum type. You must set the unique enum type
     *
     * e.j. array('category' => self::UNIQUE_CATEGORY_TYPE, 'state' => self::UNIQUE_STATE_TYPE)
     *
     * @return mixed
     */
    public static function getEnumFields();
} 