<?php
class Cms_View_Helper_Taxonomy extends Zend_View_Helper_Abstract {
    private $_vocabulary;

    public function taxonomy() {
        $this->_config = Zend_Registry::get('config');
        $this->_vocabulary = new Cms_Models_Vocabulary();
        return $this;
    }

    public function getTaxonomyToString( $taxs ) {
        $str='';
        foreach( $taxs as $t ) {
            if( !empty( $str) ){
                $str .= ', ';
            }
            $str .= $t['title'];
        }
        return $str;
    }
    public function tree( $vocabulary ) {
        return $this->_vocabulary->getTree( $vocabulary );
    }

    public function getUrl( $tid, $name, $prefix ='' ) {
        return $this->_config->app->url . "/"
            . $prefix
            . $this->view->seo()->cleanKey( $name )
            . '-'
            . $tid
            . '.html';
    }

}