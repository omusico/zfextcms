<?php
/**
 * Modelo para la asignacion de metadata para las paginas
 */
class Cms_Models_Seometadata extends Zend_Db_Table_Abstract {
    protected $_name = 'seo_metadata';
    protected $_primary = 'seo_id';

    /**
     * Guarda o Actualiza un registro
     *
     * @param mixed $id Puede ser un string o un entero
     * @param mixed $data Array con Indices
     *  No son obligatorios todos pero si por lo menos uno de los tres
     * @return boolean true|false Tuve exito o no la operacion
     */
    public function save( $id, $data ) {
        try{
            if( empty( $id ) ) {
                return false;
            }

            if( empty( $data['seo_title'] )
                && empty( $data['seo_keyword'] )
                && empty( $data['seo_description'] ) )  {

                return false;
            }

            $row = $this->find( $id )->current();
            if( ! count( $row ) ){
                $row = $this->createRow();
                $row->seo_id = $id;
            }
            $row->setFromArray( $data );
            return $row->save();
        } catch ( Exception $e ) {
            return false;
            
        }
    }

    /**
     * Devuelve los metadatas a partir de un id.
     * @param mixed $id
     */
    public function getMetadata( $id ) {
        if( empty( $id ) ){
            return array();
        }

        $row = $this->find( $id )->current();
        if( count( $row )) {
            return $row->toArray();
        }
        return array();

    }

    /**
     * Borra los metadatas a prtir de un id
     * @param mixed $id
     */
    public function delete( $id ){
        

    }

    /**
     * Inicializa el plugin
     * Si la tabla donde se guardaran los datos no existe la crea.
     */
    protected function _setup() {
    //    $this->_createTable();
        parent::_setup();
    }


    /**
     * Scripts para crear la tabla de este modelo
     */
    private function _createTable() {
        $query = "
            CREATE TABLE `seo_metadata` (
                `metadata_id` int(11) NOT NULL auto_increment,
                `seo_id` char(60) NOT NULL,
                `seo_title` varchar(255) NOT NULL,
                `seo_description` varchar(255) NOT NULL,
                `seo_keyword` varchar(255) NOT NULL,
                PRIMARY KEY  (`metadata_id`),
                KEY `id_idx` (`id`)
            )";
        $this->getAdapter()->query( $query );
    }
}