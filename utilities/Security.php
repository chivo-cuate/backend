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
                $res[$moduleIndex]['subModules'][$subModuleIndex]['perms'][] = [
                    'text' => $perm->name,
                    'route' => "/$module->slug/$subModule->slug/$perm->slug",
                ];
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
                $module = $subModule->getParent()->one();
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

}
