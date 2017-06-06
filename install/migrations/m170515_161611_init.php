<?php
use yii\db\Migration;
use yii\db\Schema;
use linex\base\modules\user\models\User;
use linex\base\modules\dashboard\models\BackendMenu;
use linex\base\modules\pages\models\Page;

/**
 * Class m170515_161611_init
 */
class m170515_161611_init extends Migration
{
    public function init()
    {
        Yii::$app->language = 'ru-RU';
    }

    /**
     *
     */
    public function up()
    {
        mb_internal_encoding("UTF-8");
        $tableOptions = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
            : null;

        $this->createTable(
            '{{%session}}',
            [
                'id'     => $this->char(40)->notNull(),
                'expire' => $this->integer(11),
                'data'   => 'BLOB',
            ],
            $tableOptions
        );

        $this->addPrimaryKey('id', '{{%session}}', 'id');

        $this->createTable(
            '{{%auth_rule}}',
            [
                'name'       => $this->string(64)->notNull(),
                'data'       => $this->text(),
                'created_at' => $this->integer(11),
                'updated_at' => $this->integer(11),
            ],
            $tableOptions
        );
        $this->addPrimaryKey('name', '{{%auth_rule}}', 'name');

        $this->createTable(
            '{{%auth_item}}',
            [
                'name'        => $this->string(64),
                'type'        => $this->smallInteger(1),
                'description' => $this->text(),
                'rule_name'   => $this->string(64),
                'data'        => $this->text(),
                'created_at'  => $this->integer(11)->notNull(),
                'updated_at'  => $this->integer(11)->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('name', '{{%auth_item}}', 'name');
        $this->createIndex('idx_auth_item_rule_name', '{{%auth_item}}', 'rule_name');
        $this->createIndex('idx_auth_item_type', '{{%auth_item}}', 'type');
        $this->addForeignKey(
            'fk_auth_item_rule',
            '{{%auth_item}}',
            'rule_name',
            '{{%auth_rule}}',
            'name',
            null,
            'CASCADE'
        );

        $this->createTable(
            '{{%auth_item_child}}',
            [
                'parent' => $this->string(64)->notNull(),
                'child'  => $this->string(64)->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('pk_auth_item_child', '{{%auth_item_child}}', ['parent', 'child']);
        $this->createIndex('idx_auth_item_child', '{{%auth_item_child}}', 'child');
        $this->addForeignKey(
            'fk_auth_item_child_parent',
            '{{%auth_item_child}}',
            'parent',
            '{{%auth_item}}',
            'name',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_auth_item_child_child',
            '{{%auth_item_child}}',
            'child',
            '{{%auth_item}}',
            'name',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable(
            '{{%auth_assignment}}',
            [
                'item_name'  => $this->string(64)->notNull(),
                'user_id'    => $this->string(64)->notNull(),
                'created_at' => $this->integer(11),
                'updated_at' => $this->integer(11),
                'rule_name'  => $this->string(64),
                'data'       => $this->text(),
            ],
            $tableOptions
        );
        $this->addPrimaryKey('pk_auth_assignment', '{{%auth_assignment}}', ['item_name', 'user_id']);
        $this->createIndex('idx_auth_assignment_rule_name', '{{%auth_assignment}}', 'rule_name');
        $this->addForeignKey(
            'fk_auth_assignment_item_name',
            '{{%auth_assignment}}',
            'item_name',
            '{{%auth_item}}',
            'name',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_auth_assignment_rule_name',
            '{{%auth_assignment}}',
            'rule_name',
            '{{%auth_rule}}',
            'name',
            null,
            'CASCADE'
        );

        $this->createTable(
            User::tableName(),
            [
                'id'                    => $this->primaryKey(),
                'username'              => $this->string(255)->notNull()->unique(),
                'auth_key'              => $this->binary(32),
                'password_hash'         => $this->string(255)->notNull(),
                'password_reset_token'  => $this->binary(32),
                'email'                 => $this->string(255)->notNull(),
                'status'                => $this->smallInteger(2)->notNull()->defaultValue(10),
                'created_at'            => $this->integer()->notNull(),
                'updated_at'            => $this->integer()->notNull(),
                'first_name'            => $this->string(255)->notNull(),
                'last_name'             => $this->string(255)->notNull(),
                'username_is_temporary' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'added_by'              => $this->string(4)->defaultValue('user'),
            ],
            $tableOptions
        );

        $this->createTable(
            '{{%dynagrid}}',
            [
                'id'        => $this->string(100)->defaultValue(''),
                'filter_id' => $this->string(100),
                'sort_id'   => $this->string(100),
                'data'      => Schema::TYPE_TEXT,
            ]
        );
        $this->addPrimaryKey(
            'pk-id',
            '{{%dynagrid}}',
            'id'
        );
        $this->createTable(
            '{{%dynagrid_dtl}}',
            [
                'id'          => $this->string(128)->defaultValue(''),
                'category'    => $this->string(10),
                'name'        => $this->string(150),
                'data'        => Schema::TYPE_TEXT,
                'dynagrid_id' => $this->string(100),
                'UNIQUE `uniq_dtl` (`name`, `category`, `dynagrid_id`)',
            ]
        );
        $this->addPrimaryKey(
            'pk-id',
            '{{%dynagrid_dtl}}',
            'id'
        );

        // Page data
        $this->createTable(
            Page::tableName(),
            [
                'id'         => $this->primaryKey(),
                'parent_id'  => $this->integer()->defaultValue(0),
                'name'       => $this->string(255)->notNull(),
                'slug'       => $this->string(80)->notNull(),
                'slug_path'  => $this->string(255)->notNull()->defaultValue(''),
                'published'  => $this->smallInteger(1)->defaultValue(1),
                'content'    => $this->text(),
                'sort'       => $this->integer()->defaultValue(100),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
                'added_by'   => $this->string(4)->defaultValue('user'),
            ],
            $tableOptions
        );

        $this->insert(
            Page::tableName(),
            [
                'parent_id'  => 0,
                'name'       => Yii::t('app', 'Main page'),
                'slug'       => 'mainpage',
                'content'    => '<p>This is mainpage</p>',
                'sort'       => '1',
                'created_at' => time(),
                'updated_at' => time(),
                'added_by'   => 'core',
            ]
        );

        // Backend menu data
        $this->createTable(
            BackendMenu::tableName(),
            [
                'id'         => $this->primaryKey(),
                'parent_id'  => $this->integer()->defaultValue(0),
                'name'       => $this->string(255)->notNull(),
                'route'      => $this->string(255),
                'icon'       => $this->string(255),
                'sort'       => $this->integer()->defaultValue(100),
                'rbac_check' => $this->string(255),
                'added_by'   => $this->string(4)->defaultValue('user'),
            ],
            $tableOptions
        );

        $this->insert(
            BackendMenu::tableName(),
            [
                'parent_id'  => 0,
                'name'       => Yii::t('app', 'Users'),
                'route'      => '',
                'icon'       => 'user',
                'rbac_check' => 'user manage',
                'added_by'   => 'core',
            ]
        );
        $lastId = $this->db->lastInsertID;
        $this->batchInsert(
            BackendMenu::tableName(),
            ['parent_id', 'name', 'route', 'icon', 'rbac_check', 'added_by'],
            [
                [$lastId, Yii::t('app', 'Users list'), 'dashboard/users/default/index', 'users', 'user manage', 'core'],
            ]
        );
        $this->insert(
            BackendMenu::tableName(),
            [
                'parent_id'  => $lastId,
                'name'       => Yii::t('app', 'Rbac'),
                'route'      => '',
                'icon'       => 'lock',
                'rbac_check' => 'user manage',
                'added_by'   => 'core',
            ]
        );
        $lastId = $this->db->lastInsertID;
        $this->batchInsert(
            BackendMenu::tableName(),
            ['parent_id', 'name', 'route', 'icon', 'rbac_check', 'added_by'],
            [
                [$lastId, Yii::t('app', 'Roles'), 'dashboard/users/rbac/roles/index', 'angle-right', 'user manage', 'core'],
                [$lastId, Yii::t('app', 'Permissions'), 'dashboard/users/rbac/permissions/index', 'angle-right', 'user manage', 'core'],
            ]
        );

        $this->insert(
            BackendMenu::tableName(),
            [
                'parent_id'  => 0,
                'name'       => Yii::t('app', 'Settings'),
                'route'      => '',
                'icon'       => 'cogs',
                'rbac_check' => 'setting manage',
                'added_by'   => 'core',
            ]
        );
        $lastId = $this->db->lastInsertID;
        $this->batchInsert(
            BackendMenu::tableName(),
            ['parent_id', 'name', 'route', 'icon', 'rbac_check', 'added_by'],
            [
                [$lastId, Yii::t('app', 'Backend menu'), 'dashboard/backend-menu/index', 'bars', 'setting manage', 'core'],
            ]
        );

        $this->insert(
            BackendMenu::tableName(),
            [
                'parent_id'  => 0,
                'name'       => Yii::t('app', 'Content'),
                'route'      => '',
                'icon'       => 'contao',
                'rbac_check' => 'content manage',
                'added_by'   => 'core',
            ]
        );
        $lastId = $this->db->lastInsertID;
        $this->batchInsert(
            BackendMenu::tableName(),
            ['parent_id', 'name', 'route', 'icon', 'rbac_check', 'added_by'],
            [
                [$lastId, Yii::t('app', 'Pages'), 'dashboard/pages/default/index', 'file-text-o', 'content manage', 'core'],
                [$lastId, Yii::t('app', 'Reference Types'), 'dashboard/reference/default/index', 'window-maximize', 'content manage', 'core'],
            ]
        );
        //

        $this->batchInsert(
            '{{%auth_item}}',
            ['name', 'type', 'description', 'created_at', 'updated_at'],
            [
                ['admin', '1', Yii::t('app', 'Administrator'), time(), time()],
                ['manager', '1', Yii::t('app', 'Content Manager'), time(), time()],
                ['administrate', '2', Yii::t('app', 'Administrate panel'), time(), time()],
                ['user manage', '2', Yii::t('app', 'User management'), time(), time()],
                ['cache manage', '2', Yii::t('app', 'Cache management'), time(), time()],
                ['content manage', '2', Yii::t('app', 'Content management'), time(), time()],
                ['setting manage', '2', Yii::t('app', 'Setting management'), time(), time()],
            ]
        );

        $this->batchInsert(
            '{{%auth_item_child}}',
            ['parent', 'child'],
            [
                ['manager', 'administrate'],
                ['manager', 'content manage'],
                ['admin', 'administrate'],
                ['admin', 'user manage'],
                ['admin', 'cache manage'],
                ['admin', 'content manage'],
                ['admin', 'setting manage'],
            ]
        );
    }

    /**
     * @return bool
     */
    public function down()
    {
        echo "No way back. It's a serious CMS which has been made for big and serious projects.\n";

        return false;
    }

}
