<?php
namespace StorageBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity
 * @ORM\Table(name="storage_file")
 **/
class File
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="files")
     */
    protected $category;

    /**
     * @ORM\Column(name="title", type="string", length=64)
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="slug", type="string", length=128)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", name="description", length=250)
     *
     * @var string
     */
    protected $description;

    /**
     * Gets the Primary key value.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets another category ID as the parent of this category.
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * get another category ID as the parent of this category.
     */
    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Clears the parent id and makes it null.
     */
    public function clearCategory()
    {
        $this->parent = null;
    }

    /**
     * Get the slug.
     * @return string
     */
    public function getSlug()
    {

        return $this->slug;

    }

    /**
     * Set the description.
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get the description.
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the description.
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets the description value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}