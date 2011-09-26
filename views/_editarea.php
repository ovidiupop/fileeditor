	

	<?php
		echo CHtml::textArea(
			$this->params['name'].$this->feId, 
			$this->params['value'],
			array(
				'id'=> $this->feId,
				'class'=>$this->params['class'],
				'encode'=> $this->encode,
				'rows'=> $this->params['rows'],
				'cols'=> $this->params['cols']
			)
		);
	?>