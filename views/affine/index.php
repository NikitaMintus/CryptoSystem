<?php

use kartik\file\FileInput;
use yii\bootstrap\Button;
use yii\widgets\ActiveForm;

?>


<div>
    <h1>Афинный метод</h1>
</div>

    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'crypto-form', 'method' => 'post', 'options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="col-md-7">
            <label for="showPlainText">Initial text</label>
            <?= $form->field($model, 'initialText',['template'=> "{input}"])->textArea(['cols' => '50', 'rows' => '13']) ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'txtFile')->widget(FileInput::classname(), [
                'options' => ['txtFile' => '*.txt'],
            ]);?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <label for="showResultText">Result</label>
            <?= $form->field($model, 'resultText',['template'=> "{input}"])->textArea(['cols' => '50', 'rows' => '15']) ?>
        </div>
        <div class="col-md-5">
            <div class="row">
                <?=$form->field($model, 'currentAction')->dropDownList([
                    '0' => 'Encrypt text',
                    '1' => 'Decrypt text',
                ]);
                ?>

                <?=$form->field($model, 'currentMethod')->dropDownList([
                                '0' => 'Афиный метод',
                                '1' => 'Перестановки',
                                '2'=>'Гаусса'
                            ]);
                ?>

                <?=$form->field($model, "affineParams['a']")->textInput()->label("Please, enter parameter A"); ?>

                <?=$form->field($model, "affineParams['b']")->textInput()->label("Please, enter parameter B"); ?>

                <?= Button::widget([
                    'label' => 'Encrypt',
                    'options' => ['class' => 'btn btn-primary grid-button', 'type' => 'submit', 'method' => 'post'],
                ]);
                ?>

            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
















