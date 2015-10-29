<?php

namespace StorageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('StorageBundle:Default:index.html.twig', array('name' => $name));
    }
}
