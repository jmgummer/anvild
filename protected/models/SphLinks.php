<?php

/**
* SphLinks Model Class
*
* @package     Anvil
* @subpackage  Models
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

/**
 * This is the model class for table "sph_links".
 *
 * The followings are the available columns in table 'sph_links':
 * @property integer $link_id
 * @property integer $media_house_id
 * @property string $url
 * @property string $title
 * @property string $description
 * @property string $fulltxt
 * @property string $indexdate
 * @property double $size
 * @property string $md5sum
 * @property integer $visible
 * @property integer $level
 */
class SphLinks extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SphLinks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return CDbConnection database connection
	 */
	public function getDbConnection()
	{
		return Yii::app()->db2;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sph_links';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('url', 'required'),
			array('media_house_id, visible, level', 'numerical', 'integerOnly'=>true),
			array('size', 'numerical'),
			array('url, description', 'length', 'max'=>255),
			array('title', 'length', 'max'=>200),
			array('md5sum', 'length', 'max'=>32),
			array('link_id,fulltxt, indexdate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('link_id, media_house_id, url, title, description, fulltxt, indexdate, size, md5sum, visible, level', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'link_id' => 'Link',
			'media_house_id' => 'Media House',
			'url' => 'Url',
			'title' => 'Title',
			'description' => 'Description',
			'fulltxt' => 'Fulltxt',
			'indexdate' => 'Indexdate',
			'size' => 'Size',
			'md5sum' => 'Md5sum',
			'visible' => 'Visible',
			'level' => 'Level',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('link_id',$this->link_id);
		$criteria->compare('media_house_id',$this->media_house_id);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('fulltxt',$this->fulltxt,true);
		$criteria->compare('indexdate',$this->indexdate,true);
		$criteria->compare('size',$this->size);
		$criteria->compare('md5sum',$this->md5sum,true);
		$criteria->compare('visible',$this->visible);
		$criteria->compare('level',$this->level);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getPublication()
	{
		if(!empty($this->media_house_id)){
			return Mediahouse::model()->find('Media_House_ID=:a', array(':a'=>$this->media_house_id))->Media_House_List;
		}else{
			return 'Unknown';
		}
	}

	public function getTruncatedFText()
	{
		$limit = 200;
	   	$content = $this->fulltxt;
	   	if (strlen($content) > $limit){
			$content = substr($content, 0, strrpos(substr($content, 0, $limit), ' '));
		}
	}

	public function getPage()
	{
		if($page = Story::model()->find('link_id=:a', array(':a'=>$this->link_id))){
			return ': Page '.$page ->StoryPage;
		}else{
			return '';
		}
	}
}