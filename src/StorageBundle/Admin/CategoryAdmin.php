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
            ->where('p.parent IS NOT NULL');

        $query = new ProxyQuery($queryBuilder);
        return $query;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();
        $id = $subject->getId();
        $formMapper
            ->with('Category')
            ->add('parent', 'entity', array(
                'class' => 'StorageBundle\Entity\Category',
                'label' => 'Parent',
                'required'=>true,
                'query_builder' => function($er) use ($id) {
                    $qb = $er->createQueryBuilder('p');
                    $qb->andWhere('p.lvl < :level')
                        ->setParameter('level', self::MAX_LEVEL);
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
            ->add('title', 'text', array('label' => 'Title'))
            ->add('slug', 'text', array('label' => 'Slug'))
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
    }
    public function postUpdate($object)
    {
        $this->request->server->get('DOCUMENT_ROOT');
        $em = $this->modelManager->getEntityManager($object);
        $repo = $em->getRepository("StorageBundle:Category");
        $repo->verify();
        $repo->recover();
        $em->flush();
    }

    public function preRemove($object)
    {
        $this->request->server->get('DOCUMENT_ROOT');
        $em = $this->modelManager->getEntityManager($object);
        $repo = $em->getRepository("ShtumiPravBundle:Page");
        $repo->verify();
        $repo->recover();
        $em->flush();
    }
}