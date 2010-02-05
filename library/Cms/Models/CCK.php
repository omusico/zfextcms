<?php
class Cms_Models_CCK extends Easytech_Db_Table
{
	protected $_name = 'cms_cck';

	public function getQueryList( $ctypeId )
	{
        if( empty( $ctypeId )) {
            throw new Easytech_Exception( 'Necesita pasar un content Type ' );
        }
		return $this->getAdapter()->select()
            ->from( array( 'cck' => 'cms_cck' ), array( '*' )  )
            ->join( array( 'ccft' => 'cms_cck_field_type' ), 'ccft.cck_field_type_id = cck.field_type_id', array( 'type_value', 'size' ) )
            ->where('content_type_id =? ', $ctypeId );
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
	
}