<?php

use app\components\Constant;
use yii\helpers\VarDumper;

\Yii::setAlias("@root_path", dirname($_SERVER["SCRIPT_FILENAME"]));
\Yii::setAlias("@root_url", dirname($_SERVER["SCRIPT_NAME"]));
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
\Yii::setAlias("@root_path", dirname($_SERVER["SCRIPT_FILENAME"]));
\Yii::setAlias("@root_url", dirname($_SERVER["SCRIPT_NAME"]));
\Yii::setAlias("@domain", "{$protocol}{$_SERVER['HTTP_HOST']}:{$_SERVER["SERVER_PORT"]}");
\Yii::setAlias("@link", \Yii::getAlias("@domain") . \Yii::getAlias("@root_url"));
\Yii::setAlias("@file", \Yii::getAlias("@link/uploads"));

if (function_exists('dd') == false) {

    function dd($val)
    {
        echo "<pre>";
        VarDumper::dump($val);
        echo "</pre>";
        die;
    }
}

if (function_exists('strposa') == false) {

    /**
     * find-needle-string-from-array
     * https://stackoverflow.com/a/9220624/15503548
     */
    function strposa($haystack, $needles = array(), $offset = 0)
    {
        $chr = array();
        foreach ($needles as $needle) {
            $res = strpos($haystack, $needle, $offset);
            if ($res !== false) {
                $chr[$needle] = $res;
            }
        }
        if (empty($chr)) {
            return false;
        }

        return count($chr);
    }
}

if (function_exists('toastSuccess') == false) {
    function toastSuccess($message)
    {
        \Yii::$app->session->addFlash("success", $message);
    }
}

if (function_exists('toastError') == false) {
    function toastError($message)
    {
        \Yii::$app->session->addFlash("error", $message);
    }
}

if (function_exists('fieldUploadImage') == false) {
    function fieldUploaImage($model, $form, $field, $options = [])
    {
        $default_options = [
            "with_preview" => true,
            "column" => 1
        ];

        $options = array_merge($default_options, $options);

        $template = "";
        if ($options['with_preview'] && $model->$field != null) :
            $image_template = "<div class=\"col-md-12 text-center\">";
            $image_template .= Yii::$app->formatter->asMyImage($model->$field);
            $image_template .= '</div>';
        endif;

        $template .= $form->field($model, $field, \app\components\Constant::COLUMN($options['column'], true, $image_template))->widget(\kartik\file\FileInput::class, [
            'options' => ['accept' => $model::DEFAULT_FILE_VALIDATION['ALLOWED_MIMES']],
            'pluginOptions' => [
                'allowedFileExtensions' => $model::DEFAULT_FILE_VALIDATION['ALLOWED_EXTENSION'],
                'maxSize' => $model::DEFAULT_FILE_VALIDATION['MAX_SIZE'],
            ],
        ]);

        return $template;
    }
}
