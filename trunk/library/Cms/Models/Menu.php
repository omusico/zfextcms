<?php
class Cms_Models_Menu extends Easytech_Db_Table
{
	protected $_name = 'sys_menu';

	public function getQueryList()
	{
		return $this->select()->order('menu_id DESC');
	}
	
	public function getItems( $menuType )
	{
		if( empty( $menuType )) {
			return array();
		}
		
		$rowset = $this->fetchAll( 
			$this->select()
			->where( 'type = ?', $menuType )
			->order('priority')
		);

		if( count($rowset)){
			return $rowset->toArray();
		}
		return array();
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