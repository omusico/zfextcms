<?php
class Cms_View_Helper_Node extends Zend_View_Helper_Abstract
{
	private $_node;
	private $_config;
		
	public function node()
	{
		$this->_config = Zend_Registry::get('config');
		$this->_node = new Cms_Models_Node();
		return $this;
	}

	public function getUrl( $nid )
	{
		return $this->_config->app->url . "/node/view/nid/" . $nid;
	}
	
	public function load( $nid )
	{
		return $this->_node->load( $nid );
	}
	
	public function getDescription( $nid )
	{
		$node = $this->load( $nid );
		if( count( $node) ){
			return $node['hdr']['description'];
		}
		return false;
	}
}