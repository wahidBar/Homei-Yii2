<?php

namespace app\models;

use Yii;
use \app\models\base\Sapaan as BaseSapaan;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_android_sapaan".
 * Modified by Defri Indra M
 */
class Sapaan extends BaseSapaan
{
    // constanta untuk status dari getStatueses()
    const TYPE_PAGI = 0;
    const TYPE_SIANG = 1;
    const TYPE_SORE = 2;
    const TYPE_MALAM = 3;
    const TYPE_DINI_HARI = 4;


    static function getStatuses()
    {
        return [
            static::TYPE_PAGI => "Pagi (07.00 - 12.00)",
            "Siang (12.00 - 15.00)",
            "Sore (15.00 - 18.00)",
            "Malam (18.00 - 21.00)",
            "Dini Hari (21.00 - 07.00)",
        ];
    }

    function getStatusLabel()
    {
        return static::getStatuses()[$this->type];
    }

    static function timeNow()
    {
        $timenow = time();

        // conditional if time match with status
        if ($timenow >= strtotime("07:00") && $timenow <= strtotime("12:00")) {
            return 0;
        } elseif ($timenow >= strtotime("12:00") && $timenow <= strtotime("15:00")) {
            return 1;
        } elseif ($timenow >= strtotime("15:00") && $timenow <= strtotime("18:00")) {
            return 2;
        } elseif ($timenow >= strtotime("18:00") && $timenow <= strtotime("21:00")) {
            return 3;
        } elseif ($timenow >= strtotime("21:00") && $timenow <= strtotime("07:00 + 1 day")) { // + 1 day
            return 4;
        }

        return -1;
    }

    public static function showBasedOnTime()
    {
        $time = self::timeNow();

        if ($time == static::TYPE_PAGI) {
            $data = self::find()
                ->where(['type' => static::TYPE_PAGI])
                ->orderBy(new \yii\db\Expression('rand()'))
                ->one();
            if ($data == null)
                return null;
            else
                return $data->kalimat;
        } elseif ($time == static::TYPE_SIANG) {
            $data = self::find()
                ->where(['type' => static::TYPE_SIANG])
                ->orderBy(new \yii\db\Expression('rand()'))
                ->one();
            if ($data == null)
                return null;
            else
                return $data->kalimat;
        } elseif ($time == static::TYPE_SORE) {
            $data = self::find()
                ->where(['type' => static::TYPE_SORE])
                ->orderBy(new \yii\db\Expression('rand()'))
                ->one();
            if ($data == null)
                return null;
            else
                return $data->kalimat;
        } elseif ($time == static::TYPE_MALAM) {
            $data = self::find()
                ->where(['type' => static::TYPE_MALAM])
                ->orderBy(new \yii\db\Expression('rand()'))
                ->one();
            if ($data == null)
                return null;
            else
                return $data->kalimat;
        } elseif ($time == static::TYPE_DINI_HARI) {
            $data = self::find()
                ->where(['type' => static::TYPE_DINI_HARI])
                ->orderBy(new \yii\db\Expression('rand()'))
                ->one();
            if ($data == null)
                return null;
            else
                return $data->kalimat;
        }

        return null;
    }
}
