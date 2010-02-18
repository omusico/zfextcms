<?php
class Cms_Forms_CCK extends Easytech_Form
{
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

		$this->addElement('hidden', 'cck_id' );

		$this->addElement('hidden', 'content_type_id', array(
            'required'   => true,
        ));

        
		$this->addElement('text', 'field_name', array(
            'label'      => "Nombre sin espacion, alfanumerico, solo '_' :",
            'required'   => true,
            'class' => 'sf'
        ));
        
        $this->addElement( 'text', 'field_label',
            array(
                'label' => 'Descripcion del campo',
                'class' => 'sf'
            )
        );

        $this->addElement( 'text', 'validator',
            array( 'label' => 'Validador','class' => 'sf'  )
        );

        $this->addElement( 'checkbox', 'required',
            array( 'label' => 'Requerido' )
        );

		$this->addElement('select', 'field_type', array(
            'label'      => 'Tipo de campo:',
            'required'   => true,
            'class' => 'sf'
        ));

        $types = array(
            'text'=>'text',
            'textarea'=>'textarea',
            'image'=>'image',
            'select'=>'select',
            'checkbox'=>'checkbox',
            'content_type'=>'content type'
        );

        $this->field_type->setMultiOptions( $types );
         // Add the submit button
        $this->addElement( new Easytech_Form_Element_Toolbar('Guardar' ) );
    }
}