<?php

namespace LeaseWeb\ExcelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LeaseWebExcelBundle:Default:index.html.twig');
    }
}
