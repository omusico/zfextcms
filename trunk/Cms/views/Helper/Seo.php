<?php
class Cms_View_Helper_Seo extends Zend_View_Helper_Abstract {
    
    public function seo() {
        return $this;
    }

    public function cleanKey( $str, $replace=array(), $delimiter='-' ) {
        if( !empty( $replace ) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }
}