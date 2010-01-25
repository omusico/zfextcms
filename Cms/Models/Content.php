<?php
class Cms_Models_Content {
    private $_vocabulary;
    private $_voct;
    private $_nodetax;
    private $_node;
    private $_file;
    private $_nfile;

    public function __construct() {
        $this->_vocabulary = new Cms_Models_Vocabulary();
        $this->_voct = new Cms_Models_VocabularyContentType();
        $this->_nodetax = new Cms_Models_NodeTaxonomy();
        $this->_node = new Cms_Models_Node();
        $this->_file = new Cms_Models_File();
        $this->_nfile = new Cms_Models_NodeFile();
    }

    public function delete( $id ) {
        try {
            if( empty( $id )) {
                return false;
            }
            $row = $this->_node->find( $id )->current();
            if( !count( $row )) {
                return false;
            }
            $row->delete();

            $row = $this->_nodetax->fetchRow(
                $this->_nodetax->select()->where( 'node_id = ?', $id )
            );
            $row->delete();

            $row = $this->_nfile->fetchRow(
                $this->_nfile->select()->where( 'node_id = ?', $id )
            );
            $row->delete();

            return true;

        }catch( Exception $e ) {
            throw new Easytech_Exception( $e );
        }
    }

    public function findContent( $key, $type, $rowset = true ) {
        $query = $this->_node->select();
        if( ! empty( $key )) {
            $query->where( "( title like ? ", '%'. $key .'%')
                ->orWhere( "description like ? )", '%'. $key .'%');
        }

        if( !empty( $type ) ) {
            $query->where( 'content_type_id = ?', $type );
        }
        $query->where( "locked = 'F' " );

        if( $rowset == false ) {
            return $query;
        }
        return $this->_node->fetchAll( $query );


    }

    public function find( $nid ) {
        if( empty ( $nid )) {
            return array();
        }

        $row = $this->_node->find( $nid )->current();
        if( !count( $row ) ) {
            return array();
        }

        $result['hdr'] = $row->toArray();
        $result['files'] = $this->_nfile->getFiles( $nid );
        $result['vocabulary'] = $this->getTaxonomies( $nid );
        return $result;
    }

    public function getTaxonomies( $nid ) {
        if( empty( $nid ) ) {
            return array();
        }
        $rowset = $this->_nodetax->getAdapter()->fetchAll(
            $this->_nodetax->getAdapter()->select()
            ->from(
                array( 'nt' => 'cms_node_taxonomy' ),
                array( 'tid' => 'nt.taxonomy_id' )
            )
            ->join(
                array( 'ta' => 'cms_taxonomy' ),
                'ta.taxonomy_id = nt.taxonomy_id',
                array('*')
            )
            ->join(
                array( 'vo' => 'cms_vocabulary' ),
                'vo.vocabulary_id = ta.vocabulary_id',
                array('choice')
            )
            ->where( 'nt.node_id = ?', $nid )
        );
        $result = array();
        foreach( $rowset as $row ) {

            if( $row['choice'] == 'multi' ) {
                if( !isset( $count[$row['vocabulary_id']] )){
                    $count[$row['vocabulary_id']] = 0;
                }
                $result[$row['vocabulary_id']][$count[$row['vocabulary_id']]] = $row;
                $result[$row['vocabulary_id']][$count[$row['vocabulary_id']]]['v_title'] = $this->_vocabulary->getName( $row['vocabulary_id'] );
                $count[$row['vocabulary_id']] ++;
            }else {
                $result[$row['vocabulary_id']] = $row;
                $result[$row['vocabulary_id']]['v_title'] = $this->_vocabulary->getName( $row['vocabulary_id'] );
            }
        }
        return $result;
    }

    public function create( $form ) {
        $data = $form->getValues();
        // Guardamos el archvio
        $this->_fileInfo = $form->node_image->getFileInfo();
        $fid = $this->_file->save( $this->_fileInfo['node_image'] );

        // Los enters los reemplazamos por <br/>

        $data['description'] = ereg_replace( "\n", '<br/>', $data['description'] );
        // Guardamos el nodo
        $nid = $this->_node->save( $data );

        // Asociamos el file al node
        $this->_nfile = $this->_nfile->save(
            array( 'node_id' => $nid, 'file_id' =>$fid )
        );

        // guardamos las taxonomias asociaads al nodo
        $vids = $this->_voct->getAll( $data['content_type_id'] );
        foreach( $vids as $voc ) {
            if( !empty( $data[ 'taxonomy_' . $voc->vocabulary_id ]) ) {
                if( is_array( $data[ 'taxonomy_' . $voc->vocabulary_id ] )){
                    foreach( $data[ 'taxonomy_' . $voc->vocabulary_id ] as $r ) {
                        $row = $this->_nodetax->createRow();
                        $row->taxonomy_id = $r;
                        $row->node_id = $nid;
                        $row->save();
                    }

                } else {
                    $row = $this->_nodetax->createRow();
                    $row->taxonomy_id = $data[ 'taxonomy_' . $voc->vocabulary_id ];
                    $row->node_id = $nid;
                    $row->save();
                }
            }
        }
        return true;
    }

    public function update( $form, $nid ) {
        $data = $form->getValues();
        // Guardamos el archvio
        $fid = NULL;
        $this->_fileInfo = $form->node_image->getFileInfo();
        if( !empty( $this->_fileInfo['node_image']['name'] )) {
            $this->_fileInfo = $form->node_image->getFileInfo();
            $fid = $this->_file->save( $this->_fileInfo['node_image'] );
        }
        // Los enters los reemplazamos por <br/>

        $data['description'] = ereg_replace( "\n", '<br/>', $data['description'] );
        $data['node_id'] = $nid;

        // Guardamos el nodo
        $this->_node->save( $data );


        // Asociamos el file al node
        if( !empty ( $fid ) ) {
            $this->_nfile->delete( $nid );
            $this->_nfile = $this->_nfile->save(
                array( 'node_id' => $nid, 'file_id' =>$fid )
            );
        }

        // guardamos las taxonomias asociaads al nodo
        $vids = $this->_voct->getAll( $data['content_type_id'] );
        $this->_nodetax->deleteAll( $nid );
        foreach( $vids as $voc ) {
            if( !empty( $data[ 'taxonomy_' . $voc->vocabulary_id ]) ) {
                if( is_array( $data[ 'taxonomy_' . $voc->vocabulary_id ] )){
                    foreach( $data[ 'taxonomy_' . $voc->vocabulary_id ] as $r ) {
                        $row = $this->_nodetax->createRow();
                        $row->taxonomy_id = $r;
                        $row->node_id = $nid;
                        $row->save();
                    }
                } else {
                    $row = $this->_nodetax->createRow();
                    $row->taxonomy_id = $data[ 'taxonomy_' . $voc->vocabulary_id ];
                    $row->node_id = $nid;
                    $row->save();
                }
            }
        }
        return true;
    }
}