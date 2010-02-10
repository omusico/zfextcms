<?php
class Cms_Models_Vocabulary extends Easytech_Db_Table {
    protected $_name = 'cms_vocabulary';

    public function getQueryList() {
        return $this->select()->order('vocabulary_id DESC');
    }

    public function getTree( $vocabulary ) {
        if( empty( $vocabulary)) {
            return array();
        }
        $rowset = $this->getAdapter()->fetchAll(
            $this->getAdapter()->select( )
            ->from(array('vo' => $this->_name ),array())
            ->join(
                array('ta'=>'cms_taxonomy'),
                'ta.vocabulary_id = vo.vocabulary_id',
                array('taxonomy_id', 'title')
            )
            ->where('vo.title =? ', $vocabulary )
            ->order( 'title' )
        );

        if( count( $rowset)) {
            return $rowset;
        }
        return array();
    }

	public function getActiveInArray( $vid = 0)
	{
		$query = $this->select();
		if( $vid != 0 ) {
			$query->where( 'vocabulary_id = ?', $vid );
		}
        $query->order( 'vocabulary_id ASC' );
		$rowset = $this->fetchAll(
			$query
		);
		$res = array();
		foreach( $rowset as $row) {
			$res[$row->vocabulary_id] = $row->title;
		}
		return $res;
	}

    public function getName( $vid ) {
        $row = $this->find( $vid )->current();
        if( count( $row )) {
            return $row->title;
        }
        return NULL;
    }

    public function getInfo($vid) {
        $tax = new Cms_Models_Taxonomy();
        $info['hdr'] = $this->find( $vid )->current();
        $info['taxonomy'] = $tax->getActiveInArray( $vid );

        return $info;
    }

    public function save( $params, $id = NULL ) {
        //var_dump( $id );exit;
        if( !empty( $id) ) {
            $row = $this->find( $id )->current();
        } else {
            $row = $this->createRow();
        }
        $row->setFromArray( $params );
        $vid = $row->save();
        if( !empty( $id )) {
            return $vid;
        }
        $ctype = new Cms_Models_ContentType();
        $vctype = new Cms_Models_VocabularyContentType();
        foreach( $ctype->getAll() as $ctype) {
            if( $params["vocabulary_ctype".$ctype->content_type_id] == 1 ) {
                $vctype->save( array(
                    'vocabulary_id' => $vid,
                    'content_type_id' => $ctype->content_type_id
                ));
            }
        }
        return $vid;
    }

    public function delete( $id ) {
        if( empty( $id )) {
            return false;
        }

        $voc = $this->find( $id )->current();

        if( !count( $voc )) {
            return false;
        }

        $taxonomy = new Cms_Models_Taxonomy();
        $nodeTaxonomy = new Cms_Models_NodeTaxonomy();
        $rowset = $taxonomy->fetchAll( 'vocabulary_id = ' . $id );
        foreach( $rowset as $row ) {
            $nodeWithTax = $nodeTaxonomy->fetchAll( 'taxonomy_id = ' . $row->taxonomy_id );
            foreach( $nodeWithTax as $nt) {
                $nt->delete();
            }
            $row->delete();
        }
        $voc->delete();
    }
}