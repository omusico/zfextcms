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
		
		/** convvertimos en varios formatos */
		/** @todo hacer que solo convierta las imagenes */
		/*if( $params['type']  == 'images') {
			
		}*/
	//	$this->imageConvert( $fid );
	}
	
	public function imageConvert( $fid )
	{
		$row = $this->find($fid )->current();
		if( count( $row )) {
			//convert dsc00232.jpg -resize 100x20 rose.png
			$src = $row->destination . "/" .$row->name;
			$dest = $row->destination . "/100_100/" . $row->name;
			exec("convert $src -resize 100x100 $dest ");
			$dest = $row->destination . "/200_200/" . $row->name;
			exec("convert $src -resize 200x200 $dest ");
			$dest = $row->destination . "/300_300/" . $row->name;
			exec("convert $src -resize 300x300 $dest ");
			return true;
		}
		return false;
	}
}