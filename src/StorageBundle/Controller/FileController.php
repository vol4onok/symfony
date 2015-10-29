<?php

namespace StorageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('StorageBundle:Default:index.html.twig', array('name' => $name));
    }
}
