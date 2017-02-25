<?php

namespace app\controllers;

use app\models\Images;
use Yii;
use yii\validators\UrlValidator;
use yii\web\Controller;
use yii\web\UploadedFile;
use linslin\yii2\curl;


class SiteController extends Controller
{

    public function actionFileload()
    {
        $model = new Images();
        return $this->render('file',['title'=>'Page load file','model'=>$model]);
    }

    public function actionAjaxload(){
        $val = new UrlValidator();
        $curl = new curl\Curl();

        //проверяем метод загрузки POST
        if (Yii::$app->request->post()) {
            $model_images = new Images();
            $model_images->load(Yii::$app->request->post());
            $model_images->file = UploadedFile::getInstance($model_images, 'file');
            //проверяем тип файла и наличие данного файла во временной директории
            if ($model_images->file->type == "text/plain" && isset($model_images->file->tempName)) {
                $handle = @fopen($model_images->file->tempName, "r");
                if ($handle) {
                    //получаем строку

                    while (($buffer = fgets($handle, 4096)) !== false && $val->validate($buffer)) {
                        if (!Images::findOne(['link'=>$buffer])) {
                            $response = $curl->get($buffer,1);
                            $img = new Images;
                            $img->link = $buffer;
                            $img->extensions = 'jpg';
                            if ($response) {
                                if($img->save()) {
                                    file_put_contents(Yii::getAlias('@app').'/'.Images::IMG_PATH.$img->md.'.jpg',$response);
                                }
                            }
                            unset($img);
                            unset($ch,$data);
                        }
                    }
                    fclose($handle);
                }
                //в случае успеха уведомляем об этом
                return $this->renderAjax('ajax/_formAjaxmessage',['text' => 'Успех! Данные были загружены', 'class' => 'text-success']);
            }
        }
        //в случае провала уведомляем об этом
        return $this->renderAjax('ajax/_formAjaxmessage', ['text' => 'Ошибка! Загрузка не была произведена','class'=>'text-danger']);
    }
}
