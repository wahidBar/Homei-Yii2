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
 * This is the base-model class for table "portofolio".
 *
 * @property integer $id
 * @property string $judul
 * @property string $slug
 * @property integer $user_id
 * @property integer $kontraktor_id
 * @property integer $konsep_desain_id
 * @property string $wilayah_provinsi
 * @property integer $total_harga
 * @property string $luas
 * @property integer $ruangan
 * @property string $timeline_proyek
 * @property string $tentang_proyek
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_by
 *
 * @property \app\models\User $user
 * @property \app\models\Kontraktor $kontraktor
 * @property \app\models\MasterKonsepDesain $konsepDesain
 * @property \app\models\WilayahProvinsi $wilayahProvinsi
 * @property \app\models\PortofolioGambarDesign[] $portofolioGambarDesigns
 * @property \app\models\PortofolioGambarHasil[] $portofolioGambarHasils
 * @property \app\models\PortofolioSebelumSesudah[] $portofolioSebelumSesudahs
 * @property string $aliasModel
 */
abstract class Portofolio extends \yii\db\ActiveRecord
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
        if (isset($parent['kode_unik'])) :
            unset($parent['kode_unik']);
            $parent['kode_unik'] = function ($model) {
                return $model->kode_unik;
            };
        endif;
        if (isset($parent['judul'])) :
            unset($parent['judul']);
            $parent['judul'] = function ($model) {
                return $model->judul;
            };
        endif;
        if (isset($parent['slug'])) :
            unset($parent['slug']);
            $parent['slug'] = function ($model) {
                return $model->slug;
            };
        endif;
        if (isset($parent['user_id'])) :
            unset($parent['user_id']);
            $parent['user_id'] = function ($model) {
                return $model->user_id;
            };
            $parent['_user'] = function ($model) {
                $rel = $model->getUser()->select('username, name, photo_url')->one();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['kontraktor_id'])) :
            unset($parent['kontraktor_id']);
            $parent['kontraktor_id'] = function ($model) {
                return $model->kontraktor_id;
            };
            $parent['_kontraktor'] = function ($model) {
                $rel = $model->getKontraktor()->select(['nama_kontraktor', 'alamat', 'telepon'])->one();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['konsep_desain_id'])) :
            unset($parent['konsep_desain_id']);
            $parent['konsep_desain_id'] = function ($model) {
                return $model->konsep_desain_id;
            };
            $parent['_konsep_desain'] = function ($model) {
                $rel = $model->getKonsepDesain()->select('nama_konsep, gambar')->one();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['wilayah_provinsi'])) :
            unset($parent['wilayah_provinsi']);
            $parent['wilayah_provinsi'] = function ($model) {
                return $model->wilayah_provinsi;
            };
            $parent['_wilayah_provinsi'] = function ($model) {
                $rel = $model->getWilayahProvinsi()->select('nama')->one();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['total_harga'])) :
            unset($parent['total_harga']);
            $parent['total_harga'] = function ($model) {
                return $model->total_harga;
            };
        endif;
        if (isset($parent['luas'])) :
            unset($parent['luas']);
            $parent['luas'] = function ($model) {
                return $model->luas;
            };
        endif;
        if (isset($parent['ruangan'])) :
            unset($parent['ruangan']);
            $parent['ruangan'] = function ($model) {
                return $model->ruangan;
            };
        endif;
        if (isset($parent['timeline_proyek'])) :
            unset($parent['timeline_proyek']);
            $parent['timeline_proyek'] = function ($model) {
                return $model->timeline_proyek;
            };
        endif;
        if (isset($parent['tentang_proyek'])) :
            unset($parent['tentang_proyek']);
            $parent['tentang_proyek'] = function ($model) {
                return $model->tentang_proyek;
            };
        endif;
        if (isset($parent['created_at'])) :
            unset($parent['created_at']);
        // $parent['created_at'] = function ($model) {
        //     return \app\components\Tanggal::toReadableDate($model->created_at, false);
        // };
        endif;
        if (isset($parent['updated_at'])) :
            unset($parent['updated_at']);
        // $parent['updated_at'] = function ($model) {
        //     return \app\components\Tanggal::toReadableDate($model->updated_at, false);
        // };
        endif;
        if (isset($parent['deleted_at'])) :
            unset($parent['deleted_at']);
        // $parent['deleted_at'] = function ($model) {
        //     return $model->deleted_at;
        // };
        endif;
        if (isset($parent['created_by'])) :
            unset($parent['created_by']);
        // $parent['created_by'] = function ($model) {
        //     return $model->created_by;
        // };
        endif;
        if (isset($parent['updated_by'])) :
            unset($parent['updated_by']);
        // $parent['updated_by'] = function ($model) {
        //     return $model->updated_by;
        // };
        endif;
        if (isset($parent['deleted_by'])) :
            unset($parent['deleted_by']);
        // $parent['deleted_by'] = function ($model) {
        //     return $model->deleted_by;
        // };
        endif;
        if (isset($parent['flag'])) :
            unset($parent['flag']);
        // $parent['flag'] = function ($model) {
        //     return $model->flag;
        // };
        endif;

        if (\app\components\Constant::isUriContain(['/portofolio', '/protofolio/index', "/protofolio/"])) {
            $parent['_preview'] = function ($model) {
                $rel = $model->getPortofolioGambars()
                    ->select(['id', 'gambar_design', 'jenis_gambar'])
                    ->one();

                if ($rel) :
                    return Yii::$app->formatter->asMyImage($rel->gambar_design, false);
                endif;

                $rel = $model->getKonsepDesain()->select('nama_konsep, gambar')->one();
                if ($rel) :
                    return Yii::$app->formatter->asMyImage($rel->gambar, false);
                endif;

                return null;
            };
        }
        if (\app\components\Constant::isUriContain(['/portofolio/view'])) {
            $parent['portofolio_gambar'] = function ($model) {
                $rel = $model->getPortofolioGambars()
                    ->select(['id', 'gambar_design', 'jenis_gambar'])
                    ->all();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
            // $parent['portofolio_sebelum_sesudah'] = function ($model) {
            //     $rel = $model->getPortofolioSebelumSesudahs()->select(['id', 'sebelum_sesudah'])->all();
            //     if ($rel) :
            //         return $rel;
            //     endif;
            //     return null;
            // };
            // $parent['portofolio_gambar_hasil'] = function ($model) {
            //     $rel = $model->getPortofolioGambarHasils()->select(['id', 'gambar_hasil'])->all();
            //     if ($rel) :
            //         return $rel;
            //     endif;
            //     return null;
            // };
        }

        return $parent;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'portofolio';
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
            [['kode_unik', 'judul', 'slug', 'user_id', 'kontraktor_id', 'konsep_desain_id', 'wilayah_provinsi', 'total_harga', 'luas', 'ruangan', 'timeline_proyek', 'tentang_proyek'], 'required'],
            [['created_by', 'updated_by', 'deleted_by', 'user_id', 'kontraktor_id', 'konsep_desain_id', 'ruangan', 'flag'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['total_harga'], 'string', 'max' => 50],
            [['kode_unik'], 'unique'],
            [['judul', 'slug', 'luas', 'timeline_proyek'], 'string', 'max' => 255],
            [['wilayah_provinsi'], 'string', 'max' => 2],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['kontraktor_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Kontraktor::className(), 'targetAttribute' => ['kontraktor_id' => 'id']],
            [['konsep_desain_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\MasterKonsepDesain::className(), 'targetAttribute' => ['konsep_desain_id' => 'id']],
            [['wilayah_provinsi'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\WilayahProvinsi::className(), 'targetAttribute' => ['wilayah_provinsi' => 'id']]
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
            'slug' => 'Slug',
            'user_id' => 'Konsultan',
            'kontraktor_id' => 'Kontraktor',
            'konsep_desain_id' => 'Konsep Desain',
            'wilayah_provinsi' => 'Lokasi',
            'total_harga' => 'Total Harga',
            'luas' => 'Luas',
            'ruangan' => 'Ruangan',
            'timeline_proyek' => 'Linimasa Proyek',
            'tentang_proyek' => 'Tentang Proyek',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diubah Pada',
            'deleted_at' => 'Dihapus Pada',
            'created_by' => 'Dibuat oleh',
            'updated_by' => 'Diubah oleh',
            'deleted_by' => 'Dihapus oleh',
            'flag' => 'Flag'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKontraktor()
    {
        return $this->hasOne(\app\models\Kontraktor::className(), ['id' => 'kontraktor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKonsepDesain()
    {
        return $this->hasOne(\app\models\MasterKonsepDesain::className(), ['id' => 'konsep_desain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWilayahProvinsi()
    {
        return $this->hasOne(\app\models\WilayahProvinsi::className(), ['id' => 'wilayah_provinsi']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getPortofolioGambarHasils()
    // {
    //     return $this->hasMany(\app\models\PortofolioGambarHasil::className(), ['portofolio_id' => 'id']);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPortofolioGambars()
    {
        return $this->hasMany(\app\models\PortofolioGambar::className(), ['portofolio_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getPortofolioSebelumSesudahs()
    // {
    //     return $this->hasMany(\app\models\PortofolioSebelumSesudah::className(), ['portofolio_id' => 'id']);
    // }

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



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'judul',
            'slug',
            'user_id',
            'kontraktor_id',
            'konsep_desain_id',
            'wilayah_provinsi',
            'total_harga',
            'luas',
            'ruangan',
            'timeline_proyek',
            'tentang_proyek',
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
