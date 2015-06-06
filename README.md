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



Options: "https://developers.google.com/youtube/player_parameters?playerVersion=HTML5&hl=ru#playerapiid"

Events: "https://developers.google.com/youtube/js_api_reference?hl=ru#EventHandlers"

Once the extension is installed, simply use it in your code by  :

Minimum: 

```
<?= \vision\ytbwidget\YouTube::widget([
    'videoId' => 'OQQpukc_IM4'    
]) ?>



<?= \vision\ytbwidget\YouTube::widget([

    'videoId' => 'MA6tk7u44mM',
    
    'divId' => 'myDiv', //if set this option in js Globals you player is var player_myDiv, else player_{random val}
    
    'playerVars'=>[
    
        'modestbranding'=>1
        
    ],
    
    'events' => [
    
        'onStateChange' => 'function (event) {
        
            if (event.data == YT.PlayerState.PLAYING) {
            
                console.log("Playing..");
                
            }else if (event.data == YT.PlayerState.PAUSED){
            
                console.log("Paused..");
                
            }else{
            
                console.log("Buffering/Video Ended");
                
            }
            
        }'
        
    ]    
]) ?>

```