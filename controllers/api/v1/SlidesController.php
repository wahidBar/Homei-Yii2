<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "SlidesController".
 * Modified by Defri Indra
 */

class SlidesController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        $data = [
            [
                "icon" => "dashboard",
                "component" => "informasi",
                "Judul" => "Dashboard"
            ],
            [
                "icon" => "file",
                "component" => "dokumen_desain",
                "Judul" => "Dokumen Desain"
            ],
            [
                "icon" => "money",
                "component" => "keuangan",
                "Judul" => "Keuangan"
            ],
            [
                "icon" => "image",
                "component" => "galery",
                "Judul" => "Galeri"
            ],
            [
                "icon" => "line-chart",
                "component" => "progress",
                "Judul" => "Progress"
            ],
            [
                "icon" => "camera",
                "component" => "cctv",
                "Judul" => "CCTV"
            ],
            [
                "icon" => "check-square-o",
                "component" => "approval",
                "Judul" => "Approval Pekerjaan"
            ],
            [
                "icon" => "list",
                "component" => "termin",
                "Judul" => "Termin"
            ],
            // [
            //     "icon" => "arrow-down",
            //     "component" => "keuangan_masuk",
            //     "Judul" => "Keuangan Masuk"
            // ],
            // [
            //     "icon" => "arrow-up",
            //     "component" => "keuangan_keluar",
            //     "Judul" => "Keuangan Keluar"
            // ],
        ];

        return $data;
    }
}
