
	<div class="zoneTools" id="zoneTools_<?php echo $this->uniq;?>">
		<div class="ferow clearfix">
			<div class="felabel">
				<?php echo $this->translations[10];?>
			</div>
			<div class="fefield">
				<?php echo CHtml::textField("new_name_".$this->uniq, '', array('class'=>'feInputNewDocument' ));?>
			</div>
		</div>
		<div class="ferow clearfix">
			<div class="felabel">
				<?php echo $this->translations[11];?>
			</div>
			<div class="fefield">
				<?php echo CHtml::dropDownList("dirs_".$this->uniq, '', $this->folders, array('class'=>'feDirsList' ));?>
			</div>
		</div>
	</div>
