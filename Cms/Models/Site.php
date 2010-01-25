<?php
class Cms_Models_Site extends Easytech_Db_Table
{
	protected $_name = 'sys_lov';

	const SITE_TYPE = 'site';
	
	public function getQueryList()
	{
		return $this->select()->order('site_id DESC');
	}
	
	public function getValue( $key )
	{
		if( empty( $key )) {
			return NULL;
		}
		$row = $this->fetchRow(
			$this->select()
			->where("type = '". self::SITE_TYPE."'")
			->where("description = ?", $key)
		);
		if( count( $row)){
			return $row->text_value;
		}
		
		return NULL;
	}	
}