<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cryptoTexts".
 *
 * @property integer $textId
 * @property string $cryptoText
 */
class CryptoTexts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cryptoTexts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['textId'], 'required'],
            [['textId'], 'integer'],
            [['cryptoText'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'textId' => 'Text ID',
            'cryptoText' => 'Crypto Text',
        ];
    }
}
