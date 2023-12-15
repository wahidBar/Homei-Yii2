<?php

namespace app\models;

use Yii;
use \app\models\base\Smarthome as BaseSmarthome;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_smarthome".
 * Modified by Defri Indra M
 */
class Smarthome extends BaseSmarthome
{
    public function getListDevice($format = "raw")
    {
        $list = $this->getSmarthomeKontrols()->select(['nama', 'value'])->asArray()->all();
        $template = '';
        foreach ($list as $item) {
            if ($format == "html") {
                $template .= '<div class="row">';
                $template .= '<div class="col-md-6">' . $item['nama'] . '</div>';
                $template .= '<div class="col-md-6">' . ($item['value'] ? "Nyala" : "Mati") . '</div>';
                $template .= '</div>';
            } else {
                $template .= $item['nama'] . ': ' . ($item['value'] ? "Nyala" : "Mati") . '\n';
            }
        }
        return $template;
    }

    public function initValue()
    {
        $this->suhu = "0";
        $this->kelembapan = "0";
        $this->tegangan = "0";
        $this->daya = "0";
        $this->ampere = "0";
    }

    public function totalDayaTerakhir()
    {
        $list_id_sirkuit = $this->getSmarthomeSirkuits()->select(['id'])->asArray()->column();
        $subquery = SmarthomeLog::find()->select(['daya', 'id_sirkuit'])->orderBy(['id' => SORT_DESC])->limit(count($list_id_sirkuit));
        $data = (new Query)->from([$subquery])->groupBy(['id_sirkuit'])->sum('daya');
        return $data;
    }

    public function totalAmpereTerakhir()
    {
        $list_id_sirkuit = $this->getSmarthomeSirkuits()->select(['id'])->asArray()->column();
        $subquery = SmarthomeLog::find()->select(['ampere', 'id_sirkuit'])->orderBy(['id' => SORT_DESC])->limit(count($list_id_sirkuit));
        $data = (new Query)->from([$subquery])->groupBy(['id_sirkuit'])->sum('ampere');
        return $data;
    }
}
