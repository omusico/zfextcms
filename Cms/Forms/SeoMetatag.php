<?php
require_once( 'Zend/Form.php' );
class Cms_Forms_SeoMetatag extends Zend_Form {
    public function init(){
        $this->setName( "seometadata" );
        
        $this->addElement('text', 'seo_title', array(
            'label'      => 'Title:',
            'required'   => true
        ));

        $this->addElement('text', 'seo_keyword', array(
            'label'      => 'Keywords:',
            'required'   => true
        ));

        $this->addElement('text', 'seo_description', array(
            'label'      => 'Description:',
            'required'   => true
        ));

    }



}
