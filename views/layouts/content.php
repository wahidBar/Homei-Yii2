<?php

use app\components\annex\Breadcrumbs;
use richardfan\widget\JSRegister;

?>


<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="btn-group float-right">
                        <?= Breadcrumbs::widget(
                            [
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ]
                        ) ?>
                    </div>
                    <h2 class="page-title">
                        <?php
                        if ($this->title !== null) {
                            echo \yii\helpers\Html::encode($this->title);
                        } else {
                            echo \yii\helpers\Inflector::camel2words(
                                \yii\helpers\Inflector::id2camel($this->context->module->id)
                            );
                            echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                        } ?>
                    </h2>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="content-wrapper">
            <section class="content">
                <?= $content ?>
            </section>
        </div>
    </div><!-- container -->


</div> <!-- Page content Wrapper -->

<footer class="footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; <?= date('Y') ?> <a href="#"><?= Yii::$app->params['copyright'] ?></a>.</strong> All rights
    reserved.
</footer>
<?php
$this->registerJsFile("@web/homepage/js/sweetalert2.all.min.js");


$data_flash_success = \Yii::$app->session->getFlash('success');
$data_flash_error = \Yii::$app->session->getFlash('error');


$data = [];
if (gettype($data_flash_success) == 'string') {
    $data[] = [
        "title" => "Berhasil !",
        "text" => $data_flash_success,
        "type" => "success",
    ];
} else if (gettype($data_flash_success) == "array") {
    foreach ($data_flash_success as $item) {
        $data[] = [
            "title" => "Berhasil !",
            "text" => $item,
            "type" => "success",
        ];
    }
}

if (gettype($data_flash_error) == 'string') {
    $data[] = [
        "title" => "Gagal !",
        "text" => $data_flash_error,
        "type" => "error",
    ];
} else if (gettype($data_flash_error) == "array") {
    foreach ($data_flash_error as $item) {
        $data[] = [
            "title" => "Gagal !",
            "text" => $item,
            "type" => "error",
        ];
    }
}

?>
<?php JSRegister::begin(); ?>
<script>
    yii.confirm = function(message, okCallback, cancelCallback) {
        // swal fires the callback when the user clicks on the confirm button
        Swal.fire({
            title: "Apakah anda yakin ?",
            text: message,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            closeOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                okCallback();
            }
        });
    };

    window.alert = function(message, icon = null, title = null) {
        Swal.fire({
            title: title ?? "Peringatan !",
            text: message,
            icon: icon ?? "warning",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: false
        });
    };

    $(document).ready(function() {
        var modals = <?= json_encode($data) ?>;
        if (modals == null) {
            modals = [];
        }

        Swal.queue(modals);
    });
</script>
<?php JSRegister::end(); ?>