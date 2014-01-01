<?php
namespace org\x3f\flamedemo\model;
use org\x3f\flamework\base\ActiveRecord;

class Post extends ActiveRecord
{
    public static function getModel($className=__CLASS__)
    {
        return parent::getModel($className);
    }
    
    public function getTableName()
    {
        return 'post';
    }
    
    public function getPrimaryKey()
    {
        return 'id';
    }
    
}
?>
