<?php

namespace app\components;

use app\models\Notification;
use yii\helpers\Url;

class Notif
{
    public static function log($id_user, $title, $desc, $data)
    {
        try {
            $notif = new Notification();

            if ($id_user == null) {
                $notif->scenario = $notif::SCENARIO_CREATE_ADMIN;
            } else {
                $notif->scenario = $notif::SCENARIO_CREATE;
            }

            $notif->user_id = $id_user;
            $notif->title = $title;
            $notif->description = $desc;
            $notif->controller = $data['controller'];
            $notif->android_route = $data['android_route'];
            $notif->params = json_encode($data['params']);
            $notif->save();

            SendFcm::message($notif->user->fcm_token, [
                "title" => $title,
                "body" => $desc,
                "data" => $data,
            ], function ($data) {
                $data['data']['route'] = $data['data']['android_route'];
                unset($data['data']['android_route']);
                return $data;
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public static function notifList($listHtml = true)
    {
        $notifList = [];
        $totalCount = 0;

        $user = \Yii::$app->user->identity;
        $belum_dilihat = 0;

        $notif = Notification::find()->where(['user_id' => $user->id, 'read' => $belum_dilihat])->orderBy(['id' => SORT_DESC])->all();
        $count = count($notif);
        if ($count > 0) {
            // $label = '<span class="label label-danger pull-right">'.$count.'</span>';
            foreach ($notif as $n) {
                array_push(
                    $notifList,
                    '<li style="padding:.5rem">
                        <a href="' . Url::to(["/notif?id=$n->id"]) . '">
                            <i class="fa fa-users text-aqua" style="margin-right:.5rem"></i>' . $n->title . '
                            <span class="label label-danger pull-right"></span>
                        </a>
                    </li>'
                );
            }
        }

        if ($listHtml) {
            echo '
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning">' . $count . '</span>
            </a>
            <ul class="dropdown-menu" style="padding:1rem;width:300px">
                <li class="header" style="margin-bottom:1rem">You have ' . $count . ' notifications</li>
                <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                        ' . implode('', $notifList) . '
                    </ul>
                </li>
                <!-- <li class="footer"><a href="#">View all</a></li>-->
            </ul>
        ';
        } else {
            echo $totalCount;
        }
    }
}
