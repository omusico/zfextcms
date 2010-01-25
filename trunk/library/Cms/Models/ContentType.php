<?php
class Cms_Models_ContentType extends Easytech_Db_Table
{
	protected $_name = 'cms_content_type';

	public function getQueryList()
	{
		return $this->select()->order('content_type_id DESC');
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
	
	public function save( $params )
	{
		$row = $this->fetchRow(
			$this->select()
			->where('title = ?', $params['title'])
		);
		if( ! count( $row )) {
			$row = $this->createRow();
		}
		$row->setFromArray( $params );

		return $row->save();
		
	}
}