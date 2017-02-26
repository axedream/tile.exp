<?php

use yii\db\Migration;
use yii\db\mysql\Schema;


class m170225_121336_img_class extends Migration
{


    public function up()
    {
        //хранящая изображения
        $this->createTable('images',[
            'id'=>Schema::TYPE_PK,                  //id записи
            'md'=>Schema::TYPE_STRING,              //уникальное имя файла (реальное имя файла)
            'extensions'=>Schema::TYPE_STRING,      //расширение имени файла
            'link'=>Schema::TYPE_STRING,            //ссылка на имя файла
            'width'=>Schema::TYPE_INTEGER,          //ширина
            'height'=>Schema::TYPE_INTEGER,         //высота
        ]);

    }

    public function down()
    {
        $this->dropTable('images');
    }
}
