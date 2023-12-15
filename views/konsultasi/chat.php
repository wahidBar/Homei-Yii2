<?php

use app\components\Constant;
use app\models\KonsultasiChat;
use yii\helpers\Url;
use yii\web\View;


$style = <<<css
h2.page-title {
    display: none;
}

.card-header .contact-profile {
    position: relative;
    font-size: 1.2rem;
}

.card-header .contact-profile .contact-status.online {
    top: 0px;
    left: 5px;
}

.card-header .contact-profile img {
    display: inline-block;
    width: 4rem;
    height: auto;
    border-radius: 100%;
    margin-right: 1rem;
}

.card-header .contact-profile .text {
    display: inline-block;
    vertical-align : middle;
    width: calc(100% - 5.4rem)
} 

.card-header .contact-profile .text p {
    margin: 0;
}

.card-header .contact-profile .text p.phone {
    color: #aaa;
    font-size: .7rem;
}

css;

$this->registerCss($style);

?>
<?php if (Yii::$app->request->isAjax == false) : ?>
    <div id="chat-content" class="row">
    <?php endif ?>
    <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 mb-3">
        <?= $this->render('_chat/sidebar', compact('model', 'chat_active')) ?>
    </div>
    <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 mb-3">
        <div class="card card-default">
            <?php if ($chat_active != null) : ?>
                <?= $this->render('_chat/content', compact('model', 'user', 'chat_active', 'list_chat', 'me')) ?>
            <?php else : ?>
                <div class="card-header">
                    <div class="contact-profile">
                        <img src="<?= Constant::DEFAULT_IMAGE ?>" alt="" />
                        <div class="text">
                            <p><?= Yii::t("cruds", "Pengguna") ?></p>
                            <p class="phone"><?= Yii::t("cruds", "No Telp Belum diatur") ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-body content-message text-center">
                    <h5><?= Yii::t("cruds", "Silahkan pilih chat") ?></h5>
                    <p><?= Yii::t("cruds", "Silahkan pilih chat dari sidebar") ?></p>
                </div>
            <?php endif ?>
        </div>
    </div>

    <?php if (Yii::$app->request->isAjax == false) : ?>
    </div>
<?php endif ?>

<?php

$this->registerJsFile("//code.jquery.com/jquery-2.2.4.min.js", ["position" => View::POS_BEGIN]);
$this->registerJsFile(Url::to("homepage/js/moment.min.js"), ["position" => View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->params['socket_protocol_asset'] . Yii::$app->params['socket_host'] . "/socket.io/socket.io.js", ["position" => View::POS_BEGIN]);
//$this->registerJsFile(Yii::$app->params[ Yii::$app->request->baseUrl. "/socket.io/socket.io.js", ["position" => View::POS_BEGIN]);
/**
 * Ajax load ketika klik list chat di sidebar
 */
$baseUrl = Url::to(['/konsultasi/index', 'ticket' => '']);
$script = <<<js

var socket = undefined;

function loaddata(ticket) {
    let myTicket = ticket;
    if (typeof socket !== 'undefined') {
        socket.disconnect();
    }
    // console.log("SOCKET = "+ socket)
    const myCallback = function(result) {
        let url = '$baseUrl' + myTicket,
            title = "Konsultasi " + myTicket;
        $('#chat-content').html(result)
        $('#count-'+ticket).text(0);
        document.title = title;
        window.history.pushState({"html":result,"pageTitle":title},"", url);
    }
    $.ajax({
        'url': '$baseUrl' + ticket,
        'success' : myCallback
    })
}
js;

$this->registerjs($script, View::POS_BEGIN);


/**
 * Update total chat di header
 */

$user = Constant::getUser();
if ($user->role_id == Constant::ROLE_KONSULTAN) :
    $new_count = KonsultasiChat::find()->joinWith(['konsultasi'])->andWhere([
        'and',
        ['!=', 't_konsultasi_chat.user_id', Yii::$app->user->id],
        [
            'read' => 0,
            't_konsultasi.id_konsultan' => $user->id,
        ],
    ])->count();
else :
    $new_count = 0;
endif;
$script = <<<js
console.log($('#header-count-chat').length)
if($('#header-count-chat').length) {
    $('#header-count-chat').text($new_count);
}
js;
$this->registerJs($script);
