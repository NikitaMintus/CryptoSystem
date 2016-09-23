<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
    * @var UploadedFile
    */
    public $txtFile;
    public $initialText;
    public $resultText;



    public function rules()
    {
        return [
            [['txtFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'txtFile' => 'Choose file',
            'currentMethod' => 'Choose encrypt method'
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->txtFile->saveAs('uploads/' . $this->txtFile->baseName . '.' . $this->txtFile->extension);
            return true;
        } else {
            return false;
        }
    }

    public function getName()
    {
        return \Yii::getAlias('@web').'/'.'uploads/' . $this->txtFile->baseName . '.' . $this->txtFile->extension;
    }

    public function readFile()
    {
        $fileName = 'uploads/' . $this->txtFile->baseName . '.' . $this->txtFile->extension;
        $fp = fopen($fileName, "r");

        $arr = [];

        while ( $line = fgets($fp, 1000) )
        {
            $nl = mb_strtoupper($line,'UTF-8');
            $arr[] = $nl;
        }

        print_r($arr);

    }

    public function readFileToStr()
    {
        $fileName = 'uploads/' . $this->txtFile->baseName . '.' . $this->txtFile->extension;
        $lines = explode("\n", file_get_contents($fileName));
        return $lines;

    }
}

