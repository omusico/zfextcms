<?php
class CmsPanel_VocabularyController extends Easytech_Controller_SecureAction {
    public function listAction() {
        $vocabulary = new Cms_Models_Vocabulary();
        $this->view->paginator = new Easytech_Paginator(
            $vocabulary->getQueryList(), $this->_page
        );
    }

    public function createAction() {
        try {
            $form = new Cms_Forms_Vocabulary();
            if ( $this->getRequest()->isPost() ) {
                if( $form->isValid($this->getRequest()->getParams()) ) {
                    $vocabulary = new Cms_Models_Vocabulary();
                    $vid = $vocabulary->save($form->getValues());
                    $this->addSuccess("Elemento guardado con exito");
                    return $this->_redirect('/CmsPanel/vocabulary/list');
                }
                $this->addError("El formulario contiene errores");
                $form->populate( $form->getValues());
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

            $form = new Cms_Forms_Vocabulary();
            $vocabulary = new Cms_Models_Vocabulary();

            if ( $this->getRequest()->isPost() ) {
                if( $form->isValid($this->getRequest()->getParams()) ) {
                    if( $vocabulary->save( $form->getValues(), $this->_getParam( "vid" ) ) ) {
                        $this->addSuccess("Elemento guardado con exito");
                        return $this->_redirect('/CmsPanel/vocabulary/list/' );
                    }
                }
                $this->addError("El formulario contiene errores");
                $form->populate( $form->getValues());
            }else {
                $voc = $vocabulary->find( $this->_getParam( 'vid' ))->current();
                $form->populate( $voc->toArray() );
            }
            $this->view->form = $form;
        }catch(Exception $e  ){
            throw new Easytech_Exception( "No se pudo guardar " . $e->getMessage());
        }
    }

    public function deleteAction() {
        if( !$this->_hasParam( 'id' ) ) {
            $this->addError( "No se encontro el vocabulario" );
            $this->_redirect( '/CmsPanel/vocabulary/list' );
        }
        $vocabulary = new Cms_Models_Vocabulary( );
        if( $vocabulary->delete( $this->_getParam( 'id' ) ) ) {
            $this->addSuccess( "Vocabulario eliminado con exito" );
        } else {
            $this->addError( "No se encontro el vocabulario" );
        }
        $this->_redirect( '/CmsPanel/vocabulary/list' );
    }

    public function unDeleteAction() {}

}