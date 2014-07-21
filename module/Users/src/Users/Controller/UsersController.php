<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsersController extends AbstractActionController
{
	public function indexAction()
	{
		$view = new ViewModel();
		return $view;
	}
}



