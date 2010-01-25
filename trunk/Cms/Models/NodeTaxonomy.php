<?php
class Cms_Models_NodeTaxonomy extends Easytech_Db_Table
{
	protected $_name = 'cms_node_taxonomy';

	public function deleteAll ( $nid )
	{
		return $this->delete( 'node_id = ' . $this->getAdapter()->quote( $nid ) );
	}
}