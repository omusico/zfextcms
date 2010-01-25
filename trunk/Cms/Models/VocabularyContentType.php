<?php
class Cms_Models_VocabularyContentType extends Easytech_Db_Table
{
	protected $_name = 'cms_vocabulary_content_type';
			
	public function getQueryList()
	{
		$query = $this->getAdapter()->select()
			->from( array( 'fi' => $this->_name ), array('*')  );
		return $query;
	}
	
	public function getAll( $cyid )
	{
		return $this->fetchAll(
			$this->select()
			->where('content_type_id =?', $cyid)
		);
	}

	public function getAllByNid( $nid )
	{
		if( empty( $nid  )) {
			return $nid;
		}
		
		$rowset = $this->fetchAll(
			$this->select()
			->where('node_id= ?', $nid)
		);
		
		if( count( $rowset )) {
			$rowset->toArray();
		}
		
		return array();
		
	}
	
	public function save( $params )
	{
		$row = $this->createRow();
		$row->setFromArray( $params );
		return $row->save();
	}
}