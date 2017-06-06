<?php

namespace app\install\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\VarDumper;
use app\install\models\Config;
use app\install\models\Migration;
use app\install\components\InstallHelper;
use app\install\models\User;
use app\install\models\Last;

/**
 * Class InstallController
 * @package app\install\controllers
 */
class InstallController extends Controller
{
    public $layout = 'install';
    private $db = null;

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect('install');
    }

    /**
     * @return string
     */
    public function actionRequirements()
    {
        $phpVersion = version_compare(PHP_VERSION, '5.5.0') >= 0;

        $this->view->title = Yii::t('install', 'Install: {action}', [
            'action' => Yii::t('install', 'check requirements'),
        ]);

        return $this->render('requirements', [
            'phpVersion' => $phpVersion,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionDatabase()
    {
        $config = $this->getConfigFromSession();
        $model = new Config();
        $model->setAttributes($config);
        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->validate()) {
            $config = $model->getAttributes();
            $config['connectionOk'] = false;

            Yii::$app->session->set('db-config', $config);

            if ($model->testConnection()) {
                $config['connectionOk'] = true;

                if (isset($_POST['next'])) {
                    Yii::$app->session->set('db-config', $config);

                    return $this->redirect(['migrate']);
                }

                Yii::$app->session->setFlash('success', Yii::t('install', 'Success connection!'));
            }
        }

        $this->view->title = Yii::t('install', 'Install: {action}', [
            'action' => Yii::t('install', 'setting up a connection to the database'),
        ]);

        return $this->render('database', [
            'model'  => $model,
            'config' => $config,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionMigrate()
    {
        $model = new Migration();
        $model->install_demo_data = Yii::$app->session->get('install_demo_data', false);
        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->validate()) {
            foreach ($model->getAttributes() as $key => $value) {
                Yii::$app->session->set($key, $value);
            }
        }

        $config = $this->getConfigFromSession();
        $configModel = new Config();
        $configModel->setAttributes($config);

        /** @var InstallHelper $helper */
        $helper = Yii::createObject([
            'class' => InstallHelper::className(),
        ]);

        $process = $helper->applyMigrations(false);

        if (Yii::$app->request->isPost) {
            if ($configModel->testConnection()) {
                $config = InstallHelper::createDatabaseConfig($configModel->getAttributes());
                $configOk = true;

                if (InstallHelper::createDatabaseConfigFile($config) === false) {
                    Yii::$app->session->setFlash('warning', Yii::t('install', 'Unable to create db-local config file'));
                    $configOk = false;
                }

                if ($configOk === true) {
                    $process->run();
                    if ($process->getExitCode() === 0) {
                        return $this->redirect(['user']);
                    } else {
                        Yii::$app->session->setFlash('danger', Yii::t('install', 'Migrations not completed!'));
                    }
                }
            }
        }

        $this->view->title = Yii::t('install', 'Install: {action}', [
            'action' => Yii::t('install', 'database migration'),
        ]);

        return $this->render('migrate', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionUser()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (InstallHelper::createAdminUser($model, $this->db())) {
                return $this->redirect(['last']);
            }
        }

        $this->view->title = Yii::t('install', 'Install: {action}', [
            'action' => Yii::t('install', 'create admin profile'),
        ]);

        return $this->render('user', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionLast()
    {
        $model = new Last();
        $model->serverName = Yii::$app->request->serverName;
        if (Yii::$app->request->serverPort !== 80) {
            $model->serverPort = Yii::$app->request->serverPort;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (InstallHelper::createCommonConfigFile($model)) {
                return $this->redirect(['complete']);
            }
        }

        $cacheClasses = $model::getCacheClasses();

        return $this->render('last', [
            'model'        => $model,
            'cacheClasses' => $cacheClasses,
        ]);
    }

    /**
     * @param null $redirect
     *
     * @return string
     */
    public function actionComplete($redirect = null)
    {
        if (Yii::$app->request->post() && $redirect) {
            $r = '/';
            $config = [
                'installed' => true
            ];
            $content = "<?php\nreturn " . VarDumper::export($config) . ";\n";

            file_put_contents(Yii::getAlias('@app/installed.php'), $content);

            switch ($redirect) {
                case 'frontend':
                    $r = '/';
                    break;
                case 'backend':
                    $r = '/dashboard';
                    break;
            }

            $this->redirect($r);
        }

        $this->view->title = Yii::t('install', 'Install completed!');

        return $this->render(
            'complete'
        );
    }

    /**
     * @return mixed
     */
    private function getConfigFromSession()
    {
        return Yii::$app->session->get('db-config', [
            'db_host'             => 'localhost',
            'db_name'             => 'linex',
            'username'            => 'root',
            'password'            => '',
            'enableSchemaCache'   => true,
            'schemaCacheDuration' => 86400,
            'schemaCache'         => 'cache',
            'connectionOk'        => false,
        ]);
    }

    /**
     * @return null|object
     */
    private function db()
    {
        if ($this->db === null) {
            $config = InstallHelper::createDatabaseConfig($this->getConfigFromSession());
            $dbComponent = Yii::createObject(
                $config
            );

            $dbComponent->open();
            $this->db = $dbComponent;
        }

        return $this->db;
    }
}