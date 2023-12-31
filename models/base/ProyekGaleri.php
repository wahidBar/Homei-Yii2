<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "t_proyek_galeri".
 *
 * @property integer $id
 * @property integer $id_proyek
 * @property integer $id_proyek_kemajuan
 * @property string $nama_file
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $deleted_at
 * @property integer $deleted_by
 *
 * @property \app\models\Proyek $proyek
 * @property \app\models\User $createdBy
 * @property \app\models\User $deletedBy
 * @property \app\models\ProyekKemajuan $proyekKemajuan
 * @property string $aliasModel
 */
abstract class ProyekGaleri extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE_AT_PROGRESS = "create_at_progress";


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
            $parent['_proyek_kemajuan'] = function ($model) {
                $rel = $model->proyekKemajuan;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['id_proyek_kemajuan'])) :
            unset($parent['id_proyek_kemajuan']);
            $parent['id_proyek_kemajuan'] = function ($model) {
                return $model->id_proyek_kemajuan;
            };
            $parent['_proyek_kemajuan'] = function ($model) {
                $rel = $model->proyekKemajuan;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['nama_file'])) :
            unset($parent['nama_file']);
            $parent['nama_file'] = function ($model) {
                return Yii::$app->formatter->asMyImage($model->nama_file, false);
            };
        endif;
        if (isset($parent['keterangan'])) :
            unset($parent['keterangan']);
            $parent['keterangan'] = function ($model) {
                return $model->keterangan;
            };
        endif;
        if (isset($parent['created_at'])) :
            unset($parent['created_at']);
            $parent['created_at'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->created_at, false);
            };
        endif;
        if (isset($parent['updated_at'])) :
            unset($parent['updated_at']);
            $parent['updated_at'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->updated_at, false);
            };
        endif;
        if (isset($parent['deleted_at'])) :
            unset($parent['deleted_at']);
            $parent['deleted_at'] = function ($model) {
                return $model->deleted_at;
            };
        endif;
        if (isset($parent['created_by'])) :
            unset($parent['created_by']);
            $parent['created_by'] = function ($model) {
                return $model->created_by;
            };
            $parent['_created_by'] = function ($model) {
                $rel = $model->createdBy;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['updated_by'])) :
            unset($parent['updated_by']);
            $parent['updated_by'] = function ($model) {
                return $model->updated_by;
            };
            $parent['_updated_by'] = function ($model) {
                $rel = $model->updatedBy;
                if ($rel) :
                    return $rel;
                endif;
                return null;
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
        return 't_proyek_galeri';
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
                'updatedAtAttribute' => false,
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
            [['id_proyek'], 'required', 'on' => static::SCENARIO_CREATE_AT_PROGRESS],
            [['id_proyek', 'nama_file', 'keterangan'], 'required', 'on' => [static::SCENARIO_CREATE, static::SCENARIO_DEFAULT, static::SCENARIO_UPDATE]],
            [['keterangan'], 'string'],
            [['id_proyek', 'id_proyek_kemajuan', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_file'], 'string', 'max' => 200],
            [['id_proyek'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Proyek::className(), 'targetAttribute' => ['id_proyek' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['id_proyek_kemajuan'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\ProyekKemajuan::className(), 'targetAttribute' => ['id_proyek_kemajuan' => 'id']]
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
            'id_proyek_kemajuan' => 'Proyek Kemajuan',
            'nama_file' => 'Nama File',
            'keterangan' => 'Keterangan',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diubah Pada',
            'deleted_at' => 'Dihapus pada',
            'created_by' => 'Dibuat oleh',
            'updated_by' => 'Diubah oleh',
            'deleted_by' => 'Dihapus oleh',
            'flag' => 'Flag',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyek()
    {
        return $this->hasOne(\app\models\Proyek::className(), ['id' => 'id_proyek']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekKemajuan()
    {
        return $this->hasOne(\app\models\ProyekKemajuan::className(), ['id' => 'id_proyek_kemajuan']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\ProyekGaleriQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ProyekGaleriQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'id_proyek',
            'id_proyek_kemajuan',
            'nama_file',
            'keterangan',
            'created_at',
            'updated_at',
            'deleted_at',
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
        $parent[static::SCENARIO_CREATE_AT_PROGRESS] = $columns;
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
