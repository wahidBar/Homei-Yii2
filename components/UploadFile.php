<?php

namespace app\components;

use Throwable;
use Yii;
use yii\helpers\FileHelper;

trait UploadFile
{
    public static function uploadImage($file, $uploadTo = "random", $setup = [])
    {
        $default_setup = [
            'MAX_SIZE' => 1024 * 1024 * 5,
            'ALLOWED_EXTENSION' => ['jpg', 'png', 'jpeg', 'gif', 'bmp'],
        ];

        $setup = array_merge($default_setup, $setup);

        $jenis_konten = $file->type;

        if (preg_match("/image/", $jenis_konten)) {
            $realpath_dir = Yii::getAlias("@webroot/uploads/{$uploadTo}/");
            if (file_exists($realpath_dir) == false) {
                mkdir($realpath_dir, 0777, true);
            }

            $file_sementara = $file->tempName;
            $ext = end(explode(".", $file->name));
            if (in_array(strtolower($ext), $setup['ALLOWED_EXTENSION']) == false) {
                return (object) [
                    "success" => false,
                    "message" => Yii::t("cruds", "Ekstensi file tidak berada dalam list yang diperbolehkan"),
                ];
            }

            $namaFile = date("Ymd") . "_" . sha1(rand(0, 9999)) . ".{$ext}";
            $file_dipermanenkan = $realpath_dir . $namaFile;
            $filename = $file_sementara;
            $percent = 1;

            // jiplak resolusi
            // pendeteksian ini masih bisa lolos dgn teknik RGB
            $size = getimagesize($filename); //diambil dari file temp, bukan $_FILE['mime']
            $width = $size[0];
            $height = $size[1];
            $mime = $size['mime'];

            //jika butuh memperkecil gambar
            $new_width = $width * $percent;
            $new_height = $height * $percent;
            // patenkan width
            $new_width = 800;
            $new_height = $width == 0 ? 0 : $height * $new_width / $width;

            // buat gambar baru

            try {
                if (preg_match('/png|jpeg|jpg|gif/', $mime)) {
                    $image_p = imagecreatetruecolor($new_width, $new_height);
                    imagealphablending($image_p, false);
                    imagesavealpha($image_p, true);
                    $transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
                    imagefilledrectangle($image_p, 0, 0, $new_width, $new_height, $transparent);
                    ini_set('memory_limit', '512M');
                    if ((preg_match('/jpg/', $mime)) || (preg_match('/jpeg/', $mime))) {
                        $image = imagecreatefromjpeg($filename);
                    }
                    if (preg_match('/png/', $mime)) {
                        $image = imagecreatefrompng($filename);
                    }
                    if (preg_match('/gif/', $mime)) {
                        $image = imagecreatefromgif($filename);
                    }
                }

                if (!@imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height)) {
                    $image_p = imagecreate(200, 100);
                    $bg = imagecolorallocate($image_p, 255, 255, 255);
                    $black = imagecolorallocate($image_p, 0, 0, 0);
                    imagestring($image_p, 5, 2, 2, 'Gambar Korupsi', $black);

                    return (object) [
                        "success" => false,
                        "message" => "Gambar Korup",
                    ];
                }

                // Output
                imagepng($image_p, $file_dipermanenkan, 9);
            } catch (Throwable $th) {
                return (object) [
                    "success" => false,
                    "message" => $th->getMessage(),
                ];
            }

            return (object) [
                "success" => true,
                "filename" => "{$uploadTo}/{$namaFile}",
            ];
        } else {
            // return static::uploadFile($file, $uploadTo);
            return (object) [
                "success" => false,
                "message" => "Jenis file yang anda unggah bukan gambar.",
            ];
        }
    }

    public static function uploadFile($file, $uploadTo = "random", $options = [])
    {
        $default_setup = [
            'MAX_SIZE' => 1024 * 1024 * 15,
            'ALLOWED_EXTENSION' => [
                "jpg",
                "jpeg",
                "png",
                "gif",
                "pdf",
                "doc",
                "docx",
                "ppt",
                "pptx",
                "xls",
                "xlsx",
                "mp3",
                "m4a",
                "wav",
                "mp3",
                "m4a",
                "wav",
                "mp4",
                "m4v",
                "mpg",
                "wmv",
                "mov",
                "avi",
                "swf",
                "ins",
                "isf",
                "te",
                "xbk",
                "ist",
                "kmz",
                "kes",
                "flp",
                "wxr",
                "xml",
                "fjsw",
                "zip",
                "epub",
            ],
        ];

        $options = array_merge($default_setup, $options);

        $realpath_dir = Yii::getAlias("@webroot/uploads/{$uploadTo}/");
        if (file_exists($realpath_dir) == false) {
            mkdir($realpath_dir, 0777, true);
        }

        $ext = end(explode(".", $file->name));

        if (in_array($ext, $options['ALLOWED_EXTENSION']) == false) {
            return (object) [
                "success" => false,
                "message" => "File ekstensi tidak boleh kosong",
            ];
        }

        $namaFile = sha1(rand(0, 9999)) . ".{$ext}";

        $file->saveAs("{$realpath_dir}/{$namaFile}");
        return (object) [
            "success" => true,
            "filename" => "{$uploadTo}/{$namaFile}",
        ];
    }

    public static function deleteOne($filename)
    {
        if (Constant::checkFile($filename)) {
            $real_path = Yii::getAlias("@webroot/uploads/$filename");
            unlink($real_path);
        }
    }
}
