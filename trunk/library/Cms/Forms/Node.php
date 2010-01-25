<?php
class Cms_Forms_Node extends Easytech_Form
{
	private $_cyid;

    public function __construct( $options, $cyid )
    {
    	$this->_cyid = $cyid;
    	parent::__construct( $options );
    }
    	
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->setAttrib('enctype', 'multipart/form-data');
        
		$this->addElement('text', 'title', array(
            'label'      => 'Titulo:',
            'required'   => true
        ));
        
		$this->addElement('hidden', 'content_type_id' );
		
		
		$this->_renderTaxonomies();
		
        $this->addElement('textarea', 'description', array(
            'label'      => 'Descripcion:',
            'required'   => true
        ));

		$this->addElement('checkbox', 'published', array(
            'label'      => 'Publicado:'
        ));
        
		$this->addElement('checkbox', 'sticky', array(
            'label'      => 'Destacado:'
		));
        
		$this->addElement('checkbox', 'page_front', array(
            'label'      => 'Enviar a la home:'
        ));
		
        $this->addElement('file', 'node_image', array(
            'label'      => 'Imagen:',
            'required'   => false
        ));
        
        $this->addElement('image', 'preview', array(
            'label'      => 'Vista Previa',
            'required'   => false
        ));       
        
        $this->node_image->setDestination(
        	Zend_Registry::get('config')->imgs->node->path
        );
  
		$this->node_image->addValidator('Size', false, 80240000);
		$this->node_image->addValidator('Extension', false, 'jpg,png,gif,jpeg');
      
        // Add the submit button
        $this->addElement( new Easytech_Form_Element_Toolbar('Guardar' ) );
    }
    
    private function _renderTaxonomies()
    {
    	$vct = new Cms_Models_VocabularyContentType();
    	$voc = new Cms_Models_Vocabulary();
    	foreach( $vct->getAll( $this->_cyid ) as $v) {
    		$vocabulary = $voc->getInfo( $v->vocabulary_id );
            $taxonomyName = 'taxonomy_' . $v->vocabulary_id;
            $type = ( $vocabulary['hdr']['choice'] == 'multi' ) ? 'multiselect' : 'select';
			$this->addElement($type, $taxonomyName, array(
				'label'      => $vocabulary['hdr']['title'].':',
			    'required'   => (boolean) $vocabulary['hdr']['required']
			)); 
			$this->$taxonomyName->setMultiOptions($vocabulary['taxonomy']);
    	}
    }
}