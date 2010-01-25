<?php
class Cms_View_Helper_Images extends Zend_View_Helper_Abstract {
    private $_node;
    private $_config;

    public function images() {
        $this->_config = Zend_Registry::get('config');
        $this->_node = new Cms_Models_File();
        return $this;
    }

    /**
     * Trae la url completa de una imagen,
     * Si le pasamos una resolucion, busca en el file systen, sino encuentra con esa resolucion la crea
     * @param string $name
     * @param string $resolution
     * @return string $url
     */
    public function getUrl( $name, $resolution = false) {
        if( file_exists( $this->_config->imgs->node->path . $name )) {
            if( $resolution != false ) {
                
                if( file_exists( $this->_config->imgs->node->path . $name . '/' . $resolution )) {
                    return $this->_config->app->img->url . '/' . $resolution . '/' . $name  ;
                }
                // A partir de la imagen original creamos una con la resolucion requerida
                $src = $this->_config->imgs->node->path . $name;
                $dest =  $this->_config->imgs->node->path . $resolution . '/' . $name;
                if( !file_exists( $this->_config->imgs->node->path . $resolution )  ) {
                    mkdir( $this->_config->imgs->node->path . $resolution, 0755 );
                }
                //exec('mkdir ' . $this->_config->imgs->node->path . '/' . $resolution );
                exec("convert $src -resize $resolution $dest ");
                return $this->_config->app->img->url . $resolution . '/' . $name  ;
            }
            return $this->_config->app->img->url . $name;
        }
        echo "222";exit;
        return NULL;
    }
}