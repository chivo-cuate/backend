<?php

namespace app\controllers;

use app\models\AuthModule;
use app\models\AuthPermission;
use app\models\AuthPermissionRole;
use app\models\AuthRole;
use app\utilities\Utilities;
use Exception;

class RolesController extends MyRestController {

    public $modelClass = AuthRole::class;

    public function actionListar() {
        try {
            return ['code' => 'success', 'msg' => 'Elementos cargados.', 'data' => $this->_getRoles()];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    private function _getSubModulesByModule($roleId, $moduleId) {
        $res = [];
        $subModules = AuthModule::find()->where(['parent_id' => $moduleId])->all();
        foreach ($subModules as $subModule) {
            $permissions = $this->_getPermissionsBySubModule($roleId, $subModule->id);
            $res[] = [
                'id' => $subModule->id,
                'name' => $subModule->name,
                'perms' => $permissions[0],
                'active' => $permissions[1],
                'status' => count($permissions[0]) === count($permissions[1]),
            ];
        }
        return $res;
    }

    private function _getPermissionsBySubModule($roleId, $subModuleId) {
        $res = [];
        $activePerms = [];
        $permissions = AuthPermission::find()->where(['module_id' => $subModuleId])->all();
        foreach ($permissions as $permission) {
            $permStatus = AuthPermissionRole::findOne(['perm_id' => $permission->id, 'role_id' => $roleId]) ? true : false;
            $res[] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'status' => $permStatus,
            ];
            if ($permStatus) {
                $activePerms[] = $permission->id;
            }
        }
        return [$res, $activePerms];
    }

    private function _getRoles() {
        $res = [];
        $roles = AuthRole::find()->all();
        $modules = AuthModule::find()->where('parent_id is null')->orderBy(['name' => SORT_ASC])->select(['id', 'name', 'slug'])->all();

        foreach ($roles as $role) {
            $rolePerms = [];
            foreach ($modules as $module) {
                $rolePerms[] = [
                    'name' => $module->name,
                    'subModules' => $this->_getSubModulesByModule($role->id, $module->id)
                ];
            }
            $res[] = [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'modules' => $rolePerms
            ];
        }
        return $res;
    }

    public function actionEliminar() {
        try {
            $item = AuthRole::findOne($this->requestParams['id']);
            if (!$item)
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            $item->delete();
            return ['code' => 'success', 'msg' => 'Elemento eliminado.', 'data' => $this->_getRoles()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionCrear() {
        try {
            $params = $this->requestParams['item'];
            $item = new AuthRole(['name' => $params['name'], 'description' => $params['description']]);
            if ($item->validate()) {
                $item->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getRoles()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($item), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditar() {
        try {
            $params = $this->requestParams['item'];
            $item = AuthRole::findOne($params['id']);
            if (!$item) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $item->setAttributes(['name' => $params['name'], 'description' => $params['description']]);
            if ($item->validate()) {
                $item->save();
                return ['code' => 'success', 'msg' => 'Elemento ' . ($item->isNewRecord ? ' adicionado.' : 'actualizado.'), 'data' => $this->_getRoles()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($item), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditarPermisos() {
        try {
            $role = AuthRole::findOne($this->requestParams['id']);
            if (!$role) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            AuthPermissionRole::deleteAll(['role_id' => $role->id]);
            $permsBySubModule = $this->requestParams['items'];
            foreach ($permsBySubModule as $permBySubModule) {
                foreach ($permBySubModule as $permId) {
                    $perm = AuthPermission::findOne($permId);
                    if ($perm) {
                        $newPermRole = new AuthPermissionRole(['perm_id' => $perm->id, 'role_id' => $role->id]);
                        $newPermRole->save();
                    }
                }
            }
            return ['code' => 'success', 'msg' => 'Permisos actualizados.', 'data' => $this->_getRoles()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
