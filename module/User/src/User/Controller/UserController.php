<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use User\Model\User;
use User\Form\UserForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
	protected $userTable;

    public function setAndGetTitle()
    {
        $title = 'Usuários';
        $headTitle = $this->getSm()->get('viewhelpermanager')->get('HeadTitle');
        $headTitle($title);
        return $title;
    }

    public function indexAction()
    {
        /*
        $partialLoop = $this->getSm()->get('viewhelpermanager')->get('PartialLoop');
        $partialLoop->setObjectKey('user');
        */
        $urlAdd = $this->url()->fromRoute('user', array('action' => 'add'));
        $urlEdit = $this->url()->fromRoute('user', array('action' => 'edit'));
        $urlDelete = $this->url()->fromRoute('user', array('action' => 'delete'));
        $urlHome = $this->url()->fromRoute('home');

        $placeHolder = $this->getSm()->get('viewhelpermanager')->get('Placeholder');
        $placeHolder('url')->edit = $urlEdit;
        $placeHolder('url')->delete = $urlDelete;

        $pageAdapter = new DbSelect($this->getUserTable()->getSelect(), $this->getUserTable()->getSql());
        $paginator = new Paginator($pageAdapter);
        $paginator->setItemCountPerPage(5);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page', 1));

        return new ViewModel(
            array(
                'paginator' => $paginator,
                //'users' => $this->getUserTable()->fetchAll(),
                'title' => $this->setAndGetTitle(),
                'urlAdd' => $urlAdd,
                'urlHome' => $urlHome,
            )
        );
    }

    public function addAction()
    {
        $form = new UserForm();
        //$form->get('submit')->setAttribute('value', 'Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $user = new User();
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $user->exchangeArray($form->getData());

                $this->getUserTable()->saveUser($user);
                $this->flashMessenger()->addSuccessMessage('Usuário adicionado com sucesso.');
                //$this->flashMessenger()->addErrorMessage('Ocorreu um erro ao adicionar o usuário.');

                // Redirect to list of users
                return $this->redirect()->toRoute('user');
            }
        }

        return array(
            'form' => $form,
            'title' => $this->setAndGetTitle(),
        );
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', null);
        if (is_null($id)) {
            return $this->redirect()->toRoute('user', array('action'=>'add'));
        }

        $user = $this->getUserTable()->getUser($id);

        $form = new UserForm();
        $form->bind($user);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getUserTable()->saveUser($user);

                $this->flashMessenger()->addSuccessMessage('Usuário alterado com sucesso.');

                // Redirect to list of albums
                return $this->redirect()->toRoute('user');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
            'title' => $this->setAndGetTitle(),
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', null);
        if (is_null($id)) {
            return $this->redirect()->toRoute('user');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'Cancelar');
            if ($del == 'Confirmar') {
                $id = (int) $request->getPost()->get('id');
                $this->getUserTable()->deleteUser($id);

                $this->flashMessenger()->addSuccessMessage('Usuário removido com sucesso.');
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('user');
        }

        return array(
            'id' => $id,
            'user' => $this->getUserTable()->getUser($id),
            'title' => $this->setAndGetTitle(),
        );
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('User\Model\UserTable');
        }
        return $this->userTable;
    }

    public function getSm()
    {
        return $this->getEvent()->getApplication()->getServiceManager();
    }

}
