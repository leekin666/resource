<?php
namespace frontend\models;

use Yii;
use yii\redis\ActiveRecord;

class WeliveResource extends ActiveRecord
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
            'room_id',
            'blind_id', 
            'sort_id',
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

    public function rules()
    {
        parent::rules();
        return [
            [['room_id', 'res_type', 'file_type', 'sort_id', 'url', 'file_name'], 'required'],
            [['room_id', 'blind_id', 'sort_id'], 'integer'],
            [['create_datetime'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'room_id' => 'Room ID',
            'res_type' => 'Res Type',
            'file_type' => 'File Type',
            'url' => 'Url',
            'file_name' => 'File Name',
        ];
    }

    

}
