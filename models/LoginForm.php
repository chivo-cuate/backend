<?php

namespace app\models;

use DusanKasan\Knapsack\Collection;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $ip;
    public $rememberMe = true;
    private $_user;
    public $allowedBranches;

    public function attributeLabels()
    {
        return [
            'username' => 'Nombre de usuario',
            'password' => 'Contraseña',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['ip', 'validateNetwork'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Nombre de usuario o contraseña incorrecto.');
            }
            if ($user->status != 10) {
                $this->addError('username', 'Su cuenta ha sido deshabilitada.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function getAuthenticatedUser()
    {
        return $this->_user;
    }

    public function validateNetwork()
    {
        $user = $this->getUser();
        $this->allowedBranches = [];

        if (!$user) {
            $this->addError('username', 'Nombre de usuario o contraseña incorrecto.');
        }
        else {
            $userBranches = Collection::from($user->getBranches()->all())
                ->map(function ($branch) {
                    return [
                        'id' => $branch->id,
                        'name' => $branch->name,
                        'tables' => $branch->tables,
                        'sub_networks' => explode(",", $branch->network)
                    ];
                })
                ->toArray();

            foreach ($userBranches as $userBranch) {
                $branchData = $userBranch;
                unset($branchData['sub_networks']);

                foreach ($userBranch['sub_networks'] as  $subNetwork) {
                    $containedAt = strpos($this->ip, $subNetwork);
                    if ($subNetwork === "*" || $containedAt !== false || User::hasRole($user->id, 2)) {
                        $this->allowedBranches[] = $branchData;
                    }
                }
            }

            if (count($this->allowedBranches) === 0 && !User::hasRole($user->id, 1)) {
                $this->addError("ip", "No puede acceder desde esta ubicación.");
            }
        }
    }

}
