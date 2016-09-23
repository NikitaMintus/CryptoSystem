<?php
use yii\widgets\ActiveForm;
?>


<div>
    <h1>Афинный метод</h1>
</div>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<!---->
<?= $form->field($model, 'txtFile')->fileInput() ?>
<!---->
<button>Submit</button>
<!---->
<?php ActiveForm::end() ?>
