<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "t_proyek_cctv".
 *
 * @property integer $id
 * @property integer $id_proyek
 * @property string $lokasi
 * @property string $link
 * @property string $created_at
 * @property string $updated_at
 * @property integer $flag
 *
 * @property \app\models\Proyek $proyek
 * @property string $aliasModel
 */
abstract class ProyekCctv extends \yii\db\ActiveRecord
{



    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';
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
        if (isset($parent['id_proyek'])) :
            unset($parent['id_proyek']);
            $parent['id_proyek'] = function ($model) {
                return $model->id_proyek;
            };
            $parent['_proyek'] = function ($model) {
                $rel = $model->proyek;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['lokasi'])) :
            unset($parent['lokasi']);
            $parent['lokasi'] = function ($model) {
                return $model->lokasi;
            };
        endif;
        if (isset($parent['link'])) :
            unset($parent['link']);
            $parent['link'] = function ($model) {
                return $model->link;
            };
        endif;
        if (isset($parent['tipe'])) :
            unset($parent['tipe']);
            $parent['tipe'] = function ($model) {
                return $model->tipe;
            };
            $parent['_tipe'] = function ($model) {
                return $model->tipeLabel;
            };
        endif;
        if (isset($parent['created_at'])) :
            unset($parent['created_at']);
            $parent['created_at'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->created_at, false);
            };
        endif;
        if (isset($parent['deleted_at'])) :
            unset($parent['deleted_at']);
            $parent['deleted_at'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->deleted_at, false);
            };
        endif;
        if (isset($parent['updated_at'])) :
            unset($parent['updated_at']);
            $parent['updated_at'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->updated_at, false);
            };
        endif;
        if (isset($parent['created_by'])) :
            unset($parent['created_by']);
            $parent['created_by'] = function ($model) {
                return $model->created_by;
            };
            $parent['_created_by'] = function ($model) {
                return $model->getCreatedBy()->select(['username', 'name', 'photo_url'])->one();
            };
        endif;
        if (isset($parent['updated_by'])) :
            unset($parent['updated_by']);
            $parent['updated_by'] = function ($model) {
                return $model->updated_by;
            };
            $parent['_updated_by'] = function ($model) {
                return $model->getUpdatedBy()->select(['username', 'name', 'photo_url'])->one();
            };
        endif;
        if (isset($parent['deleted_by'])) :
            unset($parent['deleted_by']);
            $parent['deleted_by'] = function ($model) {
                return $model->deleted_by;
            };
            $parent['_deleted_by'] = function ($model) {
                $rel = $model->deletedBy;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['flag'])) :
            unset($parent['flag']);
            $parent['flag'] = function ($model) {
                return $model->flag;
            };
        endif;



        return $parent;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_proyek_cctv';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date("Y-m-d H:i:s"),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_proyek', 'lokasi', 'link', 'tipe'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['lokasi', 'link'], 'string', 'max' => 255],
            [['id_proyek'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Proyek::className(), 'targetAttribute' => ['id_proyek' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_proyek' => 'Proyek',
            'lokasi' => 'Lokasi',
            'link' => 'Link',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diubah Pada',
            'deleted_at' => 'Dihapus Pada',
            'created_by' => 'Dibuat oleh',
            'updated_by' => 'Diubah oleh',
            'deleted_by' => 'Dihapus oleh',
            'flag' => 'Flag',
        ];
    }

    public function attributeHints() {
        return [
            "link" => "Template untuk eksternal link harap gunakan deeplink||package . eg: onassissmart://||com.onassis.smart",
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyek()
    {
        return $this->hasOne(\app\models\Proyek::className(), ['id' => 'id_proyek']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'updated_by']);
    }

    public function getDeletedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'deleted_by']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\ProyekCctvQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ProyekCctvQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'id_proyek',
            'lokasi',
            'link',
            'tipe',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'deleted_by',
            'flag',
        ];

        $delete = [
            'deleted_at',
            'deleted_by',
            'flag',
        ];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_UPDATE] = $columns;
        $parent[static::SCENARIO_DELETE] = $delete;
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
