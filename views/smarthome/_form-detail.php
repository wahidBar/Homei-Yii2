<?php

use app\components\annex\Tabs;

?>
<div class="row">
    <div class="col-md-5">
        <div class="card m-b-30">
            <div class="card-body">

                <?php $this->beginBlock('tab-formkontrol'); ?>
                <?= $this->render('_tab-formkontrol', ['modelKontrol' => $modelKontrol, 'model' => $model, 'dropdownPin' => $dropdownPin]); ?>
                <?php $this->endBlock() ?>

                <?php $this->beginBlock('tab-formsirkuit'); ?>
                <?= $this->render('_tab-formsirkuit', ['modelSirkuit' => $modelSirkuit]); ?>
                <?php $this->endBlock() ?>

                <?= Tabs::widget(
                    [
                        'id' => 'relation-tabs-form',
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'label'   => '<b class=""># ' . ($modelSirkuit->isNewRecord ? 'Tambah' : 'Ubah') . ' Sirkuit</b>',
                                'content' => $this->blocks['tab-formsirkuit'],
                                'active'  => ($modelKontrol->isNewRecord) ? !$modelSirkuit->isNewRecord : false,
                            ],
                            [
                                'label'   => '<b class=""># ' . ($modelKontrol->isNewRecord ? 'Tambah' : 'Ubah') . ' Kontrol</b>',
                                'content' => $this->blocks['tab-formkontrol'],
                                'active'  => ($modelSirkuit->isNewRecord) ? !$modelKontrol->isNewRecord : false,
                            ],
                        ]
                    ]
                );
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card m-b-30">
            <div class="card-body">
                <?php $this->beginBlock('tab-kontrol'); ?>
                <?= $this->render('_tab-kontrol', ['model' => $model]); ?>
                <?php $this->endBlock() ?>

                <?php $this->beginBlock('tab-sirkuit'); ?>
                <?= $this->render('_tab-sirkuit', ['model' => $model]); ?>
                <?php $this->endBlock() ?>

                <?= Tabs::widget(
                    [
                        'id' => 'relation-tabs-list',
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'label'   => '<b class=""># Sirkuit</b>',
                                'content' => $this->blocks['tab-sirkuit'],
                                'active'  => false,
                            ],
                            [
                                'label'   => '<b class=""># Kontrol</b>',
                                'content' => $this->blocks['tab-kontrol'],
                                'active'  => true,
                            ],
                        ]
                    ]
                );
                ?>
            </div>
        </div>
    </div>
</div>