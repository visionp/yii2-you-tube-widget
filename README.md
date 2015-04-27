Widget
======
Simple widget you tube for yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist vision/yii2-you-tube-widget "*"
```

or add

```
"vision/yii2-you-tube-widget": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

<?= \vision\ytbwidget\YouTube::widget([
    'videoId' => 'MA6tk7u44mM',
    'width' => 640,
    'height' => 390,
    'playerVars'=>[
        'modestbranding'=>1,
        ...
    ],
    'events' => [
    ...
    ]
]); ?>