<?php

namespace vision\ytbwidget;

use yii\base\InvalidParamException;
use yii\helpers\Html;

/**
 * Simple widget
 */
class YouTube extends \yii\base\Widget
{

    const ID_JS = 'APIReady';
    const POS_JS = 1;
    const START_JS = "function onYouTubePlayerAPIReady() {";

    protected $defaultSettings = Array(
        'controls' => 1, //Этот параметр определяет, будут ли отображаться элементы управления проигрывателем. 0- не отображать, 1 или 2 - отображать
        'autoplay' => 0, // Определяет, начинается ли воспроизведение исходного видео сразу после загрузки проигрывателя.
        'showinfo' => 0, //Значения: 0 или 1. Значение по умолчанию: 1. При значении 0 проигрыватель перед началом воспроизведения не выводит информацию о видео, такую как название и автор видео.
        'start '   => 0, //Значение: положительное целое число. Если этот параметр определен, то проигрыватель начинает воспроизведение видео с указанной секунды.
        'loop '    => 0, //Значения: 0 или 1. Значение по умолчанию: 0. Если значение равно 1, то одиночный проигрыватель будет воспроизводить видео по кругу, в бесконечном цикле.
        'modestbranding'=>1 //Этот параметр позволяет использовать проигрыватель YouTube, в котором не отображается логотип YouTube.
    );

    /*
     * @var set height video player
     */
    public $height = 390;

    /*
     * @var set width video player
     */
    public $width = 640;

    /*
     *@var set videoId youtube
     */
    public $videoId;

    /*
     * details https://developers.google.com/youtube/player_parameters?playerVersion=HTML5&hl=ru#playerapiid
     * @var set players settings
     */
    public $playerVars = Array();

    /*
     * @var set events
     */
    public $events = Array();

    /*
     * globals vars player = player_{id div}
     * @var set id div, default random
     */
    public $divId;

    /*
     * @var
     */
    protected $_playerVars = Array();

    /*
     * unique id elements
     * @var string
     */
    protected $_idElement;


    public function init()
    {
        parent::init();
        $this->_idElement = $this->createId();
    }

    /**
     * Registers the JavaScript Player API
     */
    protected function registerAssetBundle() {
        $view = $this->getView();
        $js = '';
        $js .= "var player_" . $this->_idElement .";";
        $view->registerJs($js, 1);
        $script = '';
        if(!isset($this->view->js[self::POS_JS][self::ID_JS])) {
            $script .= self::START_JS . $this->generateJs($this->_idElement);
            $script .= "}";
        } else {
            $script = $this->addJs($this->generateJs($this->_idElement));
        }
        $view->registerJs($script, self::POS_JS, self::ID_JS);
    }


    /*
     * Merge script for many players
     */
    protected function addJs($js) {
        $script = $this->view->js[self::POS_JS][self::ID_JS];
        $new_script = str_replace(self::START_JS, self::START_JS . ' ' . $js, $script);
        return $new_script;
    }

    /*
     * check setting
     */
    protected function checkSettings() {
        if(!$this->videoId){
            throw new InvalidParamException('Не указан идентификатор видео.');
        }
        $this->normalizeSettings();
    }

    /*
     * generate random Id for div-container
     * @return string
     */
    protected function createId() {
        if($this->divId){
            return $this->divId;
        }
        $length = 5;
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return 'vdo'.$string;
    }


    protected function getHtml($id) {
        $html = Html::tag('div', '', [
            'id' => $id
        ]);
        return $html;
    }

    /*
     * Generate js
     * @return string
     */
    protected function generateJs($idDiv) {
        $this->_playerVars = $this->normalizeSettings();
        $js_events = '';
        $js  = '';
        $js .= "player_". $idDiv ." = new YT.Player('$idDiv', {";
        $js .= "height: '".$this->height."',";
        $js .= "width: '".$this->width."',";
        $js .= "videoId: '".$this->videoId."',";
        //add player vars
        if(count($this->_playerVars) > 0){
            $js .= "playerVars: {";
            foreach($this->_playerVars as $name => $val){
                $js .= "$name:'$val',";
            }
            $js .= "}";
        }
        //add player events
        if(count($this->events) > 0){
            $js_events .= ", events: {";
            foreach($this->events as $name => $val) {
                $js_events .= "$name : $val,";
            }
            $js_events .= "}";
        }
        $js .= $js_events . "});";
        return $js;
    }


    /*
     * set default settings
     */
    protected function normalizeSettings() {
        if(!isset($this->playerVars['hl'])) {
            $this->playerVars['hl'] = substr(\Yii::$app->language, 0, 2 );
        }
        if(isset($this->playerVars['loop']) && $this->playerVars['loop']){
            $this->playerVars['playlist'] = $this->videoId;
        }
        return array_merge($this->defaultSettings, $this->playerVars);
    }


    public function run()
    {
        try {
            YouTubeAsset::register($this->view);
            $this->registerAssetBundle();
            $html = $this->getHtml($this->_idElement);
        }catch(\Exception $e){
            \Yii::error($e);
            $html = Html::tag('div', 'Video error');
        }
        return $html;
    }
}
