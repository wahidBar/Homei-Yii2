<?php

use app\components\Constant;
use yii\helpers\Url;
use yii\web\View;

$style = <<<css
ul.list-chat {
    color: #555;
    margin: 0;
    padding: 0;
    list-style-type: none;
    overflow-y: auto;
    height: 69vh;
}

img.img__side_list_chat {
    border-radius: 100%;
    width: 4rem;
    margin-right: .5rem;
}

.wrap {
    margin: 0;
    padding: 5px 15px 5px;
    position: relative;
}

.wrap .meta {
    display: inline-block;
    vertical-align: middle;
    width: calc( 100% - 5rem )
}

ul.list-chat .contact {
    transition: .4s
}

ul.list-chat .contact p.name {
    color: #555;
    margin: 0;
}

ul.list-chat .contact p.preview {
    color: #aaa;
    margin: 0;
    font-size: .7rem
}

ul.list-chat .contact.active {
    background-color: #dedede;
}

ul.list-chat .contact:hover {
    background-color: #ededed;
}

ul.list-chat .contact.active .contact-status.online {
    border: 3px solid #dedede;
}

ul.list-chat .contact:hover .contact-status.online {
    border: 3px solid #ededed;
}

.chat_belum_dibaca {
    padding: .1rem .5rem;
    position: absolute;
    top: .5rem;
    right: .5rem;
    background-color: #2E2871;
    border-radius: .5rem;
    font-size: .7rem;
}

.contact-status.online {
    content: "";
    height: 15px;
    width: 15px;
    border-radius: 100%;
    border: 3px solid whitesmoke;
    z-index: 999;
    background-color: #45A422;
    position: absolute;
    top: 10px;
    left: 15px;
}

@media only screen and (max-width: 765px) {
    ul.list-chat {
        max-height: 43vh;
    }
}

css;

$this->registerCss($style);

?>

<div class="card card-default">
    <div class="card-header">
        <h5>
            <?= Yii::t("cruds", "List Konsultasi") ?>
        </h5>
    </div>
    <div class="card-body" style="padding: 0;">
        <ul class="list-chat" id="sidebar-container">
            <?php foreach ($model as $item) : ?>
                <li id="sidebar-<?= $item->ticket ?>" class="contact <?php if ($chat_active && $item->ticket == $chat_active->ticket) :   ?> active  <?php endif ?>">
                    <div onclick="loaddata('<?= $item->ticket ?>')" style="color: #fff;text-decoration: none">
                        <div class="wrap">
                            <?php if ($item->user->is_active) : ?>
                                <span class="contact-status online"></span>
                            <?php endif ?>
                            <span id="count-<?= $item->ticket ?>" class="chat_belum_dibaca" <?= $item->getTotalChatBelumDibaca() == 0 ? "style='display: none'" : ""  ?>><?= $item->getTotalChatBelumDibaca() ?></span>
                            <img src="<?= Yii::$app->formatter->asMyImage($item->user->photo_url, false, Constant::DEFAULT_IMAGE) ?>" alt="Photo User <?= $item->user->name ?>" class="img__side_list_chat" />
                            <div class="meta">
                                <p class="name"><?= $item->user->name ?? Yii::t("cruds ", "Nama dari user ini belum di atur") ?></p>
                                <p class="preview"><?= $item->user->no_hp ?? Yii::t("cruds ", "No Telp Belum diatur") ?></p>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</div>
<?php

$scrollSidebar = "";
if ($chat_active) {
    $ticket = $chat_active->ticket;
    $scrollSidebar = <<<js
    $('#sidebar-container').animate({
        scrollTop: $('#sidebar-$ticket').offset().top - 183, // 183 adalah constant jaraknya
    }, 'fast')
js;
    $this->registerJs($scrollSidebar);
}
