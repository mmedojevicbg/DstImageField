<?php
/**
 * DstImageValidator class file.
 *
 * @author Marko Medojevic <mmedojevicbg@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html
 */

/**
 * DstImageValidator verifies if an image is valid. It can optionally
 * check if image is in specified dimension.
 * 
 * @author Marko Medojevic <mmedojevicbg@gmail.com>
 */
class DstImageValidator extends CValidator {
    /**
     * @var integer maximal allowed width.
     * Defaults to 0, meaning a max-width check is ommited.
     */
    protected $maxWidth = 0;
    
    /**
     * @var integer minimal allowed width.
     * Defaults to 0, meaning a min-width check is ommited.
     */
    protected $minWidth = 0;
    
    /**
     * @var integer maximal allowed height.
     * Defaults to 0, meaning a max-height check is ommited.
     */
    protected $maxHeight = 0;
    
    /**
     * @var integer minimal allowed height.
     * Defaults to 0, meaning a min-height check is ommited.
     */
    protected $minHeight = 0;
    
    /**
     * Validates attribute.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute) {
        Yii::import('ext.DstImageField.DstImageField');
        $fileFieldName = DstImageField::getFileFieldName($attribute);
        $fileField = CUploadedFile::getInstanceByName($fileFieldName);
        
        if($fileField) {
            $imageParams = $this->getImageParams($fileField->getTempName());
            if(!$imageParams) {
                $this->addError($object,$attribute,Yii::t('yii','{attribute} is not a valid image.'));    
            } else {
                if(!$this->areDimensionsCorrect($imageParams['width'], $imageParams['height'])) {
                    $this->addError($object,$attribute,Yii::t('yii','{attribute} is not in specified dimensions.'));     
                }
            }
        }
    }  
    
    /**
     * Executes php function getimagesize in order to retrieve image
     * info (width, height).
     * @param string $imagePath full image path
     * @return mixed image info or false if file is not valid image
     */
    protected function getImageParams($imagePath) {
        $imageParams = getimagesize($imagePath);
        if($imageParams) {
            return array(
                'width' => $imageParams[0],
                'height' => $imageParams[1],
                'mime' => $imageParams['mime']
            ); 
        } else {
            return false;
        }    
    }  
    
    /**
     * Check if image is in specified dimensions
     * @param integer $imageWidth width of uploaded image
     * @param integer $imageHeight height of uploaded image
     * @return boolean whether the image is in specified dimensions
     */
    protected function areDimensionsCorrect($imageWidth, $imageHeight) {
        $correct = true;
        
        if($this->maxWidth) {
            if($imageWidth > $this->maxWidth) {
                $correct = false;
            }
        }
        
        if($this->minWidth) {
            if($imageWidth < $this->minWidth) {
                $correct = false;
            }
        }
        
        if($this->maxHeight) {
            if($imageHeight > $this->maxHeight) {
                $correct = false;
            }
        }
        
        if($this->minHeight) {
            if($imageHeight < $this->minHeight) {
                $correct = false;
            }
        }
        
        return $correct;
    }
}
