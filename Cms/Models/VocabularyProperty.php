<?php
class Cms_Models_File extends Easytech_Db_Table
{
	protected $_name = 'cms_file';
			
	public function getQueryList()
	{
		$query = $this->getAdapter()->select()
			->from( array( 'fi' => $this->_name ), array('*')  );
		return $query;
	}
	
	public function save( $params )
	{
		$row = $this->createRow();
		$row->setFromArray( $params );
		return $row->save();
	}
}