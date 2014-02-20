<?php
namespace User\Form;

use Zend\Form\Form;

class SearchForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('user');

        $this->setAttribute('method', 'post');

        $name = strtolower($name);

        //echo $this->formButton('formButton');

        $this->add(array(
            'name' => 'search',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
                'placeholder' => "Encontrar em {$name}...",
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

    }
}