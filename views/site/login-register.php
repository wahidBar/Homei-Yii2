<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
?>
<div id="back">
    <canvas class="canvas-back"></canvas>
    <div class="backRight">
        <img src="<?= \Yii::$app->request->baseUrl . "/home/img/login-img.jpg" ?>" alt="login image" class="login-img">
    </div>
    <div class="backLeft">
        <img src="<?= \Yii::$app->request->baseUrl . "/home/img/login-img.jpg" ?>" alt="login image" class="login-img">
    </div>
</div>

<div id="slideBox">
    <div class="topLayer">
        <div class="left">
            <div class="content">
                <h2>Sign Up</h2>
                <?php $form = ActiveForm::begin(['id' => 'register-form', 'enableClientValidation' => false]); ?>
                <div class="form-element form-stack">
                <?= $form
                    ->field($modelRegister, 'name', [
                        'template' => '
                            {label}
                            {input}
                            {error}
                        ',
                        'inputOptions' => [
                            'class' => ''
                        ],
                        'labelOptions' => [
                            'class' => 'control-label'
                        ],
                        'options' => ['tag' => false]
                    ])
                    ->label(true)
                    ->textInput(['placeholder' => $modelRegister->getAttributeLabel('name')]) ?>
                </div>
                <div class="form-element form-stack">
                <?= $form
                    ->field($modelRegister, 'username', [
                        'template' => '
                            {label}
                            {input}
                            {error}
                        ',
                        'inputOptions' => [
                            'class' => ''
                        ],
                        'labelOptions' => [
                            'class' => 'control-label'
                        ],
                        'options' => ['tag' => false]
                    ])
                    ->label(true)
                    ->textInput(['placeholder' => $modelRegister->getAttributeLabel('username')]) ?>
                </div>
                <div class="form-element form-stack">
                <?= $form
                    ->field($modelRegister, 'password', [
                        'template' => '
                            {label}
                            {input}
                            {error}
                        ',
                        'inputOptions' => [
                            'class' => ''
                        ],
                        'labelOptions' => [
                            'class' => 'control-label'
                        ],
                        'options' => ['tag' => false]
                    ])
                    ->label(true)
                    ->passwordInput(['placeholder' => $modelRegister->getAttributeLabel('password')]) ?>
                </div>
                <?= $form->field($modelRegister, 'captcha')->widget(
                    \himiklab\yii2\recaptcha\ReCaptcha3::class,
                    [
                        'action' => 'login',
                    ]
                )->label(false) ?>
                
                <div class="form-element form-submit">
                    <?= Html::submitButton('Daftar', ['class' => 'signup', 'name' => 'signup', 'id' => 'register']) ?>
                    <?= Html::button('Login', ['class' => 'signup off', 'name' => 'login-button', 'id' => 'goLeft']) ?>
                </div>

                <?php ActiveForm::end(); ?>
                <!-- <form id="form-signup" method="post" onsubmit="return false;">
                    <div class="form-element form-stack">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email">
                    </div>
                    <div class="form-element form-stack">
                        <label for="username-signup" class="form-label">Username</label>
                        <input id="username-signup" type="text" name="username">
                    </div>
                    <div class="form-element form-stack">
                        <label for="password-signup" class="form-label">Password</label>
                        <input id="password-signup" type="password" name="password">
                    </div>
                    <div class="form-element form-checkbox">
                        <input id="confirm-terms" type="checkbox" name="confirm" value="yes" class="checkbox">
                        <label for="confirm-terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
                    </div>
                    <div class="form-element form-submit">
                        <button id="signUp" class="signup" type="submit" name="signup">Sign up</button>
                        <button id="goLeft" class="signup off">Log In</button>
                    </div>
                </form> -->
            </div>
        </div>
        <div class="right">
            <!-- <div class="content">
    <h2>Login</h2>
    <form id="form-login" method="post" onsubmit="return false;">
      <div class="form-element form-stack">
        <label for="username-login" class="form-label">Username</label>
        <input id="username-login" type="text" name="username">
      </div>
      <div class="form-element form-stack">
        <label for="password-login" class="form-label">Password</label>
        <input id="password-login" type="password" name="password">
      </div>
      <div class="form-element form-submit">
        <button id="logIn" class="login" type="submit" name="login">Log In</button>
        <button id="goRight" class="login off" name="signup">Sign Up</button>
      </div>
    </form>
  </div> -->

            <!-- /.login-logo -->
            <div class="content">
                <h2>Login</h2>
                <p class="login-box-msg">Silakan masukkan username & password</p>

                <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

                <div class="form-element form-stack">
                    <?= $form
                        ->field($model, 'username', [
                            'template' => '
                                {label}
                                {input}
                                {error}
                            ',
                            'inputOptions' => [
                                'class' => ''
                            ],
                            'labelOptions' => [
                                'class' => 'control-label'
                            ],
                            'options' => ['tag' => false]
                        ])
                        ->label(true)
                        ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>
                </div>
                <div class="form-element form-stack">
                    <?= $form
                        ->field($model, 'password', [
                            'template' => '
                                {label}
                                {input}
                                {error}
                            ',
                            'inputOptions' => [
                                'class' => ''
                            ],
                            'labelOptions' => [
                                'class' => 'control-label'
                            ],
                            'options' => ['tag' => false]
                        ])
                        ->label(true)
                        ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
                </div>

                <?= $form->field($model, 'captcha')->widget(
                    \himiklab\yii2\recaptcha\ReCaptcha3::class,
                    [
                        'action' => 'login',
                    ]
                )->label(false) ?>

                <div class="form-element form-submit">
                    <?= Html::submitButton('<i class="fa fa-lock"></i> Masuk', ['class' => 'login', 'name' => 'login-button', 'id' => 'logIn']) ?>
                    <?= Html::button('<i class="fa fa-user"></i> Daftar', ['class' => 'login off', 'name' => 'register-button', 'id' => 'goRight']) ?>
                </div>



                <?php ActiveForm::end(); ?>

            </div>
            <!-- /.login-box-body -->
        </div>
    </div>
</div>