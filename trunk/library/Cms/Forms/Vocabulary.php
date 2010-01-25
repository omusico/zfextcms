<?php
class Cms_Forms_Vocabulary extends Easytech_Form
{
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

		$this->addElement('text', 'title', array(
            'label'      => 'Titulo:',
            'required'   => true
        ));

		$this->addElement('select', 'choice', array(
            'label'      => 'Seleccion:',
            'required'   => true
        ));
        $this->choice->setMultiOptions( array( 'select'=> 'simple','multi' => 'Multiple' ));

        $this->addElement('checkbox', 'require', array(
            'label'      => 'Requerido:'
        ));

        $ctype = new Cms_Models_ContentType();
        foreach( $ctype->getAll() as $ctype){
	        $this->addElement('checkbox', "vocabulary_ctype".$ctype->content_type_id, array(
	            'label'      => $ctype->title . ":",
	            'required'   => false
	        ));
        }
       // Add the submit button
        $this->addElement( new Easytech_Form_Element_Toolbar('Guardar' ) );
    }

}