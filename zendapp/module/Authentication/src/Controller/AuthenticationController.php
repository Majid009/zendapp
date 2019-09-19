<?php
namespace Authentication\Controller;

use Authentication\Model\AuthenticationTable;   // Impoting Model
use Authentication\Model\Authentication; 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

// Add the following import statements at the top of the file:
use Authentication\Form\RegisterForm;
use Authentication\Form\LoginForm;

class AuthenticationController extends AbstractActionController
{
    private $table;

    // Add this constructor:
    public function __construct(AuthenticationTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $form = new LoginForm();
        $form->get('submit')->setValue('Login');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $user_data = new Authentication();

        $form->setInputFilter($user_data->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $user_data->exchangeArray($form->getData());

       $result = $this->table->auth($user_data);

       if(! $result){
        return $this->redirect()->toRoute('authentication');
       } else if(isset($result)){
          
           $sessionData = new Container();

           $sessionData->isLogin = 'YES' ;
           $sessionData->username = $result->username;
           
           // rediect to Album Pages
           return $this->redirect()->toRoute('album'); 

       }
        
    }

    public function registersuccessAction()
    {
        return new viewModel();
    }

    public function registerAction()
    {
        // Route for Registration 

        $form = new RegisterForm();
        $form->get('submit')->setValue('Register');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $user_data = new Authentication();

        $form->setInputFilter($user_data->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $user_data->exchangeArray($form->getData());
        
        $this->table->register($user_data); 

        // Redirect to some other page
        return $this->redirect()->toRoute('authentication' , ['action'  => 'registersuccess']); 
    }

    public  function logoutAction(){
        
        $session = new Container();
        unset($session->isLogin);
        unset($session->username);

        // Redirect to some other page
        return $this->redirect()->toRoute('application');
    }

}