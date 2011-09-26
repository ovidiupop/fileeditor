<?php

/**
 *fileeditor.php
 *
 * @author Ovidiu Pop <matricks@webspider.ro>
 * @copyright 2011 Binary Technology
 * @license released under dual license BSD License and LGP License
 * @package fileeditor
 * @version 0.1
 */

class fileeditor extends CInputWidget
{
	/**
	 * @var array paths of browseable folders
	 */
	public $arrFolders=array();
	/**
	 * @var array labels of browseable folders
	 */
	public $arrTypes = array();

	public $options = array(
		'name'=>'feditor',
		'class'=>'feditor',
		'cols'=>84,
		'rows'=>20,
		'editorwidth'=> '720',//px 
		'value'=> '', 
		'language'=>'en',
		'syntax'=> 'css',
		'is_editable'=>true,
		'toolbar'=>'new, load, save, |, search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help',
		'allow_toggle'=>true,
		'start_highlight'=>true,
		'EA_load_callback'=>'setEditorId',
		'EA_file_close_callback'=>'closeFileEditor',
		'load_callback'=> 'loadFileEditor',
		'save_callback'=> 'saveFileEditor'
	);

	/**
	 * @var array translations used for alerts and dialogs
	 */
	protected $translations=array();
	/**
	 * @var url for processing post requests
	 */
	protected $reqPost;

	/**
	 * @var array associative array to to feed data for fileselector listBox
	 */
	protected $arrDirs;

	/**
	 * @var array parameters
	 */
	protected $params;
	/**
	 * @var string uniqid of editor
	 */
	protected $feId;
	/**
	 * @var string uniq number as suffix
	 */
	protected $uniq;

	/**
	 * @var boolean use encode
	 */
	protected $encode=true;

	/**
	 * @var array temporary array to keep list of browseable folders
	 */
	protected $folders = array();


	/**
	 * The extension initialisation
	 *
	 * @return nothing
	 */

	public function init()
	{
		$this->translations=array(
			Yii::t('fileeditor', 'Succes!'),
			Yii::t('fileeditor', 'Failure!'),
			Yii::t('fileeditor', 'Filename is empty!'),
			Yii::t('fileeditor', 'Select a directory for the new file!'),
			Yii::t('fileeditor', 'Give an extension to filename!'),
			Yii::t('fileeditor', 'Yes'),
			Yii::t('fileeditor', 'No'),
			Yii::t('fileeditor', 'Cancel'),
			Yii::t('fileeditor', 'Save file?'),
			Yii::t('fileeditor', 'Create new file'),
			Yii::t('fileeditor', 'Filename:'),
			Yii::t('fileeditor', 'Folder'),
			Yii::t('fileeditor', 'Save'),
		);

		if(!isset($this->reqPost))
			$this->reqPost = self::setPostUrl();

		if (is_array($this->options) && count($this->options))
			foreach($this->options as $k=>$v)
				$this->params[$k]=$v;

		$this->params['value'] = $this->value;
		$this->params['class'] .= ' feditor_textarea';
		$this->uniq = uniqid();
		$this->feId = $this->params['name'].'_'.$this->uniq;

		self::setFolders();
		self::registerFiles();
		self::renderFileEditor();
	}


	/**
	 * Create POST's processing url
	 *
	 * @return url
         * this one need to be adjusted to create a specific url for site
	 */
	private function setPostUrl()
	{
		return Yii::app()->createUrl('fileeditor');
	}

	/**
	 * Populate folders arrays
	 *
	 * @return nothing
	 */
	private function setFolders()
	{
		foreach($this->arrFolders as $path){
			$p = explode(DIRECTORY_SEPARATOR, $path);
			$this->folders[$path] = $p[count($p)-1];
		}

		foreach($this->arrFolders as $k => $folder)
			$this->arrDirs[$this->arrTypes[$k]] = self::assocFilesFromDir($folder.DIRECTORY_SEPARATOR);
	}

	/**
	 * Create a string to be send to js from array
	 *
	 * @return string
	 */
	private function phptojs($array)
	{
		$jsArr = "[";
		$l = count($array);
		foreach($array as $k => $t)
			$jsArr .= $k<$l-1 ? "'$t',":"'$t'";
		$jsArr .= "]";
		return $jsArr;
	}

	/**
	 * Register assets file and initialise EditArea plugin
	 *
	 * @return nothing
	 */
	private function registerFiles()
	{
		$assets = dirname(__FILE__).'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);

		if(is_dir($assets)){
			Yii::app()->clientScript->registerCssFile($baseUrl . '/fileeditor.css');
			Yii::app()->clientScript->registerScriptFile($baseUrl.'/edit_area_full.js', CClientScript::POS_END);
			Yii::app()->clientScript->registerScriptFile($baseUrl.'/fileeditor.js', CClientScript::POS_END);
		}else
			throw new Exception(Yii::t('fileeditor - Error: Couldn\'t find assets folder to publish.'));

		$arr = self::phptojs($this->translations);
		$editorwidth = $this->params['editorwidth'];
		$js = "\teditAreaLoader.init({ \n\t\tid : \"$this->feId\"";
		foreach($this->params as $k=>$v)
			$js .=",\n\t\t$k: '$v'";
		$js.="\n\t});";
		$js.= "\n\tfileeditor('$this->reqPost', '$editorwidth', $arr);";
		
		Yii::app()->clientScript->registerScript($this->feId, $js, CClientScript::POS_READY);
	}

	/**
	 * Render fileeditor extension
	 *
	 * @return nothing
	 */
	public function renderFileEditor()
	{
		echo $this->render('feditor',array());
	}

	/**
	 * Create an associative array from files of browseable folders
	 *
	 * @return array
	 */
	public function assocFilesFromDir($dir, $ext="*")
	{
		$files = glob($dir . '*.'.$ext) ? glob($dir . '*.'.$ext) : array();
		$arr = array();
		foreach($files as $file)
			$arr[$file] = str_replace("$dir", "", $file);
		return $arr;
	}
}










