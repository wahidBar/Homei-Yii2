<?php

namespace app\formatter;

use app\components\Angka;
use app\components\Tanggal;
use Yii;
use yii\i18n\Formatter;

class CustomFormatter extends Formatter
{
    public static function asMyimage($link, $html = true, $default = null)
    {
        if (substr($link, 0, 4) == "http") {
            if ($html == false) {
                return $link;
            }

            return "<a  href='$link' target='_blank'><img src='$link' class='img img-response' style='width: 80px;'></a>";
        }

        $image = Yii::getAlias("@file/$link");
        if (file_exists(Yii::getAlias("@webroot/uploads/$link")) && Yii::getAlias("@webroot/uploads/$link") != Yii::getAlias("@webroot/uploads/")) {
            if ($html == false) {
                return $image;
            }

            return "<a  href='$image' target='_blank'><img src='$image' class='img img-response' style='width: 80px;'></a>";
        }
        if ($html == false) {
            return $default;
        }

        if ($default != null) {
            return "<a  href='$default' target='_blank'><img src='$default' class='img img-response' style='width: 80px;'></a>";
        }

        return "<span  class='badge badge-warning'>Gambar tidak tersedia</span>";
    }

    public static function asFileLink($link, $default = null)
    {
        $image = Yii::getAlias("@file/$link");
        if (file_exists(Yii::getAlias("@webroot/uploads/$link")) && Yii::getAlias("@webroot/uploads/$link") != Yii::getAlias("@webroot/uploads/")) {
            return $image;
        }
        if ($default != null) return $default;
        return null;
    }

    public static function asIdtime($time)
    {
        return Tanggal::getTimeReadable($time, true);
    }

    public static function asDownload($link)
    {
        $absolutelink = Yii::getAlias("@file/$link");
        if (\app\components\Constant::checkFile($link)) {
            return "<a href='$absolutelink' class='btn btn-primary text-white' target='_blank'>Download</a>";
        }
        return "<span  class='badge badge-warning'>File tidak tersedia</span>";
    }

    public static function asIddate($date)
    {
        if ($date == null) {
            return "-";
        }

        $withHour = true;
        $arr = explode(" ", $date);
        if (count($arr) == 1) {
            $withHour = false;
        }
        $time = strtotime($date);
        $padTime = str_pad($time, 12, "0", STR_PAD_LEFT);
        return ($withHour ? date("d ", $time) . Tanggal::getBulan(date($time)) . date(" Y, H:i", $time) : date("d ", $time) . Tanggal::getBulan(date($time)) . date(" Y", $time));
    }

    public static function asRp($value, $withspan = false)
    {
        if ($value != "") {
            return Angka::toReadableHarga($value, $withspan);
        } else {
            return "-";
        }
    }
}
