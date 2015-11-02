<?php
namespace StorageBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="storage_category")
 * use repository for handy tree functions
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * Creates a parent / child relationship on this entity.
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent = null;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="category")
     */
    private $files;

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
    public function setParent(Category $category = null)
    {
        $this->parent = $category;
    }

    /**
     * get another category ID as the parent of this category.
     */
    /**
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Category
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Category
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Add children
     *
     * @param Category $children
     * @return Category
     */
    public function addChildren(Category $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Category $children
     */
    public function removeChildren(Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add children
     *
     * @param Files $children
     * @return Category
     */
    public function addFiles(File $files)
    {
        $this->files[] = $files;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Files $children
     */
    public function removeFiles(File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get Files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Clears the parent id and makes it null.
     */
    public function clearParent()
    {
        $this->parent = null;
    }

    /**
     * Set the slug.
     */
    public function setSlug($slug)
    {

        $this->slug = $slug;

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

    public function __toString()
    {
        $prefix = "";
        for ($i=2; $i<= $this->lvl; $i++){
            $prefix .= "-";
        }
        return $prefix . $this->title;
    }

    public function getLaveledTitle()
    {
        return (string)$this;
    }
}