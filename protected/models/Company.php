<?php

/**
* Company Model Class
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
 * This is the model class for table "company".
 *
 * The followings are the available columns in table 'company':
 * @property integer $company_id
 * @property string $company_name
 * @property string $password
 * @property string $hash
 * @property string $subs
 * @property string $keywords
 * @property string $reelonline_keywords
 * @property string $reelonline_badwords
 * @property string $issues_client
 * @property string $reelonline_client
 * @property string $csr_client
 * @property string $rss_client
 * @property string $mail_schedule
 * @property string $backdate
 * @property string $priority
 * @property string $language
 * @property string $classification
 * @property string $transcription
 * @property string $kenyagazette
 * @property string $kenyagazette_kw
 */
class Company extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Company the static model class
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
		return 'company';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_name, password, hash, subs, keywords, reelonline_keywords, reelonline_badwords, issues_client, csr_client, kenyagazette_kw', 'required'),
			array('company_name', 'length', 'max'=>80),
			array('password', 'length', 'max'=>50),
			array('hash', 'length', 'max'=>11),
			array('subs', 'length', 'max'=>4),
			array('issues_client, reelonline_client, csr_client, rss_client, priority, classification, transcription, kenyagazette', 'length', 'max'=>1),
			array('language', 'length', 'max'=>2),
			array('mail_schedule, backdate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('company_id, company_name, password, hash, subs, keywords, reelonline_keywords, reelonline_badwords, issues_client, reelonline_client, csr_client, rss_client, mail_schedule, backdate, priority, language, classification, transcription, kenyagazette, kenyagazette_kw', 'safe', 'on'=>'search'),
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
			'company_id' => 'Company',
			'company_name' => 'Company Name',
			'password' => 'Password',
			'hash' => 'Hash',
			'subs' => 'Subs',
			'keywords' => 'Keywords',
			'reelonline_keywords' => 'Reelonline Keywords',
			'reelonline_badwords' => 'Reelonline Badwords',
			'issues_client' => 'Issues Client',
			'reelonline_client' => 'Reelonline Client',
			'csr_client' => 'Csr Client',
			'rss_client' => 'Rss Client',
			'mail_schedule' => 'Mail Schedule',
			'backdate' => 'Backdate',
			'priority' => 'Priority',
			'language' => 'Language',
			'classification' => 'Classification',
			'transcription' => 'Transcription',
			'kenyagazette' => 'Kenyagazette',
			'kenyagazette_kw' => 'Kenyagazette Kw',
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

		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('hash',$this->hash,true);
		$criteria->compare('subs',$this->subs,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('reelonline_keywords',$this->reelonline_keywords,true);
		$criteria->compare('reelonline_badwords',$this->reelonline_badwords,true);
		$criteria->compare('issues_client',$this->issues_client,true);
		$criteria->compare('reelonline_client',$this->reelonline_client,true);
		$criteria->compare('csr_client',$this->csr_client,true);
		$criteria->compare('rss_client',$this->rss_client,true);
		$criteria->compare('mail_schedule',$this->mail_schedule,true);
		$criteria->compare('backdate',$this->backdate,true);
		$criteria->compare('priority',$this->priority,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('classification',$this->classification,true);
		$criteria->compare('transcription',$this->transcription,true);
		$criteria->compare('kenyagazette',$this->kenyagazette,true);
		$criteria->compare('kenyagazette_kw',$this->kenyagazette_kw,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}