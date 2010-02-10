<?php
class Cms_Models_CCKFieldType extends Easytech_Db_Table
{
	protected $_name = 'cms_cck_field_type';

	public function getQueryList( $ctypeId )
	{
        if( empty( $ctypeId )) {
            throw new Easytech_Exception( 'Necesita pasar un content Type ' );
        }
		return $this->getAdapter()->select()
            ->from( array( 'cck' => 'cms_cck' ), array( '*' )  )
            ->join( array( 'ccft' => 'cms_cck_field_type' ), 'ccft.cck_field_type_id = cck.field_type_id', array( '*' ) )
            ->where('content_type_id =? ', $ctypeId );
	}
	
	public function getActiveInArray()
	{
		$rowset = $this->fetchAll( 
			$this->select()
			->order('field_type DESC')
		);
		$res = array();
		foreach( $rowset as $row) {
			$res[$row->content_type_id] = $row->title;
		}
		return $res;
	}

    public function getIdFromType( $type ) {
        if( empty( $type )) {
            throw new Easytech_Exception( 'El Tipo de campo es necesario' );
        }
        $row = $this->fetchRow( $this->select()->where('element=?', $type ));
        if( count( $row )) {
            return $row->cck_field_type_id;
        }
        throw new Easytech_Exception( 'No se encontro el tipo de campo' );
        

    }
}