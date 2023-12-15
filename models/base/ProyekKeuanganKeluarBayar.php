<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "t_proyek_keuangan_keluar_bayar".
 *
 * @property integer $id
 * @property integer $id_proyek
 * @property integer $id_keuangan_keluar
 * @property string $tanggal
 * @property integer $dibayar
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \app\models\Proyek $proyek
 * @property \app\models\ProyekKeuanganKeluar $keuanganKeluar
 * @property \app\models\User $createdBy
 * @property \app\models\User $updatedBy
 * @property \app\models\User $deletedBy
 * @property string $aliasModel
 */
abstract class ProyekKeuanganKeluarBayar extends \yii\db\ActiveRecord
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
        if (isset($parent['id_keuangan_keluar'])) :
            unset($parent['id_keuangan_keluar']);
            $parent['id_keuangan_keluar'] = function ($model) {
                return $model->id_keuangan_keluar;
            };
            $parent['_keuangan_keluar'] = function ($model) {
                $rel = $model->keuanganKeluar;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['tanggal'])) :
            unset($parent['tanggal']);
            $parent['tanggal'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->tanggal, false);
            };
        endif;
        if (isset($parent['dibayar'])) :
            unset($parent['dibayar']);
            $parent['dibayar'] = function ($model) {
                return $model->dibayar;
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
        return 't_proyek_keuangan_keluar_bayar';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
            ],
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
            [['id_proyek', 'id_keuangan_keluar', 'tanggal', 'dibayar'], 'required'],
            [['id_proyek', 'id_keuangan_keluar', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['dibayar'], 'number', 'numberPattern' => '/^\d[\d,.]*$/'],
            [['tanggal', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['id_proyek'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Proyek::className(), 'targetAttribute' => ['id_proyek' => 'id']],
            [['id_keuangan_keluar'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\ProyekKeuanganKeluar::className(), 'targetAttribute' => ['id_keuangan_keluar' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['deleted_by' => 'id']]
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
            'id_keuangan_keluar' => 'Keuangan Keluar',
            'tanggal' => 'Tanggal',
            'dibayar' => 'Dibayar',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diubah Pada',
            'deleted_at' => 'Dihapus pada',
            'created_by' => 'Dibuat oleh',
            'updated_by' => 'Diubah oleh',
            'deleted_by' => 'Dihapus oleh',
            'flag' => 'Flag'
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
    public function getKeuanganKeluar()
    {
        return $this->hasOne(\app\models\ProyekKeuanganKeluar::className(), ['id' => 'id_keuangan_keluar']);
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
    public function getUpdatedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'deleted_by']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\ProyekKeuanganKeluarBayarQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ProyekKeuanganKeluarBayarQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'id_proyek',
            'id_keuangan_keluar',
            'tanggal',
            'dibayar',
            'created_at',
            'updated_at',
            'deleted_at',
            'created_by',
            'updated_by',
            'deleted_by',
            'flag'
        ];

        $delete = [
            'id_proyek',
            'id_keuangan_keluar',
            // 'dibayar',
            'deleted_at',
            'deleted_by',
            'flag'
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
