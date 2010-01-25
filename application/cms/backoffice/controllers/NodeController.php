<?php
class CmsPanel_NodeController extends Easytech_Controller_SecureAction {
    public function listAction() {
        $node = new Cms_Models_Node();
        $this->view->paginator = new Easytech_Paginator(
            $node->getQueryList(), $this->_page
        );
    }

    public function typelistAction() {
        $ctype = new Cms_Models_ContentType();
        $this->view->contentType = $ctype->fetchAll();
    }

    public function createAction() {
        try {
            if( !$this->_hasParam('cyid')) {
                $this->addWarning( 'Seleccione un tipo de contenido' );
                return $this->_redirect('/CmsPanel/node/typelist/');
            }
            $form = new Cms_Forms_Node( NULL, $this->_getParam('cyid'));
            $form->content_type_id->setValue( $this->_getParam( 'cyid' ) );
            if ( $this->getRequest()->isPost() ) {
                
                if( $form->isValid($this->getRequest()->getParams()) ) {
                    $content = new Cms_Models_Content();
                    if( $content->create( $form ) ) {
                        $this->addSuccess("Elemento guardado con exito");
                        return $this->_redirect('/CmsPanel/node/list');
                    }
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
            if( !$this->_hasParam('nid')) {
                $this->addError("Falta seleccionar el contenido");
                return $this->_redirect('/CmsPanel/node/list');
            }
            
            $form = new Cms_Forms_Node( NULL, $this->_getParam('cyid'));
            $content = new Cms_Models_Content();
            if ( $this->getRequest()->isPost() ) {
                if( $form->isValid($this->getRequest()->getParams()) ) {
                    if( $content->update( $form, $this->_getParam( "nid" ) ) ) {
                        $this->addSuccess("Elemento guardado con exito");
                        return $this->_redirect('/CmsPanel/node/list');
                    }
                }
                $this->addError("El formulario contiene errores");
                $form->populate( $form->getValues());
            }else {
                $node = $content ->find( $this->_getParam( 'nid' ));
                //print_r($node);exit;
                $url = $this->view->images()->getUrl( $node['files'][0]['name'], '100x100' );
                $form->preview->setImageValue( $url );

                $form->content_type_id->setValue( $node['hdr']['content_type_id']);
                $form->populateWithNode( $node );
            }
            $this->view->form = $form;
        }catch(Exception $e  ){
            throw new Easytech_Exception( "No se pudo guardar " . $e->getMessage());
        }
    }

    public function deleteAction() {
        if( !$this->_hasParam( 'id' ) ) {
            $this->addError( "No se encontro el contenido" );
            $this->_redirect( '/CmsPanel/node/list' );
        }
        $content = new Cms_Models_Content( );
        if( $content->delete( $this->_getParam( 'id' ) ) ) {
            $this->addSuccess( "Contenido eliminado con exito" );
        } else {
            $this->addError( "No se encontro el contenido" );
        }
        $this->_redirect( '/CmsPanel/node/list' );

    }
}