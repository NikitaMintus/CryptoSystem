<?php

namespace app\controllers;


use app\models\CryptoForm;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

class AffineController extends Controller
{
    public function actionIndex()
    {
        $model = new CryptoForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->txtFile = UploadedFile::getInstance($model, 'txtFile');
            if ($model->upload()) {
                // file is uploaded successfully
                $model->initialText = $model->readFileToStr();
            }
            $model->encryptDecrypt();
            return $this->render('index', ['model' => $model]);
        }



        return $this->render('index', ['model' => $model]);
    }



}
