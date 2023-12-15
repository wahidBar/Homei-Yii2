<?php

namespace app\models;

use Yii;
use \app\models\base\SmarthomeLog as BaseSmarthomeLog;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_smarthome_log".
 * Modified by Defri Indra M
 */
class SmarthomeLog extends BaseSmarthomeLog
{
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

    public static function record(Smarthome $smarthome, $sirkuit)
    {
        $log               = new SmarthomeLog;
        $log->id_user      = $smarthome->id_user;
        $log->id_smarthome = $smarthome->id;
        $log->id_sirkuit   = $sirkuit;
        $log->kelembapan   = $smarthome->kelembapan;
        $log->suhu         = $smarthome->suhu;
        $log->tegangan     = $smarthome->tegangan;
        $log->daya         = $smarthome->daya;
        $log->ampere       = $smarthome->ampere;
        $log->created_at   = date('Y-m-d H:i:s');
        if (!$log->validate()) {
            Yii::error($log->errors);
        } else {
            $log->save();
        }
    }

    public static function getDataSirkuitperJam($id_smarhome, $id_sirkuit, $category_hours, $data_variable, $date = null)
    {
        $date = $date ? $date : date('Y-m-d');
        // get data group by hour in day
        $data = SmarthomeLog::find()
            ->select([$data_variable, 'DATE_FORMAT(colName,\'%H:%i:%s\') as created_at'])
            // ->select(['hour(created_at) as hour', 'sum(' . $data_variable . ') / count(' . $data_variable . ') as ' . $data_variable])
            ->where(['id_smarthome' => $id_smarhome, 'id_sirkuit' => $id_sirkuit, 'date(created_at)' => $date])
            // ->groupBy('hour(created_at)')
            ->asArray()
            ->all();

        // if data not found then set default value 0
        $graph = [];
        if (empty($data)) {
            if ($category_hours == null) {
                // generate dummy category hours
                $category_hours = [];
                for ($i = 0; $i < 24; $i++) {
                    $category_hours[] = $i;
                }
            }
            foreach ($category_hours as $hour) {
                $graph[] = 0;
            }
        } else {
            if ($category_hours == null) {
                foreach ($data as $item) {
                    $graph[$item['created_at']] = round($item[$data_variable], 2);
                }
            } else {
                foreach ($category_hours as $hour) {
                    $found = false;
                    foreach ($data as $item) {
                        if ($item['hour'] == $hour) {
                            $found = true;
                            $graph[] = round($item[$data_variable], 2);
                        }
                    }
                    if (!$found) {
                        $graph[] = 0;
                    }
                }
            }
        }

        return $graph;
    }

    public static function getLogPerSirkuit($list_id_sirkuit, $selected = null)
    {
        $mapped_name = ArrayHelper::map($list_id_sirkuit, 'id', 'nama');
        $response = [];

        foreach ($list_id_sirkuit as $sirkuit_id) {
            $jumlah   = count($list_id_sirkuit);
            $subquery = SmarthomeLog::find()->select(['kelembapan', 'suhu', 'tegangan', 'id_sirkuit'])->where(['id_sirkuit' => $sirkuit_id])->orderBy(['id' => SORT_DESC])->limit($jumlah * 2);
            $data     = (new Query())->from([$subquery])->groupBy(['id_sirkuit'])->all();

            foreach ($data as $item) {
                $response[$item['id_sirkuit']]['nama'] = $mapped_name[$item['id_sirkuit']];
                $response[$item['id_sirkuit']]['data'] = [
                    'kelembapan' => $item['kelembapan'],
                    'suhu'       => $item['suhu'],
                    'tegangan'   => $item['tegangan'],
                ];
                $response[$item['id_sirkuit']]['max'] = [
                    'kelembapan' => 100,
                    'suhu'       => 100,
                    'tegangan'   => 260,
                ];
                $response[$item['id_sirkuit']]['unit'] = [
                    'kelembapan' => '%',
                    'suhu'       => '°C',
                    'tegangan'   => 'V',
                ];
                $response[$item['id_sirkuit']]['color'] = [
                    'kelembapan' => '#555',
                    'suhu'       => '#45Ad7A',
                    'tegangan'   => '#9A751C',
                ];
            }
        }

        if ($selected != null) {
            $response = $response[$selected];
        } else {
            $response = array_shift(array_slice($response, 0, 1));
        }

        return $response;
    }
}
