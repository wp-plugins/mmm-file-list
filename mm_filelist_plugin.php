<?php
/*
Plugin Name: Mmm Simple File List
Plugin URI: http://www.mediamanifesto.com
Description: Plugin to list files in a given directory using this shortcode [MMFileList folder="optional starting from base uploads path" format="li (html) or comma (txt)"" types="optional file-extension e.g. pdf,doc" class="optional css class for html list"]
Version: 0.6
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
		'types' => '',
        'class' => '',
        'limit' => '-1',
        'orderby' => 'name', //name or date
        'target' => ''
		), $atts ) );
		
		$baseDir = wp_upload_dir(); //Base Upload Directory
		$dir = $baseDir['path'] . $folder;
		$outputDir = $baseDir['url'] . $folder;
		
		$typesToList = explode(",", $types);

        $output = "";

        $files = is_dir($dir);

        if (!$files)
        {
            $output .= sprintf('<div class="mmm-warning">The folder "%s" was not found at: "%s".', $folder, $outputDir);
        }
        else
        {
            $files = scandir($dir);
    		$list = array();

            if ($orderby == "date")
            {
                $files = array_reverse($this->rearrange_files_by_date($dir, $files));
            }

    		foreach($files as $file)
    		{
    			$path_parts = pathinfo($file);

                if (isset($path_parts['extension'])) //check for folders - don't list them
                {
        			$extension = $path_parts['extension'];
        			
        			if($file != '.' && $file != '..' && in_array($extension, $typesToList))
        			{		 
        				if(!is_dir($dir.'/'.$file))
        				{
        					array_push($list, array("name" => $file, "url" => $outputDir . "/" . $file, "size" => $this->human_filesize(filesize($dir . '/' . $file))));
        				}
        			}
                }
    		}
            
            if (is_numeric($limit))
            {
                if ($limit > 0)
                {
                    $list = array_slice($list, 0, $limit);
                }
            }

            if ($target != '')
            {
                $target = 'target="' . $target . '"';
            }

            if (count($list) == 0)
            {
                $output .= sprintf('<div class="mmm-warning">No files (of extension(s): "%s") found in: %s </div>', $types, $outputDir);
            }
            else
            {
                $formatAtts = array("class" => $class, "target" => $target);

                switch($format){
                    case 'table':
                        return $this->_MakeTabularLIst($list, $formatAtts);
                    break;
                	case 'comma':
                	    $output = $this->_MakeCommaDelimitedList($list);
         			break;
                    case 'li':
                    default:
                        return $this->_MakeUnorderedList($list, $formatAtts);
                    break;
                }
            }
        }
        
        return $output;
    }

    function _MakeCommaDelimitedList($list)
    {
        $formattedList = array_map(function($entry) { $listItemTemplate = "%s"; return sprintf($listItemTemplate, $entry["url"]); },$list);

        return implode(",", $formattedList);
    }

	function _MakeUnorderedList($list, $atts)
	{
		//These templates could be set as editable / saveable options
		$listTemplate = '<ul class="%s">%s</ul>';
		$listItemTemplate = '<li><a href="%s"%s><span class="filename">%s</span><span class="filesize"> (%s)</span></a></li>';
		
		$items = "";
		
		foreach ($list as $file => $fileatts) //in this case item == filename, value == path
		{
			$items .= sprintf($listItemTemplate, $fileatts["url"], $atts["target"], $fileatts["name"], $fileatts["size"]);
		}
		
		return sprintf($listTemplate, $atts["class"], $items);
	}

    function _MakeTabularList($list, $atts)
    {
        $listTemplate = '<table class="%s">%s%s</table>';
        $listHeadingTemplate = '<tr><th class="filename">Filename / Link</th><th class="filesize">Size</th></tr>';
        $listItemTemplate = '<tr><td class="filename"><a href="%s"%s>%s</a></td><td class="filesize">%s</td></tr>';

        $items = "";

        foreach ($list as $filename => $fileatts) {
            $items .= sprintf($listItemTemplate, $fileatts["url"], $atts["target"], $fileatts["name"], $fileatts["size"]);
        }

        return sprintf($listTemplate, $atts["class"], $listHeadingTemplate, $items);
    }

    //Stolen from comments : http://php.net/manual/en/function.filesize.php thx Rommelsantor.com
    function human_filesize($bytes, $decimals = 2) {
      $sz = 'BKMGTP';
      $factor = floor((strlen($bytes) - 1) / 3);
      return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    function rearrange_files_by_date($dir, $files)
    {
         $arr = array();
         $i = 0;
         foreach($files as $filename) {
           if ($filename != '.' && $filename != '..') {
             if (filemtime($dir.$filename) === false) return false;
             $dat = date("YmdHis", filemtime($dir.$filename));
             $arr[$dat . "," . $i++] = $filename;
           }
         }
         if (!ksort($arr)) return false;
         return $arr;
    }

} // end class


add_action( 'init', 'MM_FileList_Init', 5 );
function MM_FileList_Init()
{
    global $MM_FileList;
    $MM_FileList = new MM_FileList();
}
?>