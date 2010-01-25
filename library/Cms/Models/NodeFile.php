<?php
class Cms_Models_NodeFile extends Easytech_Db_Table
{
	protected $_name = 'cms_node_file';
	protected $_primary = array('node_id', 'file_id');
	
	public function save( $params )
	{
		$row = $this->createRow();
		$row->setFromArray( $params );
		return $row->save();
	}
	
	public function delete( $nid )
	{
		if( empty( $nid )) {
			return array();
		}
		return parent::delete( 'node_id = ' . $nid );
	}

	public function getFiles( $nid )
	{
		if( empty( $nid )) {
			return array();
		}
		
		$rowset =  $this->getAdapter()->fetchAll(
			$this->getAdapter()->select()
			->from(array( 'nf' => $this->_name, array()) )
			->join(
				array('f'=>'cms_file'),
				'f.file_id = nf.file_id',
				array( '*')
			)
			->where('nf.node_id =?', $nid)
		);
		if( count( $rowset)){
			return $rowset;
		}
		return array();		
	}
}