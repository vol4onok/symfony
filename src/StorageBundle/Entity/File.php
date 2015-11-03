<?php
namespace StorageBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="files")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

    /**
     * @ORM\Column(name="title", type="string", length=64)
     * @var string
     */
    private $title;

    /**
     * Unmapped property to handle file uploads
     */
    private $file;

    /**
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", name="description", length=250)
     *
     * @var string
     */
    private $description;

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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get the slug.
     * @return UploadedFile
     */
    public function getSlug()
    {

        return $this->slug;

    }

    /**
     * set the slug.
     * @param $file
     */
    public function setSlug($file)
    {

        $this->slug = $file;

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

    public function upload($path)
    {
        if (null === $this->getFile()) {
            return;
        }
        $fileName = uniqid() . $this->getFile()->getClientOriginalName();
        $this->getFile()->move(
            $path,
            $fileName
        );
        $this->slug = $fileName;
        $this->setFile(null);
    }
}