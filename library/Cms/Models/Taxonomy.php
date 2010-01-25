<?php
class Cms_Models_Taxonomy extends Easytech_Db_Table
{
	protected $_name = 'cms_taxonomy';

	public function getName( $tid ) 
	{
		if( empty( $tid  )){
			return false;
		}
		$row = $this->find( $tid )->current();
		if( count( $row ) ) {
			return $row->title;
		}
		return false;
	}
	
	public function getQueryList( $vid = 0 )
	{
		$query = $this->select();
		if( $vid != 0 ) {
			$query->where( 'vocabulary_id = ?', $vid );
		}
		return $query->order('taxonomy_id DESC');
	}
	
	public function getActiveInArray( $vid = 0)
	{
		$query = $this->select();
		if( $vid != 0 ) {
			$query->where( 'vocabulary_id = ?', $vid );
		}
		$query->order('title DESC');
		$rowset = $this->fetchAll( 
			$query
		);
		$res = array();
		foreach( $rowset as $row) {
			$res[$row->taxonomy_id] = $row->title;
		}
		return $res;
	}
	
	public function save( $params, $id = NULL )
	{
		if( !empty( $id) ){
			$row = $this->find( $id )->current();
		} else {
			$row = $this->fetchRow(
				$this->select()
				->where('title = ?', $params['title'])
				->where('vocabulary_id = ?', $params['vocabulary_id'])
			);
			if( ! count( $row )) {
				$row = $this->createRow();
			}
		}
		$row->setFromArray( $params );
		return $row->save();
	}

    public function getTaxonomies( $vid )
    {
        if( empty( $vid )) {
            return array();
        }
        $rowset = $this->fetchAll(
            $this->select()
            ->where('vocabulary_id = ?', $vid)
        );
        if( count( $rowset )){
            return $rowset->toArray();
        }
        return array();
    }
}