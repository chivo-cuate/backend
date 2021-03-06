./yii gii/model  --tableName=app_config --modelClass=AppConfig --ns=app\models --db=db \
  && ./yii gii/model  --tableName=auth_module --modelClass=AuthModule --ns=app\models --db=db \
  && ./yii gii/model  --tableName=auth_permission --modelClass=AuthPermission --ns=app\models --db=db \
  && ./yii gii/model  --tableName=auth_permission_role --modelClass=AuthPermissionRole --ns=app\models --db=db \
  && ./yii gii/model  --tableName=auth_role --modelClass=AuthRole --ns=app\models --db=db \
  && ./yii gii/model  --tableName=auth_user --modelClass=AuthUser --ns=app\models --db=db \
  && ./yii gii/model  --tableName=auth_user_role --modelClass=AuthUserRole --ns=app\models --db=db \
  && ./yii gii/model  --tableName=branch --modelClass=Branch --ns=app\models --db=db \
  && ./yii gii/model  --tableName=branch_user --modelClass=BranchUser --ns=app\models --db=db \
  && ./yii gii/model  --tableName=menu --modelClass=Menu --ns=app\models --db=db \
  && ./yii gii/model  --tableName=menu_asset --modelClass=MenuAsset --ns=app\models --db=db \
  && ./yii gii/model  --tableName=menu_cook --modelClass=MenuCook --ns=app\models --db=db \
  && ./yii gii/model  --tableName=order_status --modelClass=OrderStatus --ns=app\models --db=db \
  && ./yii gii/model  --tableName=order --modelClass=Order --ns=app\models --db=db \
  && ./yii gii/model  --tableName=order_asset --modelClass=OrderAsset --ns=app\models --db=db \
  && ./yii gii/model  --tableName=asset --modelClass=Asset --ns=app\models --db=db \
  && ./yii gii/model  --tableName=asset_type --modelClass=AssetType --ns=app\models --db=db \
  && ./yii gii/model  --tableName=asset_category --modelClass=AssetCategory --ns=app\models --db=db \
  && ./yii gii/model  --tableName=asset_component --modelClass=AssetComponent --ns=app\models --db=db \
  && ./yii gii/model  --tableName=stock --modelClass=Stock --ns=app\models --db=db \
  && ./yii gii/model  --tableName=measure_unit --modelClass=MeasureUnit --ns=app\models --db=db \
  && ./yii gii/model  --tableName=notification --modelClass=Notification --ns=app\models --db=db \
  && ./yii gii/model  --tableName=product_ingredient --modelClass=ProductIngredient --ns=app\models --db=db

