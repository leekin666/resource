<?php
/**
 * Created by PhpStorm.
 * User: LeeSin
 * Date: 2018/5/18 0018
 * Time: 14:58
 */
namespace frontend\models;

use Yii;
use yii\redis\ActiveRecord;

class TMstRes extends ActiveRecord
{
    /**
     * 主键 默认为 id
     *
     * @return array|string[]
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * 模型对应记录的属性列表
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'id', 
            'course_id', 
            'blind_id', 
            'res_type', 
            'file_mimetype', 
            'file_name', 
            'file_type', 
            'file_phy_name', 
            'file_size', 
            'url', 
            'create_datetime'
        ];
    }

    /**
     * 定义和其它模型的关系
     *
     * @return \yii\db\ActiveQueryInterface
     */
    public function getOrders()
    {
         return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }

}

