<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function initialize()
    {
        $this->view->setVar('title', 'Kelola Data Mahasiswa');
        $this->view->setVar('currentController', strtolower($this->dispatcher->getControllerName()));
    }
}
