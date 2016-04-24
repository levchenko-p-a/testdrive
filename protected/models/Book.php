<?php
Yii::import('application.extensions.image.Image');
/**
 * This is the model class for table "tbl_book".
 *
 * The followings are the available columns in table 'tbl_book':
 * @property integer $id_book
 * @property string $name
 * @property string $photo
 * @property integer $id_publish
 * @property string $date_publish
 */
class Book extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_book';
	}

    static public $image_base_path='/../images/';
    static public $image_base_url='/images/';

    public function getImagePath(){
		return Yii::app()->basePath.self::$image_base_path.$this->photo;
	}
    public function getImageUrl(){
        return Yii::app()->baseUrl.self::$image_base_url.$this->photo;
    }
    protected function afterDelete()
    {
        parent::afterDelete();
        $file_name=getImagePath();
        if(file_exists($file_name)){
            unlink($file_name);
        }
    }
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('photo', 'file','types'=>'jpg, gif, png', 'maxSize'=>1024 * 1024 * 5,
                'tooLarge'=>'The file weighs more than 5 MB. Please upload a smaller file.','allowEmpty'=>true,
                'on'=>'update'),
            array('photo', 'length', 'max'=>255, 'on'=>'insert,update'),
			array('name, id_publish, date_publish', 'required'),
			array('id_publish', 'safe'),
			array('id_publish', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('photo', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_book, name, photo, id_publish, date_publish', 'safe', 'on'=>'search'),
		);
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'publish'=>array(self::BELONGS_TO, 'Publish', 'id_publish'),
			'authors'=>array(self::MANY_MANY, 'Author',
				'tbl_book_author(id_book, id_author)'),
			'rubrics'=>array(self::MANY_MANY, 'Rubric',
				'tbl_book_rubric(id_book, id_rubric)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_book' => 'Id Book',
			'name' => 'Name',
			'photo' => 'Photo',
			'id_publish' => 'Id Publish',
			'date_publish' => 'Date Publish',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_book',$this->id_book);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('id_publish',$this->id_publish);
		$criteria->compare('date_publish',$this->date_publish,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Book the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
