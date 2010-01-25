<?php
class CmsPanel_ContenttypeController extends Easytech_Controller_SecureAction {
    public function preDispatch() {
        parent::preDispatch();
    }

    public function listAction() {
        $ctype = new Cms_Models_ContentType();
        $this->view->paginator = new Easytech_Paginator(
            $ctype->getQueryList(), $this->_page
        );
    }

    public function createAction() {
        try {
            $form = new Cms_Forms_ContentType();
            if ( $this->getRequest()->isPost() ) {
                if( $form->isValid($this->getRequest()->getParams()) ) {
                    $ctype = new Cms_Models_ContentType();
                    $ctype->save( $form->getValues() );
                    $this->addSuccess("Elemento guardado con exito");
                    return $this->_redirect('/CmsPanel/contenttype/list/');
                }
                $this->addError("El formulario contiene errores");
            }
            $this->view->form = $form;
        }catch(Exception $e  ){
            throw new Easytech_Exception( "No se pudo guardar " . $e->getMessage());
        }
    }

    public function deleteAction() {}

    public function unDeleteAction() {}

}