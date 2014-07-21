<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Users\Form\RegisterForm;
use Users\Form\RegisterFilter;

use Users\Model\User;

/**
 * Controller for register and manage users
 * @author cwp
 * @version 1.0
 * @since 1.0
 * @copyright Amristar
 */
class RegisterController extends AbstractActionController
{
	/**
	 * Controller for login confirm action
	 * @author mahendra
	 * @version 1.0
	 * @since 1.0
	 */
	public function indexAction()
	{
		$this->layout('layout/default');    	
		$form = $this->getServiceLocator()->get('RegisterForm'); 
		$viewModel = new ViewModel(array('form' => $form));
		return $viewModel;
	}

	protected function createUser(array $data)
	{
		$user = new User();
		$user->exchangeArray($data);

		$user->setPassword($data['password']);

		$userTable = $this->getServiceLocator()->get('UserTable');
		$userTable->saveUser($user);

		return true;
	}

	public function processAction()
	{
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('users/register');
        }

        $post = $this->request->getPost();
		$form = $this->getServiceLocator()->get('RegisterForm');

        $form->setData($post);
        if (!$form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'form'  => $form,
            ));
            $model->setTemplate('users/register/index');
            return $model;
        }

        // Create user
        $this->createUser($form->getData());

        return $this->redirect()->toRoute('users/register' , array( 
                        'action' =>  'confirm' 
		));
	}

	public function confirmAction()
	{
		$viewModel = new ViewModel();
		return $viewModel;
	}

}
