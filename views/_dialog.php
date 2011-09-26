<div style="display:none">
<?php
	//saveClosingFile
	$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
		'id'=>'dialogSaveFile',
		'options'=>array(
// 			'title'=>Yii::t('app', 'Save'),
			'autoOpen'=>false,
			'modal'=>'true',
			'width'=>'300px',
			'height'=>'auto',
			'resizable'=>'false',
		),
		));

		echo $this->translations[8];
	$this->endWidget('zii.widgets.jui.CJuiDialog');


	//newFile
	$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
		'id'=>'dialogNewFile',
		'options'=>array(
			'autoOpen'=>false,
			'modal'=>'true',
			'width'=>'300px',
			'height'=>'auto',
			'resizable'=>'false',
		),
		));

		$this->render('_newFile', array());
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
</div>