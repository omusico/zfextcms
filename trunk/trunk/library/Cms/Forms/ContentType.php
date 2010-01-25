<?php
class Cms_Forms_ContentType extends Easytech_Form
{
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        
		$this->addElement('text', 'title', array(
            'label'      => 'Titulo:',
            'required'   => true,
            'filters'    => array(),
            'validators' => array()
        ));
        // Add the submit button
        $this->addElement( new Easytech_Form_Element_Toolbar('Guardar' ) );
    }
}

