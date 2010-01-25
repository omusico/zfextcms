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
            'label'      => 'Titulo:',
            'required'   => true,
            'filters'    => array(),
            'validators' => array()
        ));

		$this->addElement('text', 'parent_id', array(
            'label'      => 'ParentId:',
            'required'   => false,
            'filters'    => array(),
            'validators' => array()
        ));
                
        // Add the submit button
        $this->addElement( new Easytech_Form_Element_Toolbar('Guardar' ) );
    }
}

