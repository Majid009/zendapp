<?php
namespace Album\Controller;

// Add the following import statements at the top of the file:
use Album\Form\AlbumForm;
use Album\Model\Album;

// Add the following import:
use Album\Model\AlbumTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class AlbumController extends AbstractActionController
{
    // Add this property:
    private $table;


    // Add this constructor:
    public function __construct(AlbumTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
         // getting session data 
         $session =  new Container();
        
         if(!isset($session->isLogin)){
            return $this->redirect()->toRoute('application' , ['action' => 'accessdenied']);
          }
            return new ViewModel([
                'albums' => $this->table->fetchAll(),
            ]);
        
    }

    public function addAction()
    {
        // getting session data 
        $session =  new Container();

        if(!isset($session->isLogin)){
            return $this->redirect()->toRoute('application' , ['action' => 'accessdenied']);
        } 

        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $album->exchangeArray($form->getData());
        $this->table->saveAlbum($album);
        return $this->redirect()->toRoute('album');  
    }

   
    public function editAction()
    {
        // getting session data 
        $session =  new Container();

        if(!isset($session->isLogin)){
            return $this->redirect()->toRoute('application' , ['action' => 'accessdenied']);
        } 

        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('album', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $album = $this->table->getAlbum($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('album', ['action' => 'index']);
        }

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveAlbum($album);

        // Redirect to album list
        return $this->redirect()->toRoute('album', ['action' => 'index']);
    }

    public function deleteAction()
    {
        // getting session data 
        $session =  new Container();

        if(!isset($session->isLogin)){
            return $this->redirect()->toRoute('application' , ['action' => 'accessdenied']);
        } 

        $id =  $this->params()->fromRoute('id');
        $this->table->deleteAlbum($id);
        return $this->redirect()->toRoute('album');
    }

    public function viewAction(){

        // getting session data 
        $session =  new Container();

        if(!isset($session->isLogin)){
            return $this->redirect()->toRoute('application' , ['action' => 'accessdenied']);
        } 
        
        $id = $this->params()->fromRoute('id');
        if(isset($id)){
           $album = $this->table->getAlbum($id);
           return new viewModel(['album'=> $album ]);
        }

        
    }
}