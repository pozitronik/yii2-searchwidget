<?php
declare(strict_types = 1);

namespace app\models;

use Faker\Factory;
use Yii;
use yii\base\Event;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "sys_users".
 *
 * @property int $id
 * @property string $login User login
 * @property string $username User's name
 * @property string $password Password or password hash
 * @property null|string $salt Password salt
 * @property null|string $email User's email
 * @property null|string $comment Any comment about the user
 * @property-read string $authKey @see [[yii\web\IdentityInterface::getAuthKey()]]
 */
class Users extends ActiveRecord implements IdentityInterface {

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return match (static::getDb()->driverName) {
			'pgsql' => [
				'id' => [
					'class' => AttributeBehavior::class,
					'attributes' => [
						ActiveRecord::EVENT_BEFORE_INSERT => 'id'
					],
					'value' => static function(Event $event) {
						$connection = Yii::$app->get('db');
						$result = $connection?->createCommand("SELECT nextval('users_id_seq');")->queryOne();
						return false === $result?:$result['nextval'];
					}
				]],
			default => []
		};
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'users';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['username', 'login', 'password', 'salt', 'comment'], 'string'],
			[['email'], 'email'],
			[['username', 'login', 'password'], 'required']
		];
	}

	/**
	 * @inheritDoc
	 */
	public static function findIdentity($id) {
		return static::findOne($id);
	}

	/**
	 * @inheritDoc
	 */
	public static function findIdentityByAccessToken($token, $type = null):?IdentityInterface {
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @inheritDoc
	 */
	public function getAuthKey():string {
		return md5($this->id.md5($this->login));
	}

	/**
	 * @inheritDoc
	 */
	public function validateAuthKey($authKey):bool {
		return $this->authKey === $authKey;
	}

	/**
	 * @return static
	 */
	public static function CreateUser(?int $id = null):self {
		return new self([
			'id' => $id,
			'login' => 'test',
			'username' => 'test_user',
			'password' => 'test',
		]);
	}

	/**
	 * @return static
	 * @throws Exception
	 */
	public function saveAndReturn():static {
		if (!$this->save()) {
			throw new Exception(sprintf("Не получилось сохранить запись: %s", implode(',', $this->firstErrors)));
		}
		$this->refresh();
		return $this;
	}

	/**
	 * @param int $count
	 * @return void
	 */
	public static function GenerateUsers(int $count = 100):void {
		$factory = Factory::create();
		for ($c = 0; $c < $count; $c++) {
			(new Users([
				'login' => $factory->userName,
				'username' => $factory->name,
				'password' => $factory->password,
				'salt' => null,//doesn't matter
				'email' => $factory->email,
				'comment' => $factory->realText(255)
			]))->save();
		}
	}

}
