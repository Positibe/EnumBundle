<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\EnumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;


/**
 * Class EnumTranslation
 * @package Positibe\Bundle\EnumBundle\Entity
 *
 * @ORM\Table(name="positibe_enum_translations", indexes={
 *      @ORM\Index(name="positibe_enum_translation_idx", columns={"locale", "object_class", "field", "foreign_key"})
 * })
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EnumTranslation extends AbstractTranslation {

} 