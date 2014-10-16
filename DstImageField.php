<?php
/**
 * DstImageField class file.
 *
 * @author Marko Medojevic <mmedojevicbg@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html
 */

/**
 * DstImageField is a form widget for image upload
 *
 * @author Marko Medojevic <mmedojevicbg@gmail.com>
 * @version 0.9
 */
class DstImageField extends CWidget
{
    /**
     * State constant. Image has not been chosen yet.
     */
    const STATE_EMPTY = 0;
    
    /**
     * State constant. Image is chosen.
     */
    const STATE_CHOSEN = 1;
    
    /**
     * State constant. Image has not been changed.
     */
    const STATE_UNCHANGED = 2;
    
    /**
     * @var CModel the data model associated with this widget.
     */
    public $model;
    
    /**
     * @var string the attribute associated with this widget.
     */
    public $attribute;
    
    /**
     * @var string absolute path where image is going to be stored.
     */
    public $absolute_path;
    
    /**
     * @var integer width of preview image
     */
    public $preview_width = '160';
    
    /**
     * @var integer height of preview image
     */
    public $preview_height = '120';
    
    /**
     * @var string absolute path where extension asset is publishedS
     */
    protected $assets;
    
    /**
     * Run this widget.
     */
    public function run()
    {       
        $this->initAssets();    
        
        $stateValue = self::STATE_EMPTY;
        if($this->getCurrentValue()) {
            $stateValue = self::STATE_UNCHANGED;    
        }
      
        $html = '';
        $html .= CHtml::openTag('div', array('class' => 'dst-image-field-container'));
        $html .= CHtml::openTag('div', array('class' => 'dst-image-field-current-image-container', 'style' => "width: {$this->preview_width}px; height: {$this->preview_height}px;"));
        $html .= CHtml::openTag('div', array('class' => 'dst-image-field-current-image-container-header'));
        $html .= CHtml::link('', '#', array('class' => 'dst-image-field-link dst-image-field-delete-link', 'style' => "background: transparent url('" . $this->assets . '/images/delete.png' . "') no-repeat center center"));
        $html .= CHtml::link('', '#', array('class' => 'dst-image-field-link dst-image-field-zoom-link', 'style' => "background: transparent url('" . $this->assets . '/images/zoom.png' . "') no-repeat center center"));
        $html .= CHtml::closeTag('div');
        $html .= CHtml::image($this->getCurrentImageFullPath(), 'image', array('class' => 'dst-image-field-current-image', 'style' => "width: {$this->preview_width}px; height: {$this->preview_height}px;"));
        $html .= CHtml::closeTag('div');
        $html .= CHtml::fileField(self::getFileFieldName($this->attribute), false, array('class' => 'dst-image-field-fileupload'));
        $html .= Chtml::hiddenField(self::getStateHiddenFieldName($this->attribute), $stateValue, array('class' => 'dst-image-field-state'));
        $html .= Chtml::activeHiddenField($this->model, $this->attribute, array('class' => 'dst-image-field-value'));
        $html .= CHtml::closeTag('div');
        
        echo $html;
    }
    
    /**
     * Returns image full path
     * @return string image full path
     */
    protected function getCurrentImageFullPath() {
        return $this->absolute_path . $this->getCurrentValue();    
    }
    
    /**
     * Returns image file name
     * @return string image file name
     */
    protected function getCurrentValue() {
        $this->refreshCurrentValue();
        return $this->model->{$this->attribute};
    }
    
    /**
     * Returns name of embeded HTML file upload tag.
     * @param string $attribute attribute name
     * @return string name of embeded HTML file upload tag
     */
    public static function getFileFieldName($attribute) {
        return 'dst-image-field-fileupload-' . $attribute;
    }
    
    /**
     * Returns name of embeded HTML hidden state tag.
     * @param string $attribute attribute name
     * @return string name of embeded HTML hidden state tag
     */
    public static function getStateHiddenFieldName($attribute) {
        return 'dst-image-field-state-' . $attribute;
    }
    
    /**
     * This method registers necessary javascript and css files.
     */
    protected function initAssets() {
        Yii::app()->clientScript->registerCoreScript('jquery');
        $this->assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($this->assets.'/init.js',CClientScript::POS_END);
        $cs->registerCssFile($this->assets.'/style.css');
    }
    
    /**
     * Refreshes image field value.
     */
    protected function refreshCurrentValue() {
        $model = $this->model->findByPk($this->model->getPrimaryKey());
        if($model) {
            $this->model->{$this->attribute} = $model->{$this->attribute}; 
        } else {
            $this->model->{$this->attribute} = '';
        }
    }
}