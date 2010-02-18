<?php

class Cms_Models_CCKContentType extends Easytech_Db_Table
{
	protected $_name = 'cms_cck_content_type';
    protected $_primary = array( 'cck_id', 'content_type_id');
}
