<?php

namespace app\install\components;

use Yii;
use yii\base\Component;
use yii\helpers\VarDumper;
use Symfony\Component\Process\ProcessBuilder;
use app\install\models\User;
use app\install\models\Last;

/**
 * Class InstallHelper
 * @package app\install\components
 */
class InstallHelper extends Component
{
    public $migrationTimeout = 3600;
    public $migrationIdleTimeout = 60;

    /**
     * @param $config
     *
     * @return mixed
     */
    public static function createDatabaseConfig($config)
    {
        $config['dsn'] = 'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'];
        $config['class'] = 'yii\db\Connection';
        unset($config['db_name'], $config['db_host'], $config['connectionOk']);

        return $config;
    }

    /**
     * @param bool $down
     *
     * @return \Symfony\Component\Process\Process
     */
    public function applyMigrations($down = false)
    {
        $builder = $this->migrationCommandBuilder('@app/install/migrations', '{{%migration}}', $down);

        $process = $builder->getProcess();
        $process
            ->setTimeout($this->migrationTimeout)
            ->setIdleTimeout($this->migrationIdleTimeout);

        return $process;
    }

    /**
     * @param string $migrationPath
     * @param string $migrationTable
     * @param bool   $down
     *
     * @return ProcessBuilder
     */
    private function migrationCommandBuilder($migrationPath = '', $migrationTable = '{{%migration}}', $down = false)
    {
        $builder = new ProcessBuilder();

        $builder
            ->setWorkingDirectory(\Yii::getAlias('@app'))
            ->setPrefix($this->getPhpExecutable())
            ->setArguments([
                realpath(Yii::getAlias('@app') . '/yii'),
                'migrate/' . ($down ? 'down' : 'up'),
                '--color=0',
                '--interactive=0',
                '--migrationTable=' . $migrationTable,
                $down ? 65536 : 0,
            ]);

        if (empty($migrationPath) === false) {
            $builder->add('--migrationPath=' . $migrationPath);
        }

        return $builder;
    }

    /**
     * @param User               $model
     * @param \yii\db\Connection $db
     *
     * @return bool
     */
    public static function createAdminUser(User $model, \yii\db\Connection $db)
    {
        $db->createCommand()
            ->insert('{{%user}}', [
                'username'      => $model->username,
                'first_name'    => $model->first_name,
                'last_name'     => $model->last_name,
                'password_hash' => Yii::$app->security->generatePasswordHash($model->password),
                'email'         => $model->email,
                'auth_key'      => '',
                'created_at'    => time(),
                'updated_at'    => time(),
                'added_by'      => 'core',
            ])
            ->execute();

        $userId = intval($db->lastInsertID);
        $assignmentResult = $db->createCommand()
                ->insert(
                    '{{%auth_assignment}}',
                    [
                        'item_name' => 'admin',
                        'user_id'   => $userId,
                    ]
                )
                ->execute() === 1;

        return ($assignmentResult && $userId > 0);
    }

    public static function createCommonConfigFile(Last $model)
    {
        $common_config = [
            'language'   => Yii::$app->session->get('language', 'ru'),
            'components' => [
                'cache' => [
                    'class'     => $model->cacheClass,
                    'keyPrefix' => $model->keyPrefix,
                ],
            ],
            'modules'    => [
                'base' => [
                    'serverName' => $model->serverName,
                    'serverPort' => $model->serverPort,
                ],
            ],
        ];

        if ($model->cacheClass === 'yii\caching\MemCache') {
            $common_config['components']['cache']['useMemcached'] = $model->useMemcached;
        }

        return file_put_contents(
                Yii::getAlias('@app/config/common-local.php'),
                "<?php\nreturn " . VarDumper::export($common_config) . ';'
            ) > 0;
    }

    /**
     * @return string Returns path to PHP executable based on predefined PHP variable PHP_BINDIR
     */
    private function getPhpExecutable()
    {
        return PHP_BINDIR . '/php';
    }

    /**
     * @param $config
     *
     * @return bool
     */
    public static function createDatabaseConfigFile($config)
    {
        $content = "<?php\nreturn " . VarDumper::export($config) . ";\n";

        return file_put_contents(Yii::getAlias('@app/config/db-local.php'), $content) > 0;
    }

    /**
     * @return bool
     */
    public static function unlimitTime()
    {
        return set_time_limit(0);
    }
}