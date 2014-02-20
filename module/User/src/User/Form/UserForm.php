<?php
namespace User\Form;

use Zend\Form\Form;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('user');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Usuário para acesso',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Senha para acesso',
            ),
        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Nome completo',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Endereço de e-mail',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'class' => 'btn btn-success',
                'value' => 'Adicionar',
                'id' => 'submitbutton',
            ),
        ));

        $this->add(array(
            'name' => 'reset',
            'attributes' => array(
                'type'  => 'reset',
                'class' => 'btn btn-default',
                'value' => 'Limpar',
                'id' => 'resetbutton',
            ),
        ));

    }
}