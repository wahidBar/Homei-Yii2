<?php

namespace app\models;

use Yii;
use \app\models\base\SmarthomeSirkuit as BaseSmarthomeSirkuit;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_smarthome_sirkuit".
 * Modified by Defri Indra M
 */
class SmarthomeSirkuit extends BaseSmarthomeSirkuit
{
    /**
     * Variable to store pairing code
     * @var integer
     */
    public $kode_produk;
    public $kode_pairing;

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }

    // add fictive attribute
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'kode_pairing' => 'Kode Pairing',
                'kode_produk' => 'Kode Produk',
            ]
        );
    }

    // add fictive attribute to scenario
    public function scenarios()
    {
        return ArrayHelper::merge(
            parent::scenarios(),
            [
                self::SCENARIO_CREATE => ['nama', 'kode_produk', 'kode_pairing'],
                self::SCENARIO_UPDATE => ['nama', 'kode_produk', 'kode_pairing'],
                // default scenario
                self::SCENARIO_DEFAULT => ['nama', 'kode_produk', 'kode_pairing'],
            ]
        );
    }

    public function nonActivateSirkuit()
    {
        // non-activate all controls
        $controls = SmarthomeKontrol::find()->where(['id_sirkuit' => $this->id])->all();
        foreach ($controls as $control) {
            $control->nonActivateControl();
        }
        $this->flag = 0;
        $this->save();
    }
}
