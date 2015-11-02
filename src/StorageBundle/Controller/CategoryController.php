<?php

namespace StorageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function indexAction()
    {
        $qb = $this->getDoctrine()->getEntityManager()->getRepository('StorageBundle:Category')->createQueryBuilder('S');
        $query = $qb->addSelect('F')
            ->leftJoin('S.files', 'F')
            ->orderBy('S.root, S.lft')
            ->getQuery();
        $result = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $categories = $this->getDoctrine()->getEntityManager()->getRepository('StorageBundle:Category')->buildTree($result);
        return $this->render('StorageBundle:Category:index.html.twig', array('categories' => $categories));
    }
}
