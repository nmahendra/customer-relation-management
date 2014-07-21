<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Account\Model\Account;
use Account\Model\AccountTable;

class IndexController extends AbstractActionController
{
    public function indexAction()
	{
		$view = new ViewModel();
		//$view->setTerminal(true);
		$view->setVariable('userName', 'Guest');		

		if(isset($_POST['name'])){
			$view->setVariable('userName', htmlspecialchars($_POST['name']));
		}

		$accountTable = $this->getServiceLocator()->get('AccountTable');
		$view  = new ViewModel(array('accounts' => $accountTable->fetchAll()));

		return $view;

		/*
		$this->layout('layout/default');    	
		$accountTable = $this->getServiceLocator()->get('AccountTable');
		$viewModel  = new ViewModel(array('accounts' => $accountTable->fetchAll()));
		return $viewModel;
		 */
    }
}
