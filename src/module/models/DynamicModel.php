<?php
/**
 * Created by YiiMan TM.
 * Programmer: gholamreza beheshtian
 * Mobile:09353466620
 * 
 * Site:https://yiiman.ir
 * Date: ۰۲/۱۹/۲۰۲۰
 * Time: ۱۲:۰۷ بعدازظهر
 */

namespace YiiMan\LibUploadManager\module\models;


use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use YiiMan\LibUploadManager\lib\UploadManager;
use YiiMan\Setting\module\components\Options;

class DynamicModel extends \yii\base\DynamicModel
{

    public $formName = '';
    static $tableName = '';

    public function loadSettings($upload = true)
    {
        $options = new Options();
        $options->init();

        $dynamicObject = ArrayHelper::toArray($options->object);
        if (!empty($dynamicObject['DynamicModel'])) {
            foreach ($dynamicObject['DynamicModel'] as $attr => $item) {
                if (!empty($_FILES['DynamicModel']['name'][$attr])) {
                    if (!empty($_FILES['DynamicModel']['tmp_name'][$attr])) {
                        $this->defineAttribute($attr);
                        if ($upload) {
                            $fileName = (new UploadManager())->save($this, $attr);
                            $options->init();
                            $object = $options->object;
                            $object->DynamicModel->{$attr} = ['type' => 'file', 'file' => $fileName];
                            $options->set('DynamicModel', $object->DynamicModel);
                        }
                    }
                } else {
//                    if (
//                        (
//                            (isset($_FILES['DynamicModel']['name'][$attr])) &&
//                            !empty($_POST['DynamicModel']) && !empty($_POST['DynamicModel'][$attr])
//                        ) ||
//                        (
//                            (isset($_FILES['DynamicModel']['name'][$attr])) &&
//                            empty($_POST['DynamicModel']) && !empty($_POST['DynamicModel'][$attr])
//                        ) ||
//                        (
//                        isset($_POST['DynamicModel'][$attr])
//                        ) ||
//                        (
//                        empty($_POST)
//                        ) ||
//                        $upload==false
//                    ) {

                        $this->defineAttribute($attr);

                        if (empty($item['type'])) {
                            $this->{$attr} = $item;
                        }
//                    }


                }
            }
        }

        if (!empty($_POST['DynamicModel'])) {
            foreach ($_POST['DynamicModel'] as $attr => $item) {
                if (!empty($dynamicObject['DynamicModel'][$attr]['type']) && empty($_FILES['DynamicModel']['name'][$attr])) {
                    $_POST['DynamicModel'][$attr] = $dynamicObject['DynamicModel'][$attr];
                }
            }
        }
    }

    public function formName()
    {
        if (empty($this->formName)) {
            return parent::formName(); // TODO: Change the autogenerated stub
        } else {
            return $this->formName;
        }

    }


    /**
     * Declares the name of the database table associated with this AR class.
     * By default this method returns the class name as the table name by calling [[Inflector::camel2id()]]
     * with prefix [[Connection::tablePrefix]]. For example if [[Connection::tablePrefix]] is `tbl_`,
     * `Customer` becomes `tbl_customer`, and `OrderItem` becomes `tbl_order_item`. You may override this method
     * if the table is not named after this convention.
     * @return string the table name
     */
    public static function tableName()
    {
        return self::$tableName;
    }

//		public function __set( $name , $value ) {
//			if (!$this->hasAttribute( $name)){
//				$this->defineAttribute( $name);
//			}
//		}

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        if ($this->hasAttribute($name)) {
            return parent::__get($name);
        }

        $this->defineAttribute($name);

        return '';
    }
}
