<?php  
namespace Authentication\Form;  
use Zend\Form\Form;  

class LoginForm extends Form {
   
   public function __construct($name = null) { 
      parent::__construct('authentication');  
      
        
      $this->add(array( 
         'name' => 'username', 
         'type' => 'Text', 
         'options' => array( 
            'label' => 'Username', 
         ), 
      ));  

      $this->add(array( 
        'name' => 'password', 
        'type' => 'password', 
        'options' => array( 
           'label' => 'Password', 
        ), 
     ));  

      $this->add(array( 
         'name' => 'submit', 
         'type' => 'Submit', 
         'attributes' => array( 
            'value' => 'Go', 
            'id' => 'submitbutton', 
         ), 
      )); 
   } 
}