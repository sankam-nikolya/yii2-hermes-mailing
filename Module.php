<?php
/**
 * @link https://github.com/thinker-g/yii2-hermes-mailing
 * @copyright Copyright (c) thinker_g (Jiyan.guo@gmail.com)
 * @license MIT
 * @version v1.0.0
 * @author thinker_g
 */
namespace thinker_g\HermesMailing;

use Yii;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class Module extends \yii\base\Module implements BootstrapInterface
{

    const EVENT_UNKNOWN_APP = 'hermesUnknownApp';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'thinker_g\HermesMailing\controllers';

    /**
     * Email AR model name.
     * Make sure the model class is imported after installation.
     * LEAVE THIS NULL, unless the model name is not generated by the table name.
     * If this is set, table name will be retrieved by "tableName()" of its instance.
     * This is for using some already existed AR. Won't be needed for default installation.
     * @var string
     */
    public $modelClass = 'app\\models\\HermesMail';

    /**
     * Search model class, if this is left empty, class name of $modelClass will be used.
     * @var string
     */
    public $searchModelClass;

    /**
     * Allow only listed roles to access this module.
     * Defaultly allow all authenticated users to access, leave it null or false to let
     * all user access, including unauthenticated users.
     * @var array
     */
    public $roles = ['@'];

    /**
     * View id used for index Action.
     * Will be passed to render() method of the controller.
     * @var string
     */
    public $indexView = 'index';

    /**
     * View id used for view Action.
     * Will be passed to render() method of the controller.
     * @var string
     */
    public $createView = 'create';

    /**
     * View id used for update Action.
     * Will be passed to render() method of the controller.
     * @var string
     */
    public $updateView = 'update';

    /**
     * View id used for view Action.
     * Will be passed to render() method of the controller.
     * @var string
     */
    public $viewView = 'view';

    /**
     * @override
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {

        if ($app instanceof \yii\web\Application) {

        } elseif ($app instanceof \yii\base\Application) {

        } else {
            $this->trigger(static::EVENT_UNKNOWN_APP);
        }
    }

    public function init()
    {
        parent::init();
        if (is_null($this->searchModelClass)) {
            if (is_array($this->modelClass)) {
                $this->searchModelClass = $this->modelClass['class'];
            } else {
                $this->searchModelClass = $this->modelClass;
            }
        }
        Yii::$app->getView()->params['sidebarMenu'] = [
            ['label' => 'Splash page', 'url' => ['default/index']],
            ['label' => 'View queue', 'url' => ['mail/index']],
            ['label' => 'New mail', 'url' => ['mail/create']],
        ];
    }

    /* (non-PHPdoc)
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->roles
                    ]
                ]
            ]
        ]);

    }




}

?>
