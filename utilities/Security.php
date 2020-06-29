<?php

namespace app\utilities;

use app\models\User;
use yii\db\Exception;

class Security {

    public static function getUserPermissions(User $user) {
        $userRoles = $user->getAuthUserRoles()->all();
        $res = [];

        foreach ($userRoles as $userRole) {
            $perms = $userRole->getRole()->one()->getPerms()->all();

            foreach ($perms as $perm) {
                $subModule = $perm->getModule()->one();
                $module = $subModule->getParent()->one();

                $moduleIndex = self::getItemIndexOnArray($res, 'name', $module->name);
                if ($moduleIndex === -1) {
                    $res[] = [
                        'name' => $module->name,
                        'icon' => $module->icon,
                        'slug' => "/$module->slug",
                        'subModules' => [],
                    ];
                    $moduleIndex = count($res) - 1;
                }

                $subModuleIndex = self::getItemIndexOnArray($res[$moduleIndex]['subModules'], 'name', $subModule->name);
                if ($subModuleIndex === -1) {
                    $res[$moduleIndex]['subModules'][] = [
                        'name' => $subModule->name,
                        'icon' => $subModule->icon,
                        'slug' => "/$module->slug/$subModule->slug",
                        'perms' => [],
                    ];
                    $subModuleIndex = count($res[$moduleIndex]['subModules']) - 1;
                }

                $permIndex = self::getItemIndexOnArray($res[$moduleIndex]['subModules'][$subModuleIndex]['perms'], 'text', $perm->name);
                if ($permIndex === -1) {
                    $res[$moduleIndex]['subModules'][$subModuleIndex]['perms'][] = [
                        'text' => $perm->name,
                        'route' => "/$module->slug/$subModule->slug/$perm->slug",
                    ];
                }
            }
        }
        return $res;
    }

    public static function verifyUserPermission(User $user, $action) {
        $userRoles = $user->getAuthUserRoles()->all();
        $found = false;
        foreach ($userRoles as $userRole) {
            $perms = $userRole->getRole()->one()->getPerms()->all();
            foreach ($perms as $perm) {
                $subModule = $perm->getModule()->one();
                if ($action === "$subModule->slug/$perm->slug") {
                    $found = true;
                    break;
                }
            }
        }
        if ($found) {
            return true;
        }
        throw new Exception("Acceso denegado.");
    }

    public static function getItemIndexOnArray($array, $attr, $value) {
        $i = 0;
        foreach ($array as $item) {
            if ($item[$attr] === $value) {
                return $i;
            }
            $i++;
        }
        return -1;
    }

    /*public static function getCurrentMenuCounterparts(User $user, $menu) {
        $branches = $user->getBranches()->all();
        $command = \Yii::$app->db->createCommand("select distinct auth_role.name, user_id, concat(first_name, ' ', last_name) full_name from auth_user_role INNER join auth_user on (auth_user_role.user_id = auth_user.id) inner join auth_role on (auth_user_role.role_id = auth_role.id) where user_id <> :userId and role_id in (select role_id from auth_user_role where user_id = :userId) and user_id in (select cook_id from menu_cook where menu_id = :menuId)", [':userId' => $user->id, ':menuId' => $menu ? $menu->id : -1]);
        return $branches;
    }*/

}
