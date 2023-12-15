<?php

use yii\helpers\Url;
?>
<div class="row">
    <!-- Column -->
    <div class="col-md-6 col-lg-6 col-xl-4">
        <div class="card m-b-30">
            <div class="card-body">
                <a href="<?= Url::to(['proyek/index']); ?>">
                    <div class="d-flex flex-row">
                        <table class="table table-borderless">
                            <tr>
                                <td style="padding: 0px 0px;">
                                    <div class="round"><i class="fa fa-building"></i></div>
                                </td>
                                <td style="padding: 0px 0px;">
                                    <div class="m-l-10 text-center">
                                        <h5 class="mt-0 round-inner"><?= $jumlah ?></h5>
                                        <p class="mb-0 text-muted">Proyek</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </a>
            </div>
        </div>
    </div><!-- Column -->
</div>