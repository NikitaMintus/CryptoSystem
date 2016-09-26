<?php

namespace app\controllers;


use app\models\CryptoMethods;
use Yii;
use yii\web\Controller;
use app\models\UploadForm;
use yii\web\UploadedFile;

class AffineController extends Controller
{
    public function actionIndex()
    {
        $model = new UploadForm();
        $modelCryptoMethods = new CryptoMethods();

        if (Yii::$app->request->isPost) {
            $model->txtFile = UploadedFile::getInstance($model, 'txtFile');
            if ($model->upload()) {
                // file is uploaded successfully
                $model->initialText = $model->readFileToStr();
                //$model->plainText = implode("",$textArr);
                return $this->render('index', ['model' => $model, 'modelCryptoMethods' => $modelCryptoMethods]);
            }


        }

        if($modelCryptoMethods->load(Yii::$app->request->post('CryptoMethods')) && $modelCryptoMethods->validate()) {
            $modelCryptoMethods->encryptDecrypt();
            return $this->render('index', ['model' => $model, 'modelCryptoMethods' => $modelCryptoMethods]);
        }

        return $this->render('index', ['model' => $model, 'modelCryptoMethods' => $modelCryptoMethods]);
    }

    public function actionUpload()
    {

    }

}
