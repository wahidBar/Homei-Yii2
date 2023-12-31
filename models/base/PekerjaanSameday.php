<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use app\components\Constant;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "t_pekerjaan_sameday".
 *
 * @property integer $id
 * @property string $id_kategori
 * @property integer $id_pelanggan
 * @property integer $id_tukang
 * @property string $nama_pelanggan
 * @property string $latitude
 * @property string $longitude
 * @property string $alamat_pelanggan
 * @property string $foto_lokasi
 * @property string $uraian_pekerjaan
 * @property string $tanggal_survey
 * @property integer $biaya
 * @property string $layanan_yang_diberikan
 * @property integer $status
 * @property string $catatan_revisi
 * @property string $created_at
 * @property string $updated_at
 * @property integer $flag
 *
 * @property \app\models\User $pelanggan
 * @property string $aliasModel
 */
abstract class PekerjaanSameday extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_TANGGAL_SURVEY = 'tanggal-survey';
    const SCENARIO_PENGISIAN_LAYANAN = 'layanan';
    const SCENARIO_PENGAJUAN = 'pengajuan';
    const SCENARIO_NILAI_DP = 'nilai-dp';
    const SCENARIO_BAYAR_DP = 'bayar-dp';
    const SCENARIO_TOLAK_DP = 'tolak-dp';
    const SCENARIO_REVISI = 'revisi';
    const SCENARIO_BAYAR_TOTAL = 'bayar-total';
    const SCENARIO_TOLAK_PEMBAYARAN = 'tolak-bayar-total';
    const SCENARIO_BATAL_LAYANAN = 'batal-layanan';
    public $_render = [];

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $parent = parent::fields();

        if (isset($parent['id'])) {
            unset($parent['id']);
            // $parent['id'] = function ($model) {
            //     return $model->id;
            // };
        }

        if (isset($parent['kode_unik'])) {
            unset($parent['kode_unik']);
            $parent['id'] = function ($model) {
                return $model->kode_unik;
            };
        }

        if (isset($parent['id_kategori'])) {
            unset($parent['id_kategori']);

            $parent['id_kategori'] = function ($model) {
                return explode(",", $model->id_kategori);
            };

            $parent['_kategori'] = function ($model) {
                $rel = MasterKategoriLayananSameday::find()->where(['id' => explode(",", $this->id_kategori)])->select('nama_kategori_layanan')->column();
                return $rel;
            };
        }

        if (isset($parent['id_tukang'])) {
            unset($parent['id_tukang']);
            $parent['id_tukang'] = function ($model) {
                return $model->id_tukang;
            };

            $parent['_tukang'] = function ($model) {
                $rel = $model->getTukang()->select([
                    'username',
                    'name',
                    'photo_url'
                ])->one();

                return $rel;
            };
        }

        if (isset($parent['id_pelanggan'])) {
            unset($parent['id_pelanggan']);
            $parent['id_pelanggan'] = function ($model) {
                return $model->id_pelanggan;
            };

            $parent['_pelanggan'] = function ($model) {
                $rel = $model->getPelanggan()->select([
                    'username',
                    'name',
                    'photo_url'
                ])->one();

                return $rel;
            };
        }

        if (isset($parent['nama_pelanggan'])) {
            unset($parent['nama_pelanggan']);
            $parent['nama_pelanggan'] = function ($model) {
                return $model->nama_pelanggan;
            };
        }

        if (isset($parent['latitude'])) {
            unset($parent['latitude']);
            $parent['latitude'] = function ($model) {
                return $model->latitude;
            };
        }

        if (isset($parent['longitude'])) {
            unset($parent['longitude']);
            $parent['longitude'] = function ($model) {
                return $model->longitude;
            };
        }

        if (isset($parent['alamat_pelanggan'])) {
            unset($parent['alamat_pelanggan']);
            $parent['alamat_pelanggan'] = function ($model) {
                return $model->alamat_pelanggan;
            };
        }

        if (isset($parent['foto_lokasi'])) {
            unset($parent['foto_lokasi']);
            $parent['foto_lokasi'] = function ($model) {
                return Yii::$app->formatter->asFileLink($model->foto_lokasi);
            };
        }


        if (isset($parent['uraian_pekerjaan'])) {
            unset($parent['uraian_pekerjaan']);
            $parent['uraian_pekerjaan'] = function ($model) {
                return $model->uraian_pekerjaan;
            };
        }


        if (isset($parent['tanggal_survey'])) {
            unset($parent['tanggal_survey']);
            $parent['tanggal_survey'] = function ($model) {
                return Yii::$app->formatter->asIddate($model->tanggal_survey, false);
            };
        }


        if (isset($parent['biaya'])) {
            unset($parent['biaya']);
            $parent['biaya'] = function ($model) {
                return $model->biaya;
            };
        }


        if (isset($parent['nominal_dp'])) {
            unset($parent['nominal_dp']);
            $parent['nominal_dp'] = function ($model) {
                return $model->nominal_dp;
            };
        }

        if (Constant::isUriContain(["/view"])) {
            if (isset($parent['bukti_dp'])) {
                unset($parent['bukti_dp']);
                $parent['bukti_dp'] = function ($model) {
                    return Yii::$app->formatter->asFileLink($model->bukti_dp);
                };
            }


            if (isset($parent['tanggal_pembayaran_dp'])) {
                unset($parent['tanggal_pembayaran_dp']);
                $parent['tanggal_pembayaran_dp'] = function ($model) {
                    return Yii::$app->formatter->asIddate($model->tanggal_pembayaran_dp, false);
                };
            }

            if (isset($parent['deadline_pembayaran'])) {
                unset($parent['deadline_pembayaran']);
                $parent['deadline_pembayaran'] = function ($model) {
                    return Yii::$app->formatter->asIddate($model->deadline_pembayaran, false);
                };
            }

            if (isset($parent['keterangan_pembayaran_dp'])) {
                unset($parent['keterangan_pembayaran_dp']);
                $parent['keterangan_pembayaran_dp'] = function ($model) {
                    return $model->keterangan_pembayaran_dp;
                };
            }


            if (isset($parent['layanan_yang_diberikan'])) {
                unset($parent['layanan_yang_diberikan']);
                $parent['layanan_yang_diberikan'] = function ($model) {
                    return $model->layanan_yang_diberikan;
                };
            }


            if (isset($parent['bukti_pembayaran'])) {
                unset($parent['bukti_pembayaran']);
                $parent['bukti_pembayaran'] = function ($model) {
                    return Yii::$app->formatter->asFileLink($model->bukti_pembayaran);
                };
            }


            if (isset($parent['tanggal_pembayaran'])) {
                unset($parent['tanggal_pembayaran']);
                $parent['tanggal_pembayaran'] = function ($model) {
                    return Yii::$app->formatter->asIddate($model->tanggal_pembayaran, false);
                };
            }

            if (isset($parent['deadline_pembayaran_dp'])) {
                unset($parent['deadline_pembayaran_dp']);
                $parent['deadline_pembayaran_dp'] = function ($model) {
                    return Yii::$app->formatter->asIddate($model->deadline_pembayaran_dp, false);
                };
            }

            if (isset($parent['keterangan_pembayaran'])) {
                unset($parent['keterangan_pembayaran']);
                $parent['keterangan_pembayaran'] = function ($model) {
                    return $model->keterangan_pembayaran;
                };
            }


            if (isset($parent['revisi_pembayaran_dp'])) {
                unset($parent['revisi_pembayaran_dp']);
                $parent['revisi_pembayaran_dp'] = function ($model) {
                    return $model->revisi_pembayaran_dp;
                };
            }

            if (isset($parent['revisi_pembayaran'])) {
                unset($parent['revisi_pembayaran']);
                $parent['revisi_pembayaran'] = function ($model) {
                    return $model->revisi_pembayaran;
                };
            }


            if (isset($parent['catatan_revisi'])) {
                unset($parent['catatan_revisi']);
                $parent['catatan_revisi'] = function ($model) {
                    return $model->catatan_revisi;
                };
            }


            if (isset($parent['foto_pengerjaan'])) {
                unset($parent['foto_pengerjaan']);
                $parent['foto_pengerjaan'] = function ($model) {
                    return Yii::$app->formatter->asFileLink($model->foto_pengerjaan);
                };
            }


            if (isset($parent['keterangan_pengerjaan'])) {
                unset($parent['keterangan_pengerjaan']);
                $parent['keterangan_pengerjaan'] = function ($model) {
                    return $model->keterangan_pengerjaan;
                };
            }
        } else {

            if (isset($parent['bukti_dp'])) {
                unset($parent['bukti_dp']);
            }


            if (isset($parent['tanggal_pembayaran_dp'])) {
                unset($parent['tanggal_pembayaran_dp']);
            }


            if (isset($parent['keterangan_pembayaran_dp'])) {
                unset($parent['keterangan_pembayaran_dp']);
            }


            if (isset($parent['layanan_yang_diberikan'])) {
                unset($parent['layanan_yang_diberikan']);
            }


            if (isset($parent['bukti_pembayaran'])) {
                unset($parent['bukti_pembayaran']);
            }


            if (isset($parent['tanggal_pembayaran'])) {
                unset($parent['tanggal_pembayaran']);
            }


            if (isset($parent['keterangan_pembayaran'])) {
                unset($parent['keterangan_pembayaran']);
            }


            if (isset($parent['revisi_pembayaran_dp'])) {
                unset($parent['revisi_pembayaran_dp']);
            }

            if (isset($parent['revisi_pembayaran'])) {
                unset($parent['revisi_pembayaran']);
            }


            if (isset($parent['catatan_revisi'])) {
                unset($parent['catatan_revisi']);
            }


            if (isset($parent['foto_pengerjaan'])) {
                unset($parent['foto_pengerjaan']);
            }


            if (isset($parent['keterangan_pengerjaan'])) {
                unset($parent['keterangan_pengerjaan']);
            }
        }

        if (isset($parent['status'])) {
            unset($parent['status']);
            $parent['status'] = function ($model) {
                return $model->status;
            };
            $parent['_status'] = function ($model) {
                return $model->getStatus();
            };
        }

        if (isset($parent['created_at'])) {
            unset($parent['created_at']);
            $parent['created_at'] = function ($model) {
                return Yii::$app->formatter->asIddate($model->created_at, false);
            };
        }

        if (isset($parent['updated_at'])) {
            unset($parent['updated_at']);
            $parent['updated_at'] = function ($model) {
                return Yii::$app->formatter->asIddate($model->updated_at, false);
            };
        }

        if (isset($parent['flag'])) {
            unset($parent['flag']);
        }

        return $parent;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_pekerjaan_sameday';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
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
            [['kode_unik', 'id_kategori', 'id_pelanggan', 'nama_pelanggan', 'latitude', 'longitude', 'alamat_pelanggan', 'uraian_pekerjaan'], 'required'],
            [['kode_unik', 'alamat_pelanggan', 'uraian_pekerjaan', 'layanan_yang_diberikan', 'catatan_revisi', 'bukti_dp', 'bukti_pembayaran', 'tanggal_pembayaran', 'tanggal_pembayaran_dp', 'keterangan_pembayaran', 'keterangan_pembayaran_dp'], 'string'],
            [[
                'id_tukang',
                'biaya',
                'nominal_dp',
                'layanan_yang_diberikan',
                'status',
            ], 'required', 'on' => static::SCENARIO_PENGISIAN_LAYANAN],
            [[
                'revisi_pembayaran_dp',
            ], 'required', 'on' => static::SCENARIO_TOLAK_DP],
            [[
                'revisi_pembayaran',
            ], 'required', 'on' => static::SCENARIO_TOLAK_PEMBAYARAN],
            [[
                'catatan_revisi',
            ], 'required', 'on' => static::SCENARIO_REVISI],
            [[
                'keterangan_pembayaran',
                'bukti_pembayaran'
            ], 'required', 'on' => static::SCENARIO_BAYAR_TOTAL],
            [['id_pelanggan', 'id_tukang', 'status', 'flag'], 'integer'],
            [['tanggal_survey', 'created_at', 'biaya', 'updated_at', 'revisi_pembayaran_dp', 'foto_pengerjaan', 'keterangan_pengerjaan', 'deadline_pembayaran_dp', 'deadline_pembayaran',], 'safe'],
            [['nama_pelanggan', 'foto_lokasi'], 'string', 'max' => 255],
            [['id_pelanggan'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::class, 'targetAttribute' => ['id_pelanggan' => 'id']],
            [['id_tukang'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::class, 'targetAttribute' => ['id_tukang' => 'id']],
            // [['id_kategori'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\MasterKategoriLayananSameday::class, 'targetAttribute' => ['id_kategori' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cruds', 'ID'),
            'id_kategori' => Yii::t('cruds', 'Kategori'),
            'id_pelanggan' => Yii::t('cruds', 'Pelanggan'),
            'id_tukang' => Yii::t('cruds', 'Tukang'),
            'nama_pelanggan' => Yii::t('cruds', 'Nama Pelanggan'),
            'latitude' => Yii::t('cruds', 'Latitude'),
            'longtitude' => Yii::t('cruds', 'Longitude'),
            'alamat_pelanggan' => Yii::t('cruds', 'Alamat Pelanggan'),
            'foto_lokasi' => Yii::t('cruds', 'Foto Lokasi'),
            'uraian_pekerjaan' => Yii::t('cruds', 'Uraian Pekerjaan'),
            'tanggal_survey' => Yii::t('cruds', 'Tanggal Survei'),
            'biaya' => Yii::t('cruds', 'Biaya'),
            'tanggal_pembayaran' => Yii::t('cruds', 'Tanggal Pembayaran Total'),
            'deadline_pembayaran_dp' => Yii::t('cruds', 'Deadline Pembayaran DP'),
            'deadline_pembayaran' => Yii::t('cruds', 'Deadline Pembayaran'),
            'tanggal_pembayaran_dp' => Yii::t('cruds', 'Tanggal Pembayaran DP'),
            'nomninal_dp' => Yii::t('cruds', 'Nominal DP'),
            'bukti_dp' => Yii::t('cruds', 'Bukti DP'),
            'keterangan_pembayaran' => Yii::t('cruds', 'Keterangan Pembayaran'),
            'keterangan_pembayaran_dp' => Yii::t('cruds', 'Keterangan Pembayaran DP'),
            'bukti_pembayaran' => Yii::t('cruds', 'Bukti Pembayaran Total'),
            'layanan_yang_diberikan' => Yii::t('cruds', 'Layanan Yang Diberikan'),
            'revisi_pembayaran_dp' => Yii::t('cruds', 'Revisi Pembayaran DP'),
            'foto_pengerjaan' => Yii::t('cruds', 'Foto Pengerjaan'),
            'keterangan_pengerjaan' => Yii::t('cruds', 'Keterangan Pengerjaan'),
            'status' => Yii::t('cruds', 'Status'),
            // 'alasan_tolak' => Yii::t('cruds', 'Alasan Tolak'),
            'catatan_revisi' => Yii::t('cruds', 'Catatan Revisi'),
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diubah Pada',
            'flag' => Yii::t('cruds', 'Flag'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'status' => Yii::t('cruds', '0:pelengkapan data;1:survey;2:pengerjaan;3:revisi;4:selesai'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPelanggan()
    {
        return $this->hasOne(\app\models\User::class, ['id' => 'id_pelanggan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTukang()
    {
        return $this->hasOne(\app\models\User::class, ['id' => 'id_tukang']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategori()
    {
        return $this->hasOne(\app\models\MasterKategoriLayananSameday::class, ['id' => 'id_kategori']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\PekerjaanSamedayQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\PekerjaanSamedayQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'kode_unik',
            'id_kategori',
            'id_pelanggan',
            // 'id_tukang',
            'nama_pelanggan',
            'latitude',
            'longitude',
            'alamat_pelanggan',
            'foto_lokasi',
            'uraian_pekerjaan',
            // 'tanggal_survey',
            // 'biaya',
            // 'layanan_yang_diberikan',
            // 'status',
            'catatan_revisi',
            'created_at',
            'updated_at',
            'flag',
        ];

        $survey = [
            'tanggal_survey',
            'status',
        ];

        $layanan = [
            'id_tukang',
            'biaya',
            'nominal_dp',
            'layanan_yang_diberikan',
            'deadline_pembayaran_dp',
            'status',
        ];

        $bayar_dp = [
            'bukti_dp',
            'keterangan_pembayaran_dp',
            'status',
        ];

        $catatan_revisi = [
            'catatan_revisi',
            'status',
        ];

        $bayar_total = [
            'bukti_pembayaran',
            'keterangan_pembayaran',
            'status',
        ];

        $tolak_dp = [
            // 'bukti_dp',
            'revisi_pembayaran_dp',
            'deadline_pembayaran_dp',
            // 'keterangan_pembayaran_dp',
            'status',
        ];

        $tolak_pembayaran = [
            // 'bukti_dp',
            'revisi_pembayaran',
            // 'keterangan_pembayaran_dp',
            'status',
        ];

        $pengajuan = [
            'foto_pengerjaan',
            'keterangan_pengerjaan',
            'status',
        ];

        $batal = [
            'status',
        ];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_UPDATE] = $columns;
        $parent[static::SCENARIO_TANGGAL_SURVEY] = $survey;
        $parent[static::SCENARIO_PENGISIAN_LAYANAN] = $layanan;
        $parent[static::SCENARIO_BAYAR_DP] = $bayar_dp;
        $parent[static::SCENARIO_REVISI] = $catatan_revisi;
        $parent[static::SCENARIO_BAYAR_TOTAL] = $bayar_total;
        $parent[static::SCENARIO_TOLAK_DP] = $tolak_dp;
        $parent[static::SCENARIO_TOLAK_PEMBAYARAN] = $tolak_pembayaran;
        $parent[static::SCENARIO_PENGAJUAN] = $pengajuan;
        $parent[static::SCENARIO_BATAL_LAYANAN] = $batal;

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
     * @return array
     */
    public function render()
    {
        return array_merge($this->_render, [
            "model" => $this,
        ]);
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        return parent::validate($attributeNames, $clearErrors);
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function load($data, $formName = null, $service = "web")
    {
        return parent::load($data, $formName);
    }
}
