translations=new Array();
/**[
 *0 'Succes!',
 *1 'Failure!',
 *2 'Filename is empty!',
 *3 'Select a directory for the new file!',
 *4 'Give an extension to filename!',
 *5 'Yes',
 *6 'No',
 *7 'Cancel',
 *8 'Save file?',
 *9 'Create new file',
 *10 'Filename:',
 *11 'Folder',
 *12 'Save'

 * ]*/
poster ='';
editor_id='';
editorwidth=''

function fileeditor(reqpost,ew, t){
	poster = reqpost;
	editorwidth = parseInt(ew);
	translations = t;
}

function setEditorId(id){
	editor_id = id;
}

function closeFileSelector(id){
	var uniq = id.split('_')[1];
	$('#fileselector_'+uniq).hide();
	$('#frame_editor_'+uniq).css({'width': editorwidth+'px'});
	$('.closer').hide();
	$('.fileselector').unbind('dblclick');
}

function loadFileEditor(id){
	var uniq = id.split('_')[1];
	var fileselector = $('#fileselector_'+uniq);
	var feditor = $('#frame_'+id);
	var height = feditor.height();
	
	if(fileselector.is(':visible')){
		fileselector.hide();
		feditor.css({'width':editorwidth+'px'});
	}else{
		fileselector.css({'width':'170px','height':height, 'border':'1px solid #888888'});
		feditor.css({'width':(editorwidth-170)+'px'});
		fileselector.show();
	}
		
	$('.fileselector').dblclick(function(){
		var filename = $('#fileselector_'+uniq+' :selected').text();
		var splites = filename.split('.');
		var syntax = splites[splites.length -1];
		loadFile(id, $(this).val(), filename, syntax);
	});
}



function closeFileEditor(file){
	var file_id = file.id;
	if(!file.edited) return true;

	var buttons = {};
	buttons[translations[6]] = function(){//No
		jQuery(this).dialog('close');
		editAreaLoader.setFileEditedMode(editor_id, file_id, false).closeFile(editor_id, file_id);
	};
	buttons[translations[12]] = function()//Save and close
	{
		jQuery(this).dialog('close');
		$.post(poster+'/putContent', 'path='+encodeURIComponent(file.id)+'&filecontent='+encodeURIComponent(file.text), function(res){
			if(res){
				alert(translations[0]);
				editAreaLoader.setFileEditedMode(editor_id, file_id, false).closeFile(editor_id, file_id);
			}else{
				alert(translations[1]);
				return false;
			}
		});
	};
	buttons[translations[7]] = function(){//Cancel
		jQuery(this).dialog('close');
		return false;
	};
	jQuery('#dialogSaveFile').dialog({
		buttons: buttons,
		title: translations[8]
	}).dialog('open');

	return false;
}


function new_callback(id){

	var buttons = {};
	buttons[translations[9]] = function(){//create new file

		var uniq = id.split('_')[1];
		var filename = $('#new_name_'+uniq).val();
		var directory = $('#dirs_'+uniq).val();
		var x= filename.split('.');
		var extension = x[x.length-1];

		if(!filename){alert(translations[2]);return false;}
		if(!directory){alert(translations[3]);return false;}
		if(x.length<2){alert(translations[4]);return false;}

		if(filename && directory){
			$.post(poster+'/newFile', 'filename='+filename+'&directory='+directory, function(res){
				if(res){
					loadFile(id, directory+'/'+filename, filename, extension);
					$('#fileselector_'+uniq).prepend('<option value="'+directory+'/'+filename+'" selected="selected" class="newfileinserted">'+filename+'</option>');
				}else{
					alert(translations[1]);
				}
			});
		}
	
		jQuery(this).dialog('close');
	};
	buttons[translations[7]] = function(){//cancel
		jQuery(this).dialog('close');
		return false;
	};
	jQuery('#dialogNewFile').dialog({
		buttons: buttons,
		title: translations[9]
	}).dialog('open');
}


function loadFile(id, filepath, filename, syntax){
	$.post(poster+'/getContent', 'filepath='+filepath, function(res){
		var new_file= {id: filepath, text: res, syntax: syntax, title: filename};
		editAreaLoader.openFile(id, new_file);
		closeFileSelector(id);
	});
}

function saveFileEditor(id, content){
	var filepath = editAreaLoader.getCurrentFile(id).id;
	if(filepath){
		$.post(poster+'/putContent', 'path='+encodeURIComponent(filepath)+'&filecontent='+encodeURIComponent(content), function(res){
			if(res){
				alert(translations[0]);
				editAreaLoader.setFileEditedMode(id, filepath, false);
			}else{
				alert(translations[1])
			}
		});
	}
}