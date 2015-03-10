<?php
namespace thinkerg\HermesMailing\installer\actions;

use yii\helpers\Console;
use yii\db\Exception as DbException;
use Yii;

class InstallAction extends InstallerAction
{
    public function run()
    {
        $this->checkGii();
        $migration = $this->migration;
        if (!$this->controller->confirm("Create table {$migration->tableName}?")) {
            $this->controller->userCancel();
        }

        $this->controller->stdout("Migrating database...\n");
        try {
            $migrated = $migration->up();
        } catch (DbException $e) {
            $err = "\nError({$e->getCode()}): Database migration failed!" . PHP_EOL;
            $err .= $e->getMessage() . PHP_EOL;
            $this->controller->stderr($err, Console::FG_RED);
            $this->controller->stderr("Installion aborted!" . PHP_EOL, Console::FG_YELLOW);
            return 1;
        }

        $params = [
            'tableName' => $migration->tableName,
            'modelClass' => $this->getModelClassName($migration->tableName)
        ];
        Yii::$app->set('db', $migration->db);
        Yii::$app->runAction("/{$this->giiID}/model", $params);

        $this->controller->stdout("Install complete!\n", Console::FG_GREEN);

    }

}

?>