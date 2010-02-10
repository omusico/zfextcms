<?php
class Cms_Forms_Taxonomy extends Easytech_Form
{
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

		$this->addElement('hidden', 'vocabulary_id', array(
            'required'   => true,
        ));        
        
		$this->addElement('text', 'title', array(
            'label'      => 'Termino: (puede ingresar varios seprandolos por ,)',
            'required'   => true,
            'class'     => 'sf'
        ));

		$this->addElement('select', 'parent_id', array(
            'label'      => 'Vocabulario Padre:',
            'required'   => false,
            'class'     => 'sf'
        ));

        $this->parent_id->setRegisterInArrayValidator( false );
        // Add the submit button
        $this->addElement( new Easytech_Form_Element_Toolbar( 'Guardar' ));
    }
}

