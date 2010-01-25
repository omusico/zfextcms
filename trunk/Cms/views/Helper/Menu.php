<?php
class Cms_View_Helper_Menu extends Zend_View_Helper_Abstract {
    private $_menu;

    public function menu() {
        $this->_config = Zend_Registry::get('config');
        $this->_menu = new Cms_Models_Menu();
        return $this;
    }

    public function getItems( $menuType ) {
        return $this->_menu->getItems( $menuType );
    }

    public function getUrl( $item ) {
        if( empty( $item['url'])) {
            $url = $this->_config->app->url;
            $url .= ( empty($item['module'])?'/':'/'.$item['module'] .'/');
            $url .= $item['controller'] .'/';
            $url .= ( empty($item['action'])?'':$item['action'] .'/');
            return $url;
        } else {
            if( $item['url'][0] == '/') {
                return $this->_config->app->url . $item['url'] ;
            } else {
                return $item['url'] ;
            }
        }
    }
}