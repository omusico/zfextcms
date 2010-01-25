<?php
class Cms_View_Helper_Metadata extends Zend_View_Helper_Abstract {
    public function metadata() {
        return $this;
    }

    public function render( $id ) {
        if( empty( $id )){
            return false;
        }
        $seometadata = new Cms_Models_Seometadata();
        $row = $seometadata->getMetadata( $id ) ;
        
        echo '<title>' . $row['seo_title'] . '</title>';
        echo '<meta name="keywords" content="' . $row['seo_keyword'] . '" /> ';
        echo '<meta name="description" content="' . $row['seo_description'] . '" /> ';
    }
}