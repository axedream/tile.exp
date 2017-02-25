<?php
use kartik\file\FileInput;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = $title;
?>
<?= Html::input('hidden', null, Url::to(['/site/ajaxload']), ['id' => 'link_from_load_txt_file']);?>
<div class="site-index">
    <h1>
        <label class="control-label">Upload TXT File</label>
    </h1>
    <?= Html::csrfMetaTags() ?>
    <table class="load_form">
        <tr>
            <td class="load_input">
        <?php $form = ActiveForm::begin(
            [
                'options' => [
                    'id' => 'create-file-form',
                    'name' => 'create_file_form',
                    'enctype'=>'multipart/form-data',
                ]
            ]
        ); ?>
        <?= FileInput::widget(
            [
            'model' => $model,
            'attribute' => 'file',
            'options' => ['multiple' => true],
            'pluginOptions' => [
                'showUpload' => false,
                'browseLabel' => 'Текстовый файл',
                'showRemove' => false,
                'showPreview' => false,
                'mainClass' => 'input-group-ml',
                'browseIcon' => '<i class="glyphicon glyphicon-text-size"></i> ',
                'maxFileCount' => 1
                ]
            ]
        ) ?>
        <?php ActiveForm::end(); ?>
            </td>
            <td>
                <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary', 'id'=>'load_txt', 'form' => 'mod']); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="load_form_mess"></div>
            </td>
        </tr>
    </table>
</div>
