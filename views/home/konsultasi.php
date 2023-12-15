<?php

use app\components\annex\Alert;
use app\components\Constant;
use richardfan\widget\JSRegister;
use yii\bootstrap\Html;

date_default_timezone_set('Asia/Jakarta');

$this->registerCssFile("@web/homepage/css/chat.css");
$this->registerCssFile("@web/homepage/css/gallery.css");
$this->registerCss("
main {
	margin: 0vh auto;
	max-width: 100%;
	display: grid;
	grid-gap: 5px;
	grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
	grid-auto-rows: 250px;
	grid-auto-flow: dense;
}

.style {
	text-align: center;
	padding: 1rem 0;
	color: white;
	text-transform: uppercase;
	background: rgba(0,0,0,.2);
	overflow: hidden;
	padding: 0;
	display: flex;
	align-items: stretch;
	justify-content: center;
    position: relative;
}
textarea {
    resize: none;
    height: 50px;
}

.style img {
	width: 100%;
	height: 100%;
	display: block;
	-o-object-fit: cover;
	   object-fit: cover;
	-o-object-position: center;
	   object-position: center;
	transition: all .5s;
}

.text-img {
    position: absolute;
    bottom: 8px;
    left: 16px;    
}

.text-img h2{
    font-size : 2rem;
    color: #fff;
    text-align : left;
}

.horizontal {
	grid-column: span 2;
}

.vertical {
	grid-row: span 2;
}

.big {
	grid-column: span 2;
	grid-row: span 2;
}
.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}
");
$my_id = Constant::getUser()->id;
?>

<?php
$setting = \app\models\SiteSetting::find()->all();
?>
<!-- Navigation -->
<section class="navigation">
    <div class="parallax parallax--nav" style="background-image: url(<?= \Yii::$app->request->baseUrl . "/uploads/" . $setting[0]['gambar_header'] ?>);">
        <div class="overlay"></div>
        <div class="container clearfix">
            <div class="row">
                <div class="col-12">
                    <h2>
                        <?= $setting[0]['tagline']; ?>
                    </h2>
                </div>
                <div class="col-12">
                    <p>
                        <?= $setting[0]['tagline2']; ?>
                    </p>
                </div>
                <div class="col-12">
                    <ul class="breadcrumbs ul--inline ul--no-style">
                        <li>
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home">Home</a>
                        </li>
                        <span>/</span>
                        <li class="active">
                            <a href="#">Konsultasi</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<div class="container">
    <div class="row pb-5">
        <div class="col-12 pt-5 pb-0" id="notif">
            <?php if ($model->id_konsultan == null) : ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Konsultan sedang Offline, Mohon Tunggu Sebentar.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif ?>
            <?= Alert::widget() ?>
        </div>
        <?php
        //get date from datetime
        $date = date('Y-m-d', strtotime($model->created_at));
        //get current date
        $current_date = date('Y-m-d');
        //calculate date
        $difference = strtotime($current_date) - strtotime($date);
        //convert to days
        $days = floor($difference / (60 * 60 * 24));
        // echo $days; die;
        if ($days > 1) {
        ?>
            <div class="col-12 pt-0 pb-0">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Konsultasi terakhir Anda <?= $days ?> hari yang lalu. Untuk memulai konsultasi baru klik tombol akhiri chat, lalu klik tombol konsultasi kembali.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        <?php
        }
        ?>
        <?php
        if ($model->is_active != 1) {
        ?>
            <div class="col-12 pt-0 pb-0">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Konsultasi anda tidak aktif. Untuk memulai konsultasi baru klik <strong><a href="<?= \Yii::$app->request->baseUrl . "/home/formulir-konsultasi" ?>"> disini</a></strong> atau klik menu konsultasi diatas.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        <?php
        }
        ?>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="mt-4">
                <div class="card text-white mb-3" style="background-color: #f8d20a">
                    <div class="card-header text-center text-dark">Informasi Konsultan</div>
                    <div class="card-body">
                        <div class="avatar text-center p-2">
                            <img class="rounded-circle" src="<?= Yii::$app->formatter->asMyImage($konsultan->photo_url, false, Constant::DEFAULT_IMAGE) ?>" width="100" alt="" style="height:100px">
                        </div>
                        <p class="card-text">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="text-dark font-weight-bold">Nama</td>
                                        <td class="text-dark" id="nama_konsultan1">
                                            <?= $konsultan->name ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-dark font-weight-bold">Email</td>
                                        <td class="text-dark" id="email_konsultan">
                                            <?= $konsultan->email ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-dark font-weight-bold">Status Konsultasi</td>
                                        <td class="text-dark">
                                            <?php
                                            if ($model->is_active == 1) {
                                                echo "Aktif";
                                            } else {
                                                echo "Tidak Aktif";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </p>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-12">
            <!-- chat -->
            <div class="page">
                <div class="canvas-chat" style="width:100%;height: 100%;">
                    <div class="screen">
                        <div class="screen-container">
                            <div class="status-bar">
                                <div class="time"></div>
                            </div>
                            <div class="chat">
                                <div class="chat-container">
                                    <div class="user-bar">
                                        <div class="avatar">
                                            <img src="<?= Yii::$app->formatter->asMyImage($konsultan->photo_url, false, Constant::DEFAULT_IMAGE) ?>" alt="Avatar" style="width: 30px; height:30px">
                                        </div>
                                        <div class="name">
                                            <span id="nama_konsultan2"><?= $konsultan->name ?></span>
                                            <span class="status" id="status_konsultan"><?= $konsultan->is_active ? "online" : "offline" ?></span>
                                        </div>
                                    </div>
                                    <div class="conversation">
                                        <div class="conversation-container" id="clear-chat">

                                            <?php
                                            foreach ($list_chat as $chat) : ?>
                                                <?php if ($chat->user_id == $my_id) : ?>
                                                    <div class="message sent"><?= str_replace("\n", "<br/>", $chat->body) ?><span class="metadata"><span class="time"><?= Yii::$app->formatter->asIddate($chat->created_at) ?></span><span class="tick"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck" x="2047" y="2061">
                                                                    <path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#92a58c"></path>
                                                                </svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076">
                                                                    <path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#f00"></path>
                                                                </svg></span></span></div>
                                                <?php else : ?>
                                                    <div class="message received"><?= str_replace("\n", "<br/>", $chat->body) ?><span class="metadata"><span class="time"><?= Yii::$app->formatter->asIddate($chat->created_at) ?></span><span class="tick"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck" x="2047" y="2061">
                                                                    <path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#92a58c"></path>
                                                                </svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076">
                                                                    <path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#f00"></path>
                                                                </svg></span></span></div>
                                                <?php endif ?>
                                            <?php endforeach; ?>

                                        </div>
                                        <form class="conversation-compose">
                                            <!-- <div class="emoji">
                                                <i class="zmdi zmdi-mood"></i>
                                            </div> -->
                                            <textarea <?= ($model->is_active == 0) ? "disabled='disabled'" : "" ?> class="input-msg ml-2 pl-3" name="input" placeholder="Tulis Pesan Anda" autocomplete="off" autofocus></textarea>
                                            <!-- <div class="photo">
                                                <i class="zmdi zmdi-camera"></i>
                                            </div> -->
                                            <button class="send">
                                                <div class="circle">
                                                    <i class="zmdi zmdi-mail-send"></i>
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <!-- Button trigger modal -->
                <div class="row">
                    <div class="col-lg-6 col-6 mt-3 mb-2">
                        <button type="button" class="btn btn-warning btn-block" id="clear">
                            Bersihkan Chat
                        </button>
                    </div>
                    <div class="col-lg-6 col-6 mt-3 mb-2">
                        <?php
                        if ($model->is_active == 1) {
                        ?>
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#confirm" id="btn-end">
                                Akhiri Konsultasi
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-danger btn-block disabled">
                                Akhiri Konsultasi
                            </button>
                        <?php } ?>
                    </div>
                </div>


                <!-- Modal -->
                <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="akhiri-konsultasi" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Akhiri Konsultasi?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin mengakhiri konsultasi?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                <a href="<?= \Yii::$app->request->baseUrl . "/home/akhiri-konsultasi?ticket=" . $model->ticket . "&end=true" ?>" class="btn btn-danger text-white">Akhiri Konsultasi</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="rencanapembangunan" tabindex="-1" role="dialog" aria-labelledby="rencana-pembangunan" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="rencana-pembangunan">Lanjutkan Mengisi Rencana Pembangunan?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin? Sesi konsultasi anda juga akan berakhir. Untuk memulai konsultasi lagi, klik menu "konsultasi" di atas.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                <a href="<?= \Yii::$app->request->baseUrl . "/home/akhiri-konsultasi?ticket=" . $model->ticket ?>" class="btn btn-info text-white">Isi Form Rencana Pembangunan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end chat -->
        </div>
    </div>

    <!-- <div class="container pt-5 pb-5">
        <p class="member-p">Puas dengan Layanan Konsultasi Kami?<br>Daftar Member dan Partner Home I Sekarang Juga!!</p>
        <a href="#" class="au-btn au-btn--big au-btn--pill au-btn--yellow au-btn--white mt-4">Daftar Member</a>
    </div> -->

    <!-- gallery -->
    <div class="container pt-4">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <h2 class="title title-3 title-3--left">
                    Galeri Kami
                </h2>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                <p>Kami menerjemahkan visi Anda menjadi kenyataan melalui kerangka kerja kreatif kami. Kami menciptakan desain estetis dan fungsional yang disesuaikan dengan semua kebutuhan dan prefensi Anda yang bertujuan untuk menciptakan kenyamanan dan menginspirasi kreativitas.</p>
            </div>
        </div>
    </div>
    <div class="d-none d-md-block">
        <main class="pb-5">
            <?php foreach ($galleries as $gal) { ?>
                <?php
                if ($gal->style == "square") {
                    $style = "";
                } else {
                    $style = $gal->style;
                }
                ?>
                <div class="style <?= $style ?> img-hover">
                    <img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gal->gambar ?>" alt="">
                    <div class="text-hover">
                        <h3><?= $gal->judul ?></h3>
                        <h4><?= $gal->keterangan ?></h4>
                    </div>
                    <a href="#"></a>
                </div>
            <?php } ?>
        </main>
    </div>
    <div class="d-block d-sm-none d-sm-block d-md-none">
        <main class="pb-5">
            <?php foreach ($hp_galleries as $gal) { ?>
                <div class="style"><img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gal->gambar ?>" alt="">
                    <div class="overlay"></div>
                    <div class="text-img">
                        <h2>
                            <?= $gal->judul ?>
                        </h2>
                        <p class="text-left">
                            <?= $gal->keterangan ?>
                        </p>
                    </div>
                </div>
            <?php } ?>
        </main>
    </div>
    <!-- end of gallery -->
</div>
<?php JSRegister::begin(); ?>
<script>
    $(document).ready(function() {
        $("#clear").click(function() {
            $("#clear-chat").empty();
        });
    });
    var stopcek = <?= $model->id_konsultan ? "true" : "false" ?>;
    let interval = setInterval(function() {
        cek();
        if (stopcek == true) {
            clearInterval(interval);
        }
    }, 10000);


    function cek() {
        $.getJSON("<?= \Yii::$app->request->BaseUrl . "/site/cronjob-search-konsultan?ticket=" . $model->ticket ?>", function(responseJSON) {
            if (responseJSON.success == true && responseJSON.data.is_active == 0) {
                $("#nama_konsultan1").html("Konsultan Belum Aktif");
                $("#nama_konsultan2").html("Konsultan Belum Aktif");
                $("#email_konsultan").html("-");
                $("#status_konsultan").html("offline");
                $("#btn-end").prop('disabled', true);
                $("#btn-next").prop('disabled', true);
            }
            if (responseJSON.success == true && responseJSON.data.is_active == 1) {
                $("#nama_konsultan1").html(responseJSON.data.name);
                $("#nama_konsultan2").html(responseJSON.data.name);
                $("#email_konsultan").html(responseJSON.data.email);
                $("#foto_konsultan").attr("src", "<?= \Yii::$app->request->baseUrl . "/uploads/" ?>" + responseJSON.data.photo_url);
                $('#notif').html('<div class="alert alert-info alert-dismissible fade show" role="alert">Konsultan telah online.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
                $("#status_konsultan").html("online");
                $("#btn-end").prop('disabled', false);
                $("#btn-next").prop('disabled', false);

                stopcek = true;
            }
        });
    }


    cek();
</script>
<?php JSRegister::end(); ?>
<?php
$socket_host = Yii::$app->params['socket_protocol'] . Yii::$app->params['socket_host'];
$this->registerJsFile("@web/homepage/js/moment.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->params['socket_protocol_asset'] . Yii::$app->params['socket_host'] . "/socket.io/socket.io.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js");
$urlSaveMessage = \yii\helpers\Url::to(['chat/save', 'ticket' => $model->ticket]);
$name = \app\components\Constant::getUser()->name;
$username = \app\components\Constant::getUser()->username;
$sessid = Yii::$app->session->getId();
$chat_id = $model->ticket;
$sound = \Yii::$app->params['socket_notification_sound'];

$js = <<<JS

// setInterval(function () {
$('.conversation-container').scrollTop($('.conversation-container')[0].scrollHeight);
// }, 1000);
/* Time */
let username = "$username";
let socket = io("$socket_host", {
    'query': 'token=$sessid&chat_id=$chat_id',
    'transports': ["websocket"],
    'reconnection': true,
    'reconnectionDelay': 1000,
    'reconnectionDelayMax' : 5000,
    'reconnectionAttempts': 5,
});
let deviceTime = document.querySelector(".status-bar .time");
let messageTime = document.querySelectorAll(".message .time");

deviceTime.innerHTML = moment().format("h:mm");

setInterval(function () {
  deviceTime.innerHTML = moment().format("h:mm");
}, 1000);

for (let i = 0; i < messageTime.length; i++) {
  messageTime[i].innerHTML = moment().format("h:mm A");
}

/* Message */

let form = document.querySelector(".conversation-compose");
let conversation = document.querySelector(".conversation-container");

form.addEventListener("submit", newMessage);
socket.emit("joining msg", "$name");
socket.on('connect', () => {
    console.log(socket.id); // an alphanumeric id...
 });

socket.on("private message", (obj) => {
    var audio = new Audio('$sound');
    audio.play();

    let message = buildMessage(obj.data.body, username == obj.data._user.username ? "sent" : "received");
    // let message = buildMessage(obj.body, "received");
    conversation.appendChild(message);
    animateMessage(message);
    $('.conversation-container').scrollTop($('.conversation-container')[0].scrollHeight);
});

function newMessage(e) {
  e.preventDefault();
  let input = e.target.input;
  console.log(input.value);

  if (input.value) {
    socket.emit("private message", input.value);
    let message = buildMessage(input.value, "sent");
    conversation.appendChild(message);
    animateMessage(message);
  }

  input.value = "";
  conversation.scrollTop = conversation.scrollHeight;
}

function buildMessage(text, type) {
  let element = document.createElement("div");
  text = text.replaceAll("\\n", "<br>");
  element.classList.add("message", type);

  element.innerHTML =
    text +
    '<span class="metadata">' +
    '<span class="time">' +
    moment().format("h:mm A") +
    "</span>" +
    '<span class="tick tick-animation">' +
    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck" x="2047" y="2061"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#92a58c"/></svg>' +
    "</span>" +
    "</span>";

  return element;
}

function animateMessage(message) {
  setTimeout(function () {
    let tick = message.querySelector(".tick");
    tick.classList.remove("tick-animation");
  }, 500);
}

JS;

$this->registerJs($js);
?>