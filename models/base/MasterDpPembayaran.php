<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "master_dp_pembayaran".
 *
 * @property integer $id
 * @property integer $dp
 * @property integer $status
 *
 * @property \app\models\TProyekPembayaran[] $tProyekPembayarans
 * @property string $aliasModel
 */
abstract class MasterDpPembayaran extends \yii\db\ActiveRecord
{



    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    public $_render = [];

    /**
     * @inheiritance
     */
    public function fields()
    {
        $parent = parent::fields();

        if (isset($parent['id'])) :
            unset($parent['id']);
            $parent['id'] = function ($model) {
                return $model->id;
            };
        endif;
        if (isset($parent['dp'])) :
            unset($parent['dp']);
            $parent['dp'] = function ($model) {
                return $model->dp;
            };
            $parent['_t_proyek_pembayarans'] = function ($model) {
                $rel = $model->tProyekPembayarans;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['status'])) :
            unset($parent['status']);
            $parent['status'] = function ($model) {
                return $model->status;
            };
        endif;


        // $parent['t_proyek_pembayaran'] = function($model) {
        //     $rel = $model->tProyekPembayarans;
        //     if($rel) :
        //         return $rel;
        //     endif;
        //     return null;
        // };

        return $parent;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'master_dp_pembayaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dp'], 'required'],
            [['dp', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dp' => 'DP',
            'status' => 'Status',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'dp' => 'satuan persen',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTProyekPembayarans()
    {
        return $this->hasMany(\app\models\TProyekPembayaran::className(), ['id_dp_pembayaran' => 'id']);
    }





    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'dp',
            'status',
        ];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_UPDATE] = $columns;
        return $parent;
    }

    public function setRender($arr)
    {
        $this->_render = array_merge($this->_render, $arr);
    }

    public function removeRender($arr)
    {
        unset($this->_render[$arr]);
    }

    /**
     * Simplify return data xD
     */
    public function render()
    {
        return array_merge($this->_render, [
            "model" => $this,
        ]);
    }

    /**
     * override validate
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        return parent::validate($attributeNames, $clearErrors);
    }

    /**
     * override load
     */
    public function load($data, $formName = null, $service = "web")
    {
        return parent::load($data, $formName);
    }
}
