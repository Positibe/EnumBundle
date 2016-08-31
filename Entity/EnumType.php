<?php

namespace Positibe\Bundle\EnumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Sluggable\Util\Urlizer;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * EnumType
 *
 * @ORM\Table("positibe_enum_type")
 * @ORM\Entity
 * @UniqueEntity("name")
 */
class EnumType implements ResourceInterface
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
     * @ORM\Column(name="name", type="string", length=255, unique=TRUE)
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
     * @var Enum[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Positibe\Bundle\EnumBundle\Entity\Enum", mappedBy="type", cascade="all", orphanRemoval=TRUE)
     */
    private $enums;

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

    public function __toString()
    {
        return $this->text ? $this->text : $this->getUpperName() . '*';
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->enums = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EnumType
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
     * @return ArrayCollection|Enum[]
     */
    public function getEnums()
    {
        return $this->enums;
    }

    /**
     * @param ArrayCollection|Enum[] $enums
     */
    public function setEnums($enums)
    {
        $this->enums = $enums;
    }

    /**
     * @param Enum $enum
     * @return $this
     */
    public function addEnum(Enum $enum)
    {
        $this->enums[] = $enum;

        return $this;
    }

    /**
     * @param Enum $enum
     * @return $this
     */
    public function removeEnum(Enum $enum)
    {
        $this->enums->removeElement($enum);

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
}
