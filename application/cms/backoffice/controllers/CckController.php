<?php
class CmsPanel_CckController extends Easytech_Controller_SecureAction {

    public function preDispatch(){
        if( !$this->_hasParam( 'cid' )) {
            $this->addError( 'Necesita seleccionar un tipo de contenido antes' );
            $this->_redirect( '/CmsPanel/contenttype/list/' );
            die();
        }
        parent::preDispatch();
        $this->view->ctype = $this->_getParam( 'cid' );
    }
    public function listAction() {
        $vocabulary = new Cms_Models_Vocabulary();
        $this->view->vocabularies = $vocabulary->fetchAll();

        $contentType = new Cms_Models_ContentType();
        $this->view->contentTypes = $contentType->fetchAll();

        $ctype = new Cms_Models_CCK();
        $this->view->paginator = new Easytech_Paginator(
            $ctype->getQueryList( $this->_getParam( 'cid' )), $this->_page
        );
    }

    public function sortableAction(){
        try{
            $orders = $this->_getParam('order');
            $o = 50;
            $cck = new Cms_Models_CCK();
            foreach( $orders as $order ) {
                $row = $cck->find( $order )->current();
                $row->field_order = $o;
                $row->save();
                $o ++;
            }
            $this->addSuccess( "Los datos fueron actualizados con exito." );
        }catch( Easytech_Exception $e ) {
            $this->addError( $e->getMessage() );
        }
        $this->_redirect( '/CmsPanel/cck/list/cid/' . $this->_getParam('cid') );
    }

    public function relationctypeAction(){
        $cckType = new Cms_Models_CCKContentType();
        $row = $cckType->fetchRow(
            $cckType->select()
            ->where('cck_id =? ', $this->_getParam('cckId'))
        );
        if( !count( $row ))  {
            $row = $cckType->createRow();
        }
        $row->content_type_id = $this->_getParam('ctypeid');
        $row->cck_id = $this->_getParam('cckId');
        $row->save();
        exit;
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
                    $bind['field_order'] = 50;
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