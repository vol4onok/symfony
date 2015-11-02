<?php

namespace StorageBundle\Admin;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class CategoryAdmin extends Admin
{
    /**
     * Max depth
     */
    const MAX_LEVEL = 5;

    protected $oldPath;
    protected $newPath;

    /**
     * @param string $context
     * @return ProxyQuery
     */
    public function createQuery($context = 'list')
    {
        $em = $this->modelManager->getEntityManager('StorageBundle\Entity\Category');

        $queryBuilder = $em
            ->createQueryBuilder('p')
            ->select('p')
            ->from('StorageBundle:Category', 'p')
            ->where('p.parent IS NOT NULL')
            ->orderBy('p.root, p.lft', 'ASC');

        $query = new ProxyQuery($queryBuilder);
        return $query;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();
        $id = $subject->getId();
        $maxLevel = self::MAX_LEVEL;
        $formMapper
            ->with('Category')
            ->add('parent', 'entity', array(
                'class' => 'StorageBundle\Entity\Category',
                'label' => 'Parent',
                'required'=>true,
                'query_builder' => function($er) use ($id, $maxLevel) {
                    $qb = $er->createQueryBuilder('p');
                    $qb->andWhere('p.lvl <= :level')
                        ->setParameter('level', $maxLevel);
                    if ($id){
                        $qb
                            ->andWhere('p.id <> :id')
                            ->setParameter('id', $id);
                    }
                    $qb
                        ->orderBy('p.root, p.lft', 'ASC');
                    return $qb;
                }
            ))
            ->add('title', 'text', array('label' => 'Title', 'required'=>true))
            ->add('slug', 'text', array('label' => 'Slug', 'required'=>true))
            ->add('description', 'textarea', array('label' => 'Description'))
            ->end()
        ;

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title')
            ->add('slug');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('laveled_title', null, array('sortable'=>false, 'label'=>'Title'))
            ->add('slug', 'text');
    }

    public function postPersist($object)
    {
        $this->request->server->get('DOCUMENT_ROOT');
        $em = $this->modelManager->getEntityManager($object);
        $repo = $em->getRepository("StorageBundle:Category");
        $repo->verify();
        $repo->recover();
        $em->flush();
        $this->newPath = $this->getFullPath($object);
        $this->checkDirHierarchy();
    }

    public function postUpdate($object)
    {
        $this->request->server->get('DOCUMENT_ROOT');
        $em = $this->modelManager->getEntityManager($object);
        $repo = $em->getRepository("StorageBundle:Category");
        $repo->verify();
        $repo->recover();
        $em->flush();
        $this->newPath = $this->getFullPath($object);
        $this->checkDirHierarchy();
    }

    public function preRemove($object)
    {
        $this->request->server->get('DOCUMENT_ROOT');
        $em = $this->modelManager->getEntityManager($object);
        $repo = $em->getRepository("StorageBundle:Category");
        $repo->verify();
        $repo->recover();
        $em->flush();
    }

    public function postRemove($object)
    {
        $this->checkDirHierarchy();
    }

    /**
     * @param $object
     * @return string
     */
    private function getFullPath($object) {
        $em = $this->modelManager->getEntityManager($object);
        $repo = $em->getRepository("StorageBundle:Category");
        return implode(DIRECTORY_SEPARATOR, array_map(function(\StorageBundle\Entity\Category $object) {
                return $object->getSlug();
        }, $repo->getPath($object)));
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject) {
        if ($subject->getId()) {
            $this->oldPath = $this->getFullPath($subject);
        }
        $this->subject = $subject;
    }

    /**
     * Creator path
     */
    private function checkDirHierarchy() {
        $path = $this->request->server->get('DOCUMENT_ROOT') . DIRECTORY_SEPARATOR;
        if ($this->newPath && empty($this->oldPath) && !is_dir($path . $this->newPath)) {
            //create dir
            exec("mkdir -p " . $path . $this->newPath);
        } elseif ($this->newPath && $this->oldPath && $this->newPath != $this->oldPath && is_dir($this->oldPath)) {
            // move or rename dir
            exec ("mv " . $path . $this->oldPath . ' ' . $path . $this->newPath);
        } elseif (empty($this->newPath) && $this->oldPath && is_dir($path . $this->newPath)) {
            // remove dir
            exec("rm -R " . $path . $this->oldPath);
        }
    }
}