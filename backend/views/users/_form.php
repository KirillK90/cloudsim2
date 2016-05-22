<?php
/* @var $this backend\components\View */
/* @var $model common\models\User */

use common\enums\UserRole;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>
<div class="row-fluid clearfix">
    <div class="col-md-5">
<? if (!$model->isNewRecord): ?>
<?= $form->field($model, 'id')->staticControl(); ?>
<?= $form->field($model, 'created_at')->staticControl(); ?>
<?= $form->field($model, 'updated_at')->staticControl(); ?>
<? endif ?>
<?= $form->field($model, 'email')->textInput(); ?>
<?= $form->field($model, 'username')->textInput(); ?>
<?= $form->field($model, 'role')->dropDownList(UserRole::getList()); ?>
<?= $form->field($model, 'newPassword', ['labelOptions' => ['label' => $model->isNewRecord ? 'Пароль' : 'Новый пароль']])->passwordInput(['style' => 'width: 142px']); ?>



    <div class="form-group">
        <div class="btn-group btn-group-sm">
            <br>
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn '.($model->isNewRecord ? 'btn-success' : 'btn-primary')]) ?>
            <?= Html::resetButton('Сбосить', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>
</div>

<?php ActiveForm::end(); ?>