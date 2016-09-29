<?php

use dosamigos\fileupload\FileUploadUI;
use kartik\file\FileInput;
use yii\bootstrap\Button;
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

?>

    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'crypto-form', 'method' => 'post', 'options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="col-md-7">
            <label for="showPlainText">Initial text</label>
            <?= $form->field($model, 'initialText',['template'=> "{input}"])->textArea(['cols' => '50', 'rows' => '13']) ?>
        </div>
        <div class="col-md-5">
<!--            --><?//= $form->field($model, 'txtFile')->widget(FileInput::classname(), [
//                'options' => ['txtFile' => '*.txt'],
//            ]);?>

<!--            --><?//= $form->field($model, 'txtFile')->fileInput() ?>
            <?= $form->field($model, 'txtFile')->widget(\dosamigos\fileinput\BootstrapFileInput::className(), [
                'options' => ['multiple' => true],
                'clientOptions' => [
                    'previewFileType' => 'text',
                    'browseClass' => 'btn btn-primary grid-button',
                    'uploadClass' => 'hidden',
                    'removeClass' => 'btn btn-danger',
                    'removeIcon' => '<i class="glyphicon glyphicon-trash"></i> '
                ]
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
                                '0' => 'Affine method',
                                '1' => 'Shifts method',
                            ]);
                ?>

                <?=$form->field($model, 'affineParams[a]')->label("Please, enter parameter A"); ?>

                <?=$form->field($model, 'affineParams[b]')->label("Please, enter parameter B"); ?>

                <?= Button::widget([
                    'label' => 'Encrypt',
                    'options' => ['class' => 'btn btn-primary grid-button', 'type' => 'submit', 'method' => 'post'],
                ]);
                ?>


                <?= Html::a('Download', ['/affine/download'], ['class'=>'btn btn-primary grid-button']) ?>

                <?= Button::widget([
                    'label' => 'Clear',
                    'options' => ['class' => 'btn btn-danger', 'type' => 'button'],
                    'id' => 'clear-button',
                ]);
                ?>

            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>

<?php
$script = <<< JS
    $('#clear-button').click(function(){
                $('#cryptoform-initialtext').val('');
                $('#cryptoform-resulttext').val('');
            });
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
?>















