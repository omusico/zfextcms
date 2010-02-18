<?php
class Cms_Models_Node extends Easytech_Db_Table {
    protected $_name = 'cms_node';
    private $_letters = array(
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
        'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
    );
    public function getRelations( $tid ) {
        if( !is_array( $tid )){
            $tid = array( $tid );
        }
        
        $rowset = $this->getAdapter()->fetchAll(
            $this->getAdapter()
            ->select()
            ->from( array( 'nt' => 'cms_node_taxonomy' ), array() )
            ->join( array( 'no' => 'cms_node' ), 'no.node_id = nt.node_id', array('*') )
            ->where( "published='1'" )
            ->where( "locked = 'F'")
            ->where( "nt.taxonomy_id in (".implode( ',',$tid ) .")" )
            ->group( "no.node_id" )
            ->order( "no.sticky" )
        );

        if( count( $rowset )) {
            return $rowset;
        }
        return array();

    }

    public function getActiveInArray( $ctid = 0)
	{
		$query = $this->select();
		if( $ctid != 0 ) {
			$query->where( 'content_type_id = ?', $ctid );
		}
		$query->order('title DESC');
		$rowset = $this->fetchAll(
			$query
		);
		$res = array();
		foreach( $rowset as $row) {
			$res[$row->node_id] = $row->title;
		}
		return $res;
	}

    public function getContentFromTid( $tid ) {
        if( empty( $tid )) {
            return array();
        }

        $rowset = $this->getAdapter()->fetchAll(
            $this->getAdapter()->select()
            ->from( array( 'tn' => 'cms_node_taxonomy' ) )
            ->join( array( 'no' => $this->_name ), 'no.node_id = tn.node_id', array( '*' ) )
            ->where( "tn.taxonomy_id = ?", $tid )
            ->where( "no.published = '1' " )
            ->where( "no.locked = 'F' " )
        );
        return $rowset;
    }

    public function load( $nid ) {
        $content = new Cms_Models_Content();
        return $content->find( $nid );
    }

    public function getAll( $where ) {
        return $this->fetchAll( "locked = 'F'")->toArray();
    }


    public function getQueryList() {
        $query = $this->getAdapter()->select()
            ->from( array( 'no' => $this->_name ), array('*')  )
            ->join(
            array( 'ty' => 'cms_content_type'),
            'ty.content_type_id = no.content_type_id',
            array('type' => 'ty.title')
        );
        return $query;
    }

    public function save( $params ) {
        if( empty ( $params['node_id'])) {
            $row = $this->createRow();
        } else {
            $row = $this->fetchRow(
                $this->select()
                ->where('node_id = ?', $params['node_id'])
            );
            if( ! count( $row )) {
                $row = $this->createRow();
            }
        }
        $row->setFromArray( $params );
        return (int)$row->save();

    }

    public function getNodesPageFront( $type ) {
        if( empty( $type )) {
            return array();
        }
        $query = $this->select()
            ->where( 'locked = ?', 'F'  )
            ->where( 'content_type_id = ?', $type )
            ->where( 'published = ?', 'T')
            ->where( 'page_front = ?', 'T' );

        $rowset = $this->fetchAll( $query );
        if( count( $rowset ) ) {
            return $rowset->toArray();
        }
        return array();
    }
}
