<?php

namespace StorageBundle\Admin;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

class FileAdmin extends Admin
{

    const MAX_FILES = 10;
    protected $categoryPath;
    protected $oldFilePath;


    public function setSubject($subject) {
        if ($subject->getId()) {
            $filePath = $this->getFilePath($subject->getCategory());
            $filePath .= $subject->getSlug();
            $this->oldFilePath = $filePath;
        }
        $this->subject = $subject;
    }
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();
        $id = $subject->getId();
        $formMapper
            ->add('title', 'text', array('label' => 'Title'))
            ->add('category', 'entity', array(
                'class' => 'StorageBundle\Entity\Category',
                'label' => 'Category',
                'required'=>true,
                'query_builder' => function($er) use ($id) {
                    $qb = $er->createQueryBuilder('p');
                    $qb->where('p.parent IS NOT NULL');
                    if ($id){
                        $qb
                            ->andWhere('p.id <> :id')
                            ->setParameter('id', $id);
                    }
                    $qb
                        ->orderBy('p.root, p.lft', 'ASC');
                    return $qb;
                },
                'constraints' => array(
                    new Assert\Callback(array(array($this, 'validateCountInDir')))
                )
            ))
            ->add('file', 'file', array(
                'required' => true,
                'constraints' => array(
                    new \Symfony\Component\Validator\Constraints\File(array('mimeTypes' => array("text/plain")))
                    )
            ))
            ->add('description', 'textarea', array('label' => 'Description'))
            ->end()
        ;

    }

    /**
     * @param $event
     * @param ExecutionContextInterface $context
     */
    public function validateCountInDir($event, ExecutionContextInterface $context) {

        $path = $this->getFilePath($context->getValue());
        $countFiles = count(glob("{$path}*.*"));
        if ($countFiles >= self::MAX_FILES) {
            $context->addViolation("Maximum " . self::MAX_FILES . " files in the category", array(), null);
        }
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title')
            ->add('slug');

    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('title')
            ->add('category.title')
            ->add('slug', 'file');
    }

    /**
     * @param \StorageBundle\Entity\File $file
     */
    public function prePersist($file) {
        $this->saveFile($file);
    }

    /**
     * @param \StorageBundle\Entity\File $file
     */
    public function preUpdate($file) {
        $this->saveFile($file);
    }

    /**
     * @param \StorageBundle\Entity\File $file
     */
    public function preRemove($file) {
        $fullPath = $this->getFilePath($file->getCategory());
        $fullPath .= $file->getSlug();
        if (file_exists($fullPath)) {
            unlink($fullPath);
        };
    }

    /**
     * @param \StorageBundle\Entity\File $file
     */
    public function saveFile(\StorageBundle\Entity\File $file) {

        $fullPath = $this->getFilePath($file->getCategory());
        if ($this->oldFilePath && file_exists($this->oldFilePath)) {
            unlink($this->oldFilePath);
        }
        $file->upload($fullPath);
    }

    private function getFilePath(\StorageBundle\Entity\Category $category) {
        $em = $this->modelManager->getEntityManager($category);
        $repo = $em->getRepository("StorageBundle:Category");
        $fullPath = $this->request->server->get('DOCUMENT_ROOT') . DIRECTORY_SEPARATOR;
        $fullPath .= implode(DIRECTORY_SEPARATOR, array_map(function(\StorageBundle\Entity\Category $object) {
            return $object->getSlug();
        }, $repo->getPath($category)));
        $fullPath .= DIRECTORY_SEPARATOR;
        return $fullPath;
    }

}