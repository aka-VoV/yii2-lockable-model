
Migrations
===
on Yii 2.0.10

````
'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationPath' => [
                '@app/migrations',
                '@yii/rbac/migrations', // Just in case you forgot to run it on console (see next note)
                '@dkit/lockable/console/migrations'
            ],          
        ],
    ],
````
oldest

````
php yii migrate/up --migrationPath=@vendor/dk-it/yii2-lockable-model/console/migrations
````