<?php

class Cms_Models_CCKVocabulary extends Easytech_Db_Table
{
	protected $_name = 'cms_cck_vocabulary';
    protected $_primary = array( 'cck_id', 'vocabulary_id');
}