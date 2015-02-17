<?php
/*
Plugin Name: Mmm Simple File List
Plugin URI: http://www.mediamanifesto.com
Description: Plugin to list files in a given directory using this shortcode [MMFileList folder="optional starting from base uploads path" format="li (unordered list) or table (tabular) or img (unordered list of images) or comma (plain text, comma, delimited) types="optional file-extension e.g. pdf,doc" class="optional css class for html list"]
Version: 1.3.1
Author: Adam Bissonnette
Author URI: http://www.mediamanifesto.com
*/

class MM_FileList
{
    public static $attsKeyTemplate = "{%s}";

    function MM_FileList()
    {
        add_shortcode( 'MMFileList', array(&$this, 'ListFiles') );
    }
    
    function copter_remove_crappy_markup( $string )
    {
        $patterns = array(
            '#^\s*</p>#',
            '#<p>\s*$#'
        );

        return preg_replace($patterns, '', $string);
    }

    function ListFiles($atts, $content="")
    {   
        //Strip any empty <p> tags
        //Credit goes to: https://gist.github.com/jlengstorf/5370457
        $content = $this->copter_remove_crappy_markup($content);

        extract( shortcode_atts( array(
        'folder' => '',
        'format' => 'li',
        'types' => '',
        'class' => '',
        'limit' => '-1',
        'orderby' => 'name', //name or date
        'target' => ''
        ), $atts ) );
        
        $folder = $this->_check_for_slashes($folder);

        $baseDir = wp_upload_dir(); //Base Upload Directory
        $dir = $baseDir['path'] . '/' . $folder;
        $outputDir = $baseDir['url'] . '/' . $folder; //ex. http://example.com/wp-content/uploads/2010/05/../../cats
        
        $typesToList = array_filter(explode(",", $types));

        $output = "";

        $files = is_dir($dir);

        if (!$files)
        {
            $output .= sprintf('<div class="mmm-warning">The folder "%s" was not found at: "%s".', $dir, $outputDir);
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
                    
                    if($file != '.' && $file != '..')
                    {
                        if(!is_dir($dir.'/'.$file))
                        {
                            $file = array("name" => $file, "url" => $outputDir . "/" . $file, "size" => $this->human_filesize(filesize($dir . '/' . $file)));
                            
                            //If we are looking for specific types then only list those types, otherwise list everything
                            if (count($typesToList) > 0)
                            {
                                if (in_array($extension, $typesToList))
                                {
                                    array_push($list, $file);
                                }
                            }
                            else
                            {
                                array_push($list, $file);
                            }
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
                    case 'li':
                        $output = $this->_MakeUnorderedList($list, $content, $formatAtts);
                        break;
                    case 'img':
                        $listTemplate = '<ul class="%s">%s</ul>';

                        if ($content == "")
                        {
                            $content = $this->_AddFileAttsToTemplate('<a href="{url}"{target}><img src="{url}" class="{class}" title="{name} ({size})" /></a>', $formatAtts);
                        }

                        $output = $this->_MakeUnorderedList($list, $content, $formatAtts);
                    break;
                    case 'custom':
                        $output = $this->_OutputList($list, $content, $formatAtts);
                    break;
                    case 'table':
                        $output = $this->_MakeTabularLIst($list, $content, $formatAtts);
                    break;
                    case 'comma':
                        $output = $this->_MakeCommaDelimitedList($list);
                    break;
                    default:
                        $output = $this->_MakeUnorderedList($list, $content, $formatAtts);
                    break;
                }
            }
        }
        
        return $output;
    }

    function _AddFileAttsToTemplate($template, $fileAtts)
    {
        $output = $template;

        foreach ($fileAtts as $key => $value) {
            if (isset($value))
            {
                $output = str_replace(sprintf(MM_FileList::$attsKeyTemplate, $key), $value, $output);
            }
        }

        return $output;
    }

    function _OutputList($list, $content, $atts, $wrapper="")
    {
        $listItemTemplate = $content;

        $items = "";

        foreach ($list as $file => $fileatts) //in this case item == filename, value == path
        {
            $items .= $this->_AddFileAttsToTemplate($listItemTemplate, $fileatts);
        }
        
        if ($wrapper != "")
        {
            return sprintf($wrapper, $atts["class"], $items);
        }
        else
        {
            return $items;
        }
    }

    function _MakeCommaDelimitedList($list)
    {
        $formattedList = array();

        foreach ($list as $entry) {
            array_push($formattedList, $entry["url"]);
        }

        return implode(",", $formattedList);
    }

    function _MakeUnorderedList($list, $content, $atts)
    {
        $listTemplate = '<ul class="%s">%s</ul>';
        $listItemTemplate = sprintf('<li>%s</li>', $content);

        if ($content == "")
        {
            $content = '<a href="%s"%s><span class="filename">%s</span><span class="filesize"> (%s)</span></a>';
            $listItemTemplate = sprintf('<li>%s</li>', $content);

            $items = "";
        
            foreach ($list as $file => $fileatts) //in this case item == filename, value == path
            {
                $items .= sprintf($listItemTemplate, $fileatts["url"], $atts["target"], $fileatts["name"], $fileatts["size"]);
            }
            
            return sprintf($listTemplate, $atts["class"], $items);
        }
        else
        {
            return $this->_OutputList($list, $listItemTemplate, $atts, $listTemplate);
        }
    }

    function _MakeTabularList($list, $content, $atts)
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

    //Stolen from comments : http://php.net/manual/en/function.filesize.php thx Arseny Mogilev
    function human_filesize($bytes) {
        $bytes = floatval($bytes);
        $arBytes = array(
            array(
                "UNIT" => "Pb",
                "VALUE" => pow(1024, 5)
            ),
            array(
                "UNIT" => "Tb",
                "VALUE" => pow(1024, 4)
            ),
            array(
                "UNIT" => "Gb",
                "VALUE" => pow(1024, 3)
            ),
            array(
                "UNIT" => "Mb",
                "VALUE" => pow(1024, 2)
            ),
            array(
                "UNIT" => "Kb",
                "VALUE" => 1024
            ),
            array(
                "UNIT" => "Bytes",
                "VALUE" => 1
            ),
        );

        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(",", "." , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
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

    //Remove slashes from the start and end of the path if they exist
    function _check_for_slashes($folder)
    {
        $fixedPath = rtrim ($folder, '/');
        $fixedPath = ltrim ($fixedPath, '/');
        return $fixedPath;
    }

    function _flip_slahes($folder)
    {
        return str_replace("/", "\\", $folder);
    }

} // end class


add_action( 'init', 'MM_FileList_Init', 5 );
function MM_FileList_Init()
{
    global $MM_FileList;
    $MM_FileList = new MM_FileList();
}
?>