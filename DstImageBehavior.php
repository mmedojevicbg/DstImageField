<?php
class DstImageBehavior extends CBehavior {
    public function saveImage($imageFieldName, $imageSaveDirectory) {
        $state = Yii::app()->request->getPost(DstImageField::getStateHiddenFieldName($imageFieldName));
        if($state == DstImageField::STATE_CHOSEN) {
            $image = CUploadedFile::getInstanceByName(DstImageField::getFileFieldName($imageFieldName));
            $image->saveAs($imageSaveDirectory . $image, false);
        }
    }
}