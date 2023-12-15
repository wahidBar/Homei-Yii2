<?php

namespace app\components;

use Yii;

class Constant
{
    const ROLES = [
        "sa" => 1,
        "admin" => 2,
        "agent" => 4,
        "user" => 3,
        "user" => 3,
        "konsultan" => 5,
        "supplier" => 9,
    ];

    const ROLE_KONSULTAN = 4;
    const ROLE_KONTRAKTOR = 5;
    const ROLE_KEUANGAN = 6;

    const DEFAULT_IMAGE = "https://homei.co.id/web/uploads/default.png";

    const COLOR = [
        "purple",
        "green",
        "red",
        "blue",
        "yellow",
        "orange",
        "maroon",
        "black",
    ];

    const ROLE_TUKANG_SAMEDAY = 8;

    const COLUMN_DYNAMIC = [
        'template' => '
				{input}
			',
        'options' => ['class' => '', 'style' => 'padding:0px;'],
    ];

    public static function BUTTON_GROUP($template, $label = "...", $options = [])
    {
        $doptions = [
            "id" => "btnGroupDrop1",
            "class" => "btn btn-primary dropdown-toggle",
            "style" => "text-align: center",
        ];

        $options = array_merge($doptions, $options);
        return "<div class=\"btn-group\" role=\"group\">
        <button id=\"{$options['id']}\" type=\"button\" class=\"{$options['class']}\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
          $label
        </button>
        <div class=\"dropdown-menu\" aria-labelledby=\"{$options['id']}\">
        $template
        </div>
    </div>";
    }

    public static function generateRandomColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    public static function purifyPhone($phone)
    {
        if ($phone == "") {
            return null;
        }

        $remove_white_space = str_replace(" ", "", $phone);
        $filter_phone = str_replace("-", "", $remove_white_space);

        if (substr($filter_phone, 0, 2) === "08") {
            $phone = substr($filter_phone, 1);
            $phone = "62" . $phone;
        } else if (substr($filter_phone, 0, 2) === "+") {
            $phone = substr($filter_phone, 1);
        }

        return $phone;
    }

    public static function COLUMN_SWITCH_ROW($number)
    {
        switch ($number) {
            case 1:
                $row = 12;
                break;
            case 2:
                $row = 6;
                break;
            case 3:
                $row = 4;
                break;
            case 4:
                $row = 3;
                break;
            default:
                $row = 6;
                break;
        }
        return $row;
    }

    /**
     * Modify Field size
     * @param int $number number of column
     * @param boolean $withLabel Is Field rendering with label
     * @return array
     */
    public static function COLUMN($number = 2, $withLabel = true, $special_for_image = "")
    {
        $row = self::COLUMN_SWITCH_ROW($number);

        $template = "<div class=\"col-lg-12\">";
        if ($withLabel)
            $template .= '<div class="col-md-12">{label}<span style="color: #aaa;display:inline-block;font-size:.7rem">{hint}</span></div>';
        if ($special_for_image)
            $template .= '<div class="col-md-12">' . $special_for_image . '</div>';
        $template .= "<div class=\"col-md-12\">{input}{error}</div>";
        $template .= "</div>";

        return [
            'template' => $template,
            'labelOptions' => ['class' => "control-label"],
            'horizontalCssClasses' => ['hint' => ''],
            'options' => ['class' => "col-md-{$row}", 'style' => 'padding:0px;'],
        ];
    }

    public static function generateRandomString($length = 32, $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ")
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function flattenError($errors)
    {
        $flatten = [];

        foreach ($errors as $errorAttr) :
            foreach ($errorAttr as $error) :
                $flatten[] = "$error";
            endforeach;
        endforeach;

        if ($flatten == []) {
            return null;
        }

        return $flatten[0];
    }

    public static function uuid($suffix, $lenght = 13)
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        return $suffix . substr(bin2hex($bytes), 0, $lenght);
    }

    public static function isMethod($method)
    {
        if (gettype($method) == "array") {
            foreach ($method as $_m) {
                if (Yii::$app->request->method == strtoupper($_m)) {
                    return true;
                }
            }
        } else {
            if (Yii::$app->request->method == strtoupper($method)) {
                return true;
            }
        }
        return false;
    }

    public static function setting($attribute)
    {
        return null;
        // $model = WebProfile::findOne(['id'=>1]);
        // if($model==null) return null;
        // return $model->$attribute;
    }

    public static function getUser()
    {
        return \Yii::$app->user->identity;
    }

    /**
     * get List Id from Model
     */
    public static function getIDs($model, $attribute = "id")
    {
        $list = [];
        foreach ($model as $_m) {
            if (gettype($_m) == "array") {
                $list[] = $_m[$attribute];
            } else {
                $list[] = $_m->$attribute;
            }
        }

        return $list;
    }

    /**
     * get Random data from array
     */
    public static function getRandomFrom($array)
    {
        $random = random_int(0, count($array) - 1);
        return $array[$random];
    }

    public static function checkFile($filename)
    {
        $folder_path = Yii::getAlias("@webroot/uploads/");
        $default = Yii::getAlias("@webroot/uploads/default.png");
        $real_path = Yii::getAlias("@webroot/uploads/$filename");
        $existing_file = file_exists($real_path);

        if ($existing_file) {
            if ($folder_path != $real_path && $real_path != $default) {
                return true;
            }
        }

        return false;
    }

    public static function isUriContain($uri = ["/view"])
    {
        foreach ($uri as $ur) {
            $position = strpos($_SERVER['REQUEST_URI'], $ur);
            if (is_int($position)) {
                return true;
            }
        }
        return false;
    }

    public static function getPaginationSummary($pagination, $count)
    {
        if ($count == 0) return "Menampilkan $count dari total $count data ";
        $start = $pagination->offset + 1;
        $end = ($count < $pagination->limit) ? $count : $pagination->offset + $pagination->limit;
        $end = ($end > $count) ? $count : $end;
        return "Menampilkan $start-$end dari total $count data ";
    }

    static function calculatorAllowedSymbol()
    {
        return [
            "(",
            ")",
            "+",
            "*",
            "-",
            "/",
        ];
    }

    static function calculatorAllowedVar()
    {
        return \yii\helpers\ArrayHelper::map(\app\models\MasterVariableHitungan::find()->all(), "id", "nama");
    }

    static function calculatorAllowedChar()
    {
        return array_merge(
            self::calculatorAllowedVar(),
            self::calculatorAllowedSymbol()
        );
    }
}
