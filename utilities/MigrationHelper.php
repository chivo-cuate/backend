<?php


namespace app\utilities;


class MigrationHelper {

    public static function getTableOptions($driverName) {
        return ($driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;
    }

}
