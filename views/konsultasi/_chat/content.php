<?php

use app\components\Constant;
use app\components\Tanggal;
use dmstr\helpers\Html;
use yii\helpers\Url;

$unread = 1;

$style = <<<css

.card-body.content-message {
    padding: 0;
    min-height: 67vh;
}

ul#chat {
    list-style-type: none;
    margin: 0;
    padding: 0 .5rem;
    overflow-y: auto;
    height: 58vh;
}

ul#chat .replies img ,
ul#chat .sent img {
    width: 3rem;
    border-radius: 100%;
}

ul#chat li {
    display: flex;
    margin: 1rem 0;
}

ul#chat li .content-message .datet {
    font-size: .7rem;
    color: #bbb;
}

ul#chat li .content-message {
    padding: .5rem;
    margin: 0 .8rem;
    border-radius: .5rem;
    vertical-align: middle;
    background-color: #eaeaea;
    position: relative;
}

ul#chat .sent {
    flex-direction: row-reverse;
    
}

ul#chat li.sent .content-message::after {
    content: "";
    border-width: 10px;
    border-style: solid;
    border-color:  transparent transparent transparent #2E2871;
    position: absolute;
    right: -18px;
    top: 10px;
}

ul#chat li.replies .content-message::after {
    content: "";
    border-width: 10px;
    border-style: solid;
    border-color:  transparent #eaeaea transparent transparent;
    position: absolute;
    left: -18px;
    top: 10px;
}

ul#chat li.sent .content-message {
    background: #2E2871;
    color: whitesmoke;
}

ul#chat li .content-message {
    font-size: .9rem;
}

ul#chat li .content-message p {
    margin: 0;
    margin-bottom: 0;
}

#messages {
    position: relative;
}
#messages .message-input {
    display: block;
    padding: 1rem 0;
}

#messages .message-input textarea {
    width: 83%;
    vertical-align: middle;
    border: 1px solid #aaa;
    border-radius: 1rem;
    padding: .5rem 1rem;
}

#messages .message-input button {
    width: 7.5%;
    vertical-align: middle;
    border: 0;
    color: whitesmoke;
    border-radius: 1rem;
    padding: .5rem .2rem;
}

ul#chat li.chat__info {
    display: block;
    background-color: #aaa;
    font-size: .7rem;
    width: fit-content;
    padding: .2rem .5rem;
    margin: 1rem auto;
    border-radius: 1rem;
    color: white;
}


@media only screen and (max-width: 1234px) {
    #messages .message-input textarea {
        width: 70%;
    }
    #messages .message-input button {
        width: 14%;
    }
}

@media only screen and (max-width: 995px) {
    #messages .message-input textarea {
        width: 60%;
    }
    #messages .message-input button {
        width: 18%;
    }
}

@media only screen and (max-width: 768px) {
    #messages .message-input textarea {
        width: 70%;
    }
    #messages .message-input button {
        width: 14%;
    }
}

@media only screen and (max-width: 492px) {
    #messages .message-input textarea {
        width: 60%;
    }
    #messages .message-input button {
        width: 18%;
    }
}


css;

$this->registerCss($style);
?>
<script>
    // script js to open link in new window
    function openInNewWindow(url) {
        var win = window.open(url, '_blank');
        win.focus();
    }
</script>
<div class="card card-default">
    <div class="card-header">
        <div class="contact-profile">
            <img src="<?= Yii::$app->formatter->asMyImage($user->photo_url, false, Constant::DEFAULT_IMAGE) ?>" alt="" />
            <div class="text">
                <p><?= $user->name ?? Yii::t("cruds", "nama belum Diatur") ?></p>
                <p class="phone"><?= $user->no_hp ?? Yii::t("cruds", "No Telp Belum diatur") ?></p>
            </div>
        </div>
    </div>
    <div class="card-body content-message">
        <div id="messages" class="content">
            <div class="messages">
                <ul id="chat">
                    <?php
                    foreach ($list_chat as $chat) : ?>
                        <?php if ($chat->user_id == $me->id) : ?>
                            <li class="sent">
                                <div class="img_container">
                                    <img src="<?= Yii::$app->formatter->asMyImage($me->photo_url, false, Constant::DEFAULT_IMAGE) ?>" alt="" />
                                </div>
                                <div class="content-message">
                                    <p><?= str_replace("\n", '<br/>', $chat->body) ?></p>
                                    <span class="datet"><?= Tanggal::toReadableDate($chat->created_at) ?></span>
                                </div>
                            </li>
                        <?php else : ?>

                            <?php if ($chat->read == 0 && $unread) : ?>
                                <li class="chat__info">
                                    <?= Yii::t("cruds", "Pesan Belum Dibaca") ?>
                                </li>
                            <?php endif ?>
                            <li class="replies">
                                <div class="img_container">
                                    <img src="<?= Yii::$app->formatter->asMyImage($user->photo_url, false, Constant::DEFAULT_IMAGE) ?>" alt="" />
                                </div>
                                <div class="content-message">
                                    <p><?=  str_replace("\n", '<br/>', $chat->body) ?></p>
                                    <span class="datet"><?= Tanggal::toReadableDate($chat->created_at) ?></span>
                                </div>
                            </li>
                        <?php endif ?>
                    <?php endforeach; ?>
                    <?php if ($chat_active->is_active == 0) : ?>
                        <li class="chat__info">
                            <?= Yii::t("cruds", "Sesi Konsultasi ini telah berakhir") ?>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
            <div class="message-input">
                <div class="wrap">
                    <textarea <?= $chat_active->is_active == 0 ? "disabled" : "" ?> placeholder="<?= Yii::t("cruds", "Tulis pesan anda...") ?>"></textarea>
                    <?= Html::button(Yii::t("cruds", "Link"), [
                        "class" => "submit",
                        "style" => "border: 1px solid #eee; background:#006ecd",
                        "onclick" => "openInNewWindow('" . Url::to(['isian-lanjutan/view', 'id' => $chat_active->id_isian_lanjutan]) . "')"
                    ]) ?>
                    <button class="submit" style="border: 1px solid #aaa"><i class="fa fa-paper-plane" style="color: #555" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

$script = <<<js
function scrollDown() {
    $("ul#chat").animate({
        scrollTop: $('ul#chat')[0].scrollHeight
    }, "fast");
}

scrollDown();

js;

$this->registerjs($script);

$nodelink = Yii::$app->params['socket_protocol'] . Yii::$app->params['socket_host'];
$sessid = Yii::$app->session->getId();
$photo_user = Yii::$app->formatter->asMyImage($user->photo_url, false, Constant::DEFAULT_IMAGE);
$photo_me = Yii::$app->formatter->asMyImage($me->photo_url, false, Constant::DEFAULT_IMAGE);
$sound = \Yii::$app->params['socket_notification_sound'];

if ($chat_active->is_active) {
    $emitmasukchat = <<<emit
    socket.emit("joining msg", "$name");
    socket.on('connect', () => {
        console.log(socket.id); // an alphanumeric id...
    });
    socket.on("private message", (obj) => {
        var audio = new Audio('$sound');
        audio.play();
        buildMessage(obj.data.body, obj.data.created_at, obj.data.user_id === $me->id ? "sent" : "replies", obj.data.user_id == $me->id ? "$photo_me" : "$photo_user");
    });
emit;
} else $emitmasukchat = "";

$scriptJs = <<<js
socket = io("$nodelink", {
    query: 'token=$sessid&chat_id=$chat_active->ticket',
    // transports: ["websocket"],
    'reconnection': true,
    'reconnectionDelay': 1000,
    'reconnectionDelayMax' : 5000,
    'reconnectionAttempts': 5
});

$emitmasukchat

function generateDate(){
    var date = new Date(); // M-D-YYYY
    var MONTH = ["", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sept", "Okt", "Nov", "Des"];
    var h = date.getHours();
    var i = date.getMinutes();
    var d = date.getDate();
    var m = date.getMonth() + 1;
    var y = date.getFullYear();

    var dateString = (d <= 9 ? '0' + d : d) + ' ' + MONTH[m] + ' ' + y + ", " + h + ":" + i;
    return dateString;
}

function newMessage() {
    let message = $(".message-input textarea").val();
    let date = generateDate();
    if ($.trim(message) == '') {
        return false;
    }
    
    socket.emit("private message", message);
    buildMessage(message, date, "sent", "$photo_me");
}

function buildMessage(message, date, type = "replies", photo = "$photo_user") {
    if ($.trim(message) == '') return false;
    message = message.replaceAll("\\n", "<br>");

    $(`
    <li class="\${type}">
        <div class="img_container">
            <img src="\${photo}" alt="" />
        </div>
        <div class="content-message">
            <p>\${message}</p>
            <span class="datet">\${date}</span>
        </div>
    </li>`).appendTo($('ul#chat'));
    if(type == "sent") $('.message-input textarea').val(null);
    scrollDown();
};

$('.submit').click(function() {
    newMessage();
});

$(window).on('keydown', function(e) {
    if (e.which == 13 && !e.shiftKey) {
        newMessage();
        return false;
    }
});
js;

$this->registerJs($scriptJs);
?>