<?php
class Cms_Models_Sitemap extends Easytech_Db_Table {
    protected $_name = 'seo_sitemap';

    public function save( $data ) {
        if( !empty( $data['hash'] ) ) {

            $row = $this->fetchRow( $this->select()->where('hash = ?', $data['hash'] ) );

            if( count( $row ) ) {
                $row->setFromArray( $data );
                return $row->save();
            }
        }

        $row = $this->createRow();
        $row->setFromArray( $data );
        return $row->save();
    }


}