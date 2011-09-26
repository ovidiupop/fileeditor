var EditArea_new= {
	init: function(){
		editArea.load_css(this.baseURL+"css/new.css");
	}
	,get_control_html: function(ctrl_name){
		switch(ctrl_name){
			case "new":
				// Control id, button img, command
				return parent.editAreaLoader.get_button_html('new', 'newdocument.gif', 'new', false, this.baseURL);
		}
		return false;
	}
	,onload: function(){ 
	}
	,execCommand: function(cmd, param){
		switch(cmd){
			case "new":
				parent.new_callback(editArea.id);
				return false;
		}
		return true;
	}
};

editArea.add_plugin("new", EditArea_new);
