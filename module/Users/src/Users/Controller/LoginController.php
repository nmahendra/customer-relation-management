<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Users\Form\LoginForm;
use Users\Form\LoginFilter;

use Users\Model\User;
use Users\Model\UserTable;

/**
 * Controller for handling login requests.
 * @author mahendra
 * @version 1.0
 * @since 1.0
 * @copyright Amristar
 */
class LoginController extends AbstractActionController
{
	protected $storage;
    protected $authservice;

	/**
	 * initializing service locator
	 * @author mahendra
	 * @version 1.0
	 * @since 1.0
	 */
	public function getAuthService()
	{
		if(! $this->authservice) {
			$this->authservice = $this->getServiceLocator()->get('AuthService');
		}			
		return $this->authservice;
	}
	
	/**
	 * Controller for rendering login form
	 * @author mahendra
	 * @version 1.0
	 * @since 1.0
	 */
	public function indexAction()
	{
		$this->layout('layout/default');    	
		$form = $this->getServiceLocator()->get('LoginForm');
		$viewModel = new ViewModel(array('form' => $form));
		return $viewModel;
	}

	/**
	 * Controller for handling login requests.
	 * @author mahendra
	 * @version 1.0
	 * @since 1.0
	 */
    public function processAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(NULL , array( 
                        'controller' => 'login', 
                        'action' =>  'index' 
            ));
        }

        $post = $this->request->getPost();
		$form = $this->getServiceLocator()->get('LoginForm');
		$form->setData($post);

        if (!$form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'form'  => $form,
            ));
            $model->setTemplate('users/login/index');
            return $model;
        } else {
			//check authentication...
			$this->getAuthService()->getAdapter()
								   ->setIdentity($this->request->getPost('email'))
								   ->setCredential($this->request->getPost('password'));
            $result = $this->getAuthService()->authenticate();
            if ($result->isValid()) {
				$this->getAuthService()->getStorage()->write($this->request->getPost('email'));
				return $this->redirect()->toRoute(NULL , array( 
								'controller' => 'login', 
								'action' =>  'confirm' 
							));			
            } else {
				$model = new ViewModel(array(
					'error' => true,
					'form'  => $form,
				));
				$model->setTemplate('users/login/index');
				return $model;
			}
		}
    }

	/**
	 * Controller for login confirm action
	 * @author mahendra
	 * @version 1.0
	 * @since 1.0
	 */
	public function confirmAction()
	{
		$user_email = $this->getAuthService()->getStorage()->read();
		$viewModel  = new ViewModel(array(
            'user_email' => $user_email 
        )); 
		return $viewModel; 
	}

	/**
	 * Controller for logout action
	 * @author mahendra
	 * @version 1.0
	 * @since 1.0
	 */
	public function logoutAction()
	{
		$this->getAuthService()->clearIdentity();
		return $this->redirect()->toRoute('users/login'); 
	}
}
