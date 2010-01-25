<?php
class CmsPanel_TaxonomyController extends Easytech_Controller_SecureAction {
    public function preDispatch() {
        parent::preDispatch();
        if(! $this->_hasParam('vid')) {
            return $this->_redirect( '/CmsPanel/vocabulary/list/' );
        }
    }
    public function listAction() {
        $taxonomy = new Cms_Models_Taxonomy();
        $this->view->paginator = new Easytech_Paginator(
            $taxonomy->getQueryList( $this->_getParam('vid') ), $this->_page
        );
        $this->view->vid =  $this->_getParam('vid');
    }

    public function createAction() {
        try {
            if( !$this->_hasParam( "vid" ) ) {
                $this->addError( 'Seleccione el vocabulario' );
                return $this->_redirect( '/CmsPanel/vocabulary/list' );
            }
            $form = new Cms_Forms_Taxonomy();
            $form->vocabulary_id->setValue( $this->_getParam('vid'));
            if ( $this->getRequest()->isPost() ) {
                if( $form->isValid($this->getRequest()->getParams()) ) {
                    $taxonomy = new Cms_Models_Taxonomy();
                    $taxonomy->save($form->getValues());
                    $this->addSuccess("Elemento guardado con exito");
                    return $this->_redirect('/CmsPanel/taxonomy/list/vid/' . $this->_getParam('vid'));
                }
                $this->addError("El formulario contiene errores");
            }
            $this->view->form = $form;
        }catch(Exception $e  ){
            throw new Easytech_Exception( "No se pudo guardar " . $e->getMessage());
        }
    }

    public function updateAction() {
        try {

            if( !$this->_hasParam( "vid" ) ) {
                $this->addError( 'Seleccione el vocabulario' );
                return $this->_redirect( '/CmsPanel/vocabulary/list' );
            }

            if( !$this->_hasParam('tid')) {
                $this->addError("Falta seleccionar la taxonomia");
                return $this->_redirect('/CmsPanel/taxonomy/list/vid/'. $this->_getParam( "vid" ));
            }

            $form = new Cms_Forms_Taxonomy();
            $form->vocabulary_id->setValue( $this->_getParam('vid'));
            $taxonomy = new Cms_Models_Taxonomy();

            if ( $this->getRequest()->isPost() ) {
                if( $form->isValid($this->getRequest()->getParams()) ) {
                    if( $taxonomy->save( $form->getValues(), $this->_getParam( "tid" ) ) ) {
                        $this->addSuccess("Elemento guardado con exito");
                        return $this->_redirect('/CmsPanel/taxonomy/list/vid/'. $this->_getParam( "vid" ));
                    }
                }
                $this->addError("El formulario contiene errores");
                $form->populate( $form->getValues());
            }else {
                $tax = $taxonomy->find( $this->_getParam( 'tid' ))->current();
                $form->populate( $tax->toArray() );
            }
            $this->view->form = $form;
        }catch(Exception $e  ){
            throw new Easytech_Exception( "No se pudo guardar " . $e->getMessage());
        }

    }

    public function deleteAction() {


    }

    public function unDeleteAction() {}

}