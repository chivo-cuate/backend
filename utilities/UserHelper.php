<?php

namespace app\utilities;

use app\models\AuthUserRole;
use app\models\Menu;
use app\models\User;
use DusanKasan\Knapsack\Collection;
use function Lambdish\Phunctional\map;

class UserHelper
{

    //private static $roleId = 0;
    private static $userId = 0;

    /*public static function getCooksWithSameRole(User $user) {
        self::$roleId = AuthUserRole::find()
            ->where(['user_id' => $user->id])
            ->andWhere('role_id in (4, 6)')
            ->select('role_id')
            ->orderBy(['role_id' => SORT_DESC])
            ->one();

        if (!self::$roleId) {
            return [];
        }

        self::$userId = $user->id;

        $branches = $user->getBranches()->select('id, name')->asArray()->all();
        $res = [];
        foreach ($branches as $branch) {
            $currMenu = MenuHelper::getCurrentMenu($branch['id']);
            $menuCooks = $currMenu ? $currMenu->getCooks()->all() : [];
            $filteredByRoleCooks = Collection::from($menuCooks)->filter(function($menuCook) {
                return $menuCook->id !== self::$userId && User::hasRole($menuCook->id, self::$roleId);
            });
            $res[$branch['id']] = [
                'users' => $filteredByRoleCooks
            ];
        }

        return $res;
    }*/

    public static function getCooksPerBranches(User $user)
    {
        $branches = $user->getBranches()->select('id')->all();
        $res = [];

        foreach ($branches as $branch) {
            $res[$branch->id] = MenuHelper::getCurrentMenuCooks($branch->id);
        }

        return $res;
    }

    public static function getUserRolesNamesArray(User $user)
    {
        return Collection::from($user->getRoles()
            ->select('name')
            ->asArray()
            ->all())
            ->map(function ($role) {
                return $role['name'];
            })
            ->toArray();
    }

}
