<?php
class Cms_Models_CCK extends Easytech_Db_Table
{
	protected $_name = 'cms_cck';

    public function cleanNode( $nid ) {
        throw new Easytech_Exception("TODAVIA NO SE CREO ESTA FUNCIONALIDAD");
    }

	public function getQueryList( $ctypeId )
	{
        if( empty( $ctypeId )) {
            throw new Easytech_Exception( 'Necesita pasar un content Type ' );
        }
		return $this->getAdapter()->select()
            ->from( array( 'cck' => 'cms_cck' ), array( '*' )  )
            ->join( array( 'ccft' => 'cms_cck_field_type' ), 'ccft.cck_field_type_id = cck.field_type_id', array( '*' ) )
            ->where('content_type_id =? ', $ctypeId )
            ->order( 'cck.field_order' );
	}

    public function geCCKByName( $name ) {
        if( empty( $name )) {
            throw new Easytech_Exception( "El nombre del campo cck esta vacio" );
        }
        $row = $this->getAdapter()->fetchRow(
            $this->getAdapter()->select()
                ->from( array( 'cck' => 'cms_cck' ), array( '*' )  )
                ->join( array( 'ccft' => 'cms_cck_field_type' ), 'ccft.cck_field_type_id = cck.field_type_id', array( '*' ) )
                ->where('cck.field_name= ?', $name )
        );
        return $row;
    }
	
	public function getAll()
	{
		return $this->fetchAll();
	}
	
	public function getActiveInArray()
	{
		$rowset = $this->fetchAll( 
			$this->select()
			->order('title DESC')
		);
		$res = array();
		foreach( $rowset as $row) {
			$res[$row->content_type_id] = $row->title;
		}
		return $res;
	}

    public function addElements( $ctid, Zend_Form $form ) {

        if( empty( $ctid )) {
            throw new Easytech_Exception( 'Falta en tipo de contendio' );
        }
        $rowset = $this->getAdapter()->fetchAll(
            $this->getAdapter()->select()
                ->from( array( 'cck' => $this->_name ), array('*') )
                ->join( array( 'ccft' => 'cms_cck_field_type'), 'ccft.cck_field_type_id = cck.field_type_id', array('*') )
                ->where( 'cck.content_type_id=?', $ctid )
        );
        
        $taxonomy = new Cms_Models_Taxonomy();
        $node = new Cms_Models_Node();
        foreach( $rowset as $row ) {
            $type = $row['element'];
            if( $row['element'] == 'content_type' ) {
                $row['element'] = 'multiselect';
                
            }
            $form->addElement(
                $row['element'],
                $row['field_name'],
                array(
                    'label' => $row['field_label'] ,
                    'class' => 'sf'
                )
            );

            $form->{$row['field_name']}->setOrder( $row['field_order'] );

            if( $type == 'content_type'){
                $r = $this->getAdapter()->fetchRow( "SELECT content_type_id FROM cms_cck_content_type WHERE cck_id='{$row['cck_id']}'" );
                if( count( $r)){
                    $re = $node->getActiveInArray( $r['content_type_id'] );
                    $form->{$row['field_name']}->setMultiOptions( $re );
                }
            }

            if( $row['element'] == 'select') {
                $voc = $this->getAdapter()->fetchRow( "SELECT vocabulary_id FROM cms_cck_vocabulary WHERE cck_id='{$row['cck_id']}'" );
                if( count( $voc)){
                    $tax = $taxonomy->getActiveInArray( $voc['vocabulary_id'] );
                    $form->{$row['field_name']}->setMultiOptions( $tax );
                }
            }
        }
        return $form;
    }

    public function save( $bind, $id = NULL ) {
        $bind['field_name'] = "cck_field_" . $bind['field_name'];
        $row = $this->fetchRow( $this->select()->where('field_name=?', $bind['field_name']) );
        if( count( $row )) {
            throw new Easytech_Exception( "El nombre de campo ya existe." );
        }
        parent::save( $bind, $id );
    }
}