<?php
class CmsPanel_CckController extends Easytech_Controller_SecureAction {

    public function preDispatch(){
        parent::preDispatch();
        $this->view->ctype = $this->_getParam( 'cid' );
    }
    public function listAction() {
        if( !$this->_hasParam( 'cid' )) {
            $this->addError( 'Necesita seleccionar un tipo de contenido antes' );
            $this->_redirect( '/CmsPanel/contenttype/list/' );
            die();
        }
        $vocabulary = new Cms_Models_Vocabulary();
        $this->view->vocabularies = $vocabulary->fetchAll();
        $ctype = new Cms_Models_CCK();
        $this->view->paginator = new Easytech_Paginator(
            $ctype->getQueryList( $this->_getParam( 'cid' )), $this->_page
        );
    }

    public function relationAction(){
        $cckVocabulary = new Cms_Models_CCKVocabulary();
        $row = $cckVocabulary->fetchRow(
            $cckVocabulary->select()
            ->where('cck_id =? ', $this->_getParam('cckId'))
        );
        if( !count( $row ))  {
            $row = $cckVocabulary->createRow();
            $row->vocabulary_id = $this->_getParam('vid');
            $row->cck_id = $this->_getParam('cckId');
        } else {
            $row->vocabulary_id = $this->_getParam('vid');
            $row->cck_id = $this->_getParam('cckId');
        }
        $row->save();
        exit;
    }

    public function createAction() {
        
        if( !$this->_hasParam( 'cid' )) {
            $this->addError( 'Necesita seleccionar un tipo de contenido antes' );
            $this->_redirect( '/CmsPanel/contenttype/list/cid/' . $this->_getParam( 'cid' )  );
            die();
        }
        $form = new Cms_Forms_CCK();
        
        if ( $this->getRequest()->isPost() ) {
            if( $form->isValid($this->getRequest()->getParams()) ) {
                $cck = new Cms_Models_CCK();
                $bind = $form->getValues();
                // CCK properties
                $cckType = new Cms_Models_CCKFieldType();
                $bind['element'] = $bind['field_type'];
                try{
                    $bind['field_type_id'] = $cckType->save( $bind );
                    unset($bind['Guardar']);
                    unset($bind['element']);
                    unset($bind['validator']);
                    unset($bind['required']);
                    unset($bind['field_type']);
                    unset($bind['cck_id']);
                    $cck->save( $bind );
                    $this->addSuccess("Elemento guardado con exito");
                    return $this->_redirect('/CmsPanel/cck/list/cid/' . $this->_getParam( 'cid' ) );
                }catch( Easytech_Exception $e ) {
                    $this->addError( $e->getMessage() );
                }
            }
            $this->addError("El formulario contiene errores");
        }else {
            $form->content_type_id->setValue( $this->_getParam( 'cid' ) );
        }
        $this->view->form = $form;

    }

    public function deleteAction() {

    }

    public function unDeleteAction() {
        
    }

}