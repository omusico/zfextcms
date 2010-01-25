<?php
class Cms_View_Helper_Theme extends Zend_View_Helper_Abstract {
    private $_config;

    public function theme() {
        $this->_config = Zend_Registry::get('config');
        return $this;
    }


    public function render( $tpl, array $params, $render = true  ) {
		/*
		$smarty = Easytech_Smarty::getInstance( $this->_config );
		foreach( $params as $key=>$value) {
			$smarty->assign($key, $value );
		}
		$result = $smarty->fetch( $this->_config->Smarty->root->dir . $tpl );
        //$content = file_get_contents( $this->_config->Smarty->root->dir . $tpl  );
		if( $output === true ) {
			echo $result;
			return true;
		}
		return $output;
		*/
        $contents = $params['items'];
        $view = $params['view'];
        require_once( $this->_config->Smarty->root->dir . $tpl  );
        if( $render == true ) {
            echo $output;
        }
        return $output;
    }
}