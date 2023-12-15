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
 * This is the base-model class for table "galeri".
 *
 * @property integer $id
 * @property string $judul
 * @property string $keterangan
 * @property string $gambar
 * @property string $style
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $aliasModel
 */
abstract class Galeri extends \yii\db\ActiveRecord
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
        if (isset($parent['judul'])) :
            unset($parent['judul']);
            $parent['judul'] = function ($model) {
                return $model->judul;
            };
        endif;
        if (isset($parent['keterangan'])) :
            unset($parent['keterangan']);
            $parent['keterangan'] = function ($model) {
                return $model->keterangan;
            };
        endif;
        if (isset($parent['gambar'])) :
            unset($parent['gambar']);
            $parent['gambar'] = function ($model) {
                return \Yii::$app->formatter->asMyimage($model->gambar, false);
            };
        endif;
        if (isset($parent['style'])) :
            unset($parent['style']);
            $parent['style'] = function ($model) {
                return $model->style;
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



        return $parent;
    }

    /**
     * ENUM field values
     */
    const STYLE_SQUARE = 'square';
    const STYLE_VERTICAL = 'vertical';
    const STYLE_HORIZONTAL = 'horizontal';
    const STYLE_BIG = 'big';
    var $enum_labels = false;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'galeri';
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
            [['judul', 'keterangan', 'style'], 'required'],
            [['keterangan', 'style'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['judul', 'gambar'], 'string', 'max' => 255],
            [
                'style', 'in', 'range' => [
                    self::STYLE_SQUARE,
                    self::STYLE_VERTICAL,
                    self::STYLE_HORIZONTAL,
                    self::STYLE_BIG,
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'judul' => 'Judul',
            'keterangan' => 'Keterangan',
            'gambar' => 'Gambar',
            'style' => 'Model',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diubah Pada',
            'created_by' => 'Dibuat oleh',
            'updated_by' => 'Diubah oleh',
        ];
    }




    /**
     * get column style enum value label
     * @param string $value
     * @return string
     */
    public static function getStyleValueLabel($value)
    {
        $labels = self::optsStyle();
        if (isset($labels[$value])) {
            return $labels[$value];
        }
        return $value;
    }

    /**
     * column style ENUM value labels
     * @return array
     */
    public static function optsStyle()
    {
        return [
            self::STYLE_SQUARE => self::STYLE_SQUARE,
            self::STYLE_VERTICAL => self::STYLE_VERTICAL,
            self::STYLE_HORIZONTAL => self::STYLE_HORIZONTAL,
            self::STYLE_BIG => self::STYLE_BIG,
        ];
    }


    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'judul',
            'keterangan',
            'gambar',
            'style',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_UPDATE] = $columns;
        return $parent;
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
