DstImageField
=============

Image upload widget for YII 1.x framework.

### Features
* preview of currrent image
* possibility to delete current image without uploading new image
* checks whether submitted image is valid 
* checks whether submitted image is in specified dimensions
* provides state variable which indicates whether image left unchanged or new image is uploaded

### Examples

#### Add widget to active form
	$this->widget('ext.DstImageField.DstImageField',array(
			'model'=>$model,
			'attribute'=>'image_full',
			'absolute_path' => '/images/full/'
	));

#### Add widget using form builder	
	'image_full'=>array(
		'type'=>'ext.DstImageField.DstImageField',
		'absolute_path' => '/images/full/'
	)

#### Add validator with basic configuration	
	array('image_full', 
		  'ext.DstImageField.DstImageValidator'
	)
_It only checks wheter submitted image is valid. Validation will not be trigered if value is empty, it can be done using required validator._

#### Add validator with additional configuration		
	array('image_full', 
		  'ext.DstImageField.DstImageValidator', 
		  'maxWidth' => 640,
		  'minWidth' => 200,
		  'maxHeight' => 480,
		  'minHeight' => 120
	)
_Any of additional attributes can be ommited_

#### Handle form submit and save file to disk
	Yii::import('ext.DstImageField.DstImageField');
        
	$state = Yii::app()->request->getPost(DstImageField::getStateHiddenFieldName('image_full'));
	
	if($state == DstImageField::STATE_CHOSEN) {
		$image_full = CUploadedFile::getInstanceByName(DstImageField::getFileFieldName('image_full'));
		$image_full->saveAs('images/content-location/' . $image_full, false);

		$image = Yii::app()->image->load('images/content-location/' . $image_full);
		$image->resize(640, 480)->quality(50);
		$image->save();
	}
Possible states for DstImageField are: 
* STATE_CHOSEN - in this case new image has been submitted and storage can be handled with code above
* STATE_EMPTY - image has not been submitted so if field is not required empty value will bi set to model
* STATE_UNCHANGED - image has not been submitted but model already contains image which will be shown in preview.