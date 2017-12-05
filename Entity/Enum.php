<?php

namespace Positibe\Bundle\EnumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Sluggable\Util\Urlizer;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Enum
 *
 * @ORM\Table(name="positibe_enum")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\EnumBundle\Repository\EnumRepository")
 * @UniqueEntity(fields={"name", "type"},errorPath="name", repositoryMethod="findByNameAndType")
 * @Gedmo\TranslationEntity(class="Positibe\Bundle\EnumBundle\Entity\EnumTranslation")
 */
class Enum implements ResourceInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255, nullable=TRUE)
     * @Gedmo\Translatable
     */
    private $text;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var EnumType
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\EnumBundle\Entity\EnumType", inversedBy="enums")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="deletable", type="boolean", nullable=TRUE)
     */
    private $deletable = true;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->text ?: $this->name;
    }

    public function getUpperName()
    {
        return strtoupper($this->name);
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Enum
     */
    public function setName($name)
    {
        $this->name = Urlizer::urlize($name, '_');

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return EnumType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param EnumType $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeletable()
    {
        return $this->deletable;
    }

    /**
     * @param string $deletable
     */
    public function setDeletable($deletable)
    {
        $this->deletable = $deletable;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
