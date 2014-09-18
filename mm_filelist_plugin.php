<?php
/*
Plugin Name: Mmm Simple File List
Plugin URI: http://www.mediamanifesto.com
Description: Plugin to list files in a given directory using this shortcode [MMFileList folder="optional starting from basedir" format="li (html) or comma (txt)"" types="optional file-extension e.g. pdf,doc" class="optional css class for html list"]
Version: 0.1
Author: Adam Bissonnette
Author URI: http://www.mediamanifesto.com
*/

class MM_FileList
{
	function MM_FileList()
	{
        add_shortcode( 'MMFileList', array(&$this, 'ListFiles') );
	}
	
	function ListFiles($atts)
	{	
		extract( shortcode_atts( array(
		'folder' => '',
		'format' => 'li',
		'types' => 'pdf,doc',
        'class' => ''
		), $atts ) );
		
		$baseDir = wp_upload_dir();
		$dir = $baseDir['path'] . $folder;
		$outputDir = $baseDir['url'] . $folder;
		
		$typesToList = explode(",", $types);
		$files = scandir($dir);
		$list = array();
		
		foreach($files as $file)
		{
			$path_parts = pathinfo($file);
			$extension = $path_parts['extension'];
			
			if($file != '.' && $file != '..' && in_array($extension, $typesToList))
			{		 
				if(!is_dir($dir.'/'.$file))
				{
					$list[$file] = $outputDir . '/' . $file;
				} 
			}
		}
        
        $output = "";
        
        switch($format){
        	case 'li':
        		return $this->_MakeHtmlList($list, $class);
        	break;
        	case 'comma':
        	default:
        		$output = implode(",", $list);
 			break;
        }
        
        return $output;
    }

	function _MakeHtmlList($list, $class)
	{
		//These templates could be set as editable / saveable options
		$listTemplate = '<ul class="' . $class . '">%s</ul>';
		$listItemTemplate = '<li><a href="%s">%s</a></li>';
		
		$items = "";
		
		foreach ($list as $item => $value) //in this case item == filename, value == path
		{
			$items .= sprintf($listItemTemplate, $value, $item);
		}
		
		return sprintf($listTemplate, $items);
	}

} // end class


add_action( 'init', 'MM_FileList_Init', 5 );
function MM_FileList_Init()
{
    global $MM_FileList;
    $MM_FileList = new MM_FileList();
}
?>