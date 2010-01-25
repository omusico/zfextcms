<?php
class Cms_View_Helper_Site extends Zend_View_Helper_Abstract
{
	private $_site;
	
	public function site()
	{
		$this->_config = Zend_Registry::get('config');
		$this->_site = new Cms_Models_Site();
		return $this;
	}
	
	public function getTitle()
	{
		return $this->_site->getValue('title');
	}

	public function getLogo()
	{
		return $this->_site->getValue('logo');
	}
	
	public function getUrl( $item )
	{
		return $this->_config->app->url;
	}
}