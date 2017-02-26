<?php

namespace app\controllers;

use app\models\Images;
use Yii;
use yii\validators\UrlValidator;
use yii\web\Controller;
use yii\web\UploadedFile;


class SiteController extends Controller
{

    public function actionFileload()
    {
        $model = new Images();
        return $this->render('file',['title'=>'Page load file','model'=>$model]);
    }

    public function actionAjaxload(){
        $val = new UrlValidator();
        $count = 0;
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
                    //while (($buffer = fgets($handle, 4096)) !== false && $val->validate($buffer)) {
                    //}

                    while (!feof($handle)) {
                        $buffer = trim(fgets($handle));
                        if (!Images::findOne(['link'=>$buffer])) {
                            $img = new Images;
                            $img->link = $buffer;
                            $img->extensions = 'jpg';

                            //проверяем доступность файла на сервере
                            $header = @get_headers($buffer);

                            if(in_array('200',explode(" ",$header[0]))) {
                                for ($i=0;$i<count($header);$i++){
                                    //--------------------------------атрибуты файла (тип, расширение)-------------------------//
                                    if (stripos($header[$i],"Content-Type:")!==false){
                                        $val = explode("/",explode(" ",$header[$i])[1]);
                                        $type = $val[0];
                                        $img->extensions = $val[1];
                                    }
                                    //-------------------------------end атрибуты---------------------------//
                                }//end for

                                //проверяем тип = изображение
                                if ($type=='image') {
                                    //получаем полный путь + имя файлв
                                    $fullpath = Yii::getAlias('@app').'/'.Images::IMG_PATH.$img->setName().'.'.$img->extensions;

                                    //запись информации о файлах и запись файлов
                                    if (copy($buffer,$fullpath) ){
                                        $img->save();
                                        $count++;
                                    }//end save
                                }//end image

                            }//end 200
                            //-------------------------------------- пересохранение = изменение масштаба + водяной знак -------------------------//
                            
                            //-------------------------------------- END пересохранение = изменение масштаба + водяной знак -------------------------//
                            unset($fullpath,$header,$img);
                        }

                    }
                    fclose($handle);
                }
                //в случае успеха уведомляем об этом
                return $this->renderAjax('ajax/_formAjaxmessage',['text' => 'Успех! Было загружено: '.$count. ' файл(а/ов)!', 'class' => 'text-success']);
            }
        }
        //в случае провала уведомляем об этом
        return $this->renderAjax('ajax/_formAjaxmessage', ['text' => 'Ошибка! Загрузка не была произведена','class'=>'text-danger']);
    }
}
