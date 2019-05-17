	<?php
/**
 * Class för att filtrerar Varbi-data
 * 
 * @package  Region Halland
 * @category Varbi
 * @author   Roland Hyden <Roland.Hyden@RegionHalland.se>
 * @link     Roland.hyden@RegionHalland.se
 */
class Filter
{
    public function __construct() {
    }
	
    public function prepareFilterData() {
        
        // Öppna komplett lista
        $strVarbiListaPath = ENV("VARBI_DATA_PATH") . "varbi_lista.txt";
        $myVarbiListaFile = fopen($strVarbiListaPath, "r");
        $myVarbiListaContent = fread($myVarbiListaFile,filesize($strVarbiListaPath));
        $myUnSerializedVarbiListaContent = unserialize($myVarbiListaContent);

        // Öppna kommun lista
        $strVarbiKommunPath = ENV("VARBI_DATA_PATH") . "dropdown/varbi_kommun_0_0_0.txt";
        $myVarbiKommunFile = fopen($strVarbiKommunPath, "r");
        $myVarbiKommunContent = fread($myVarbiKommunFile,filesize($strVarbiKommunPath));
        $myUnSerializedVarbiKommunContent = unserialize($myVarbiKommunContent);
        
        // Öppna field-lista
        $strVarbiFieldPath = ENV("VARBI_DATA_PATH") . "dropdown/varbi_jobtype_field_0_0_0.txt";
        $myVarbiFieldFile = fopen($strVarbiFieldPath, "r");
        $myVarbiFieldContent = fread($myVarbiFieldFile,filesize($strVarbiFieldPath));
        $myUnSerializedVarbiFieldContent = unserialize($myVarbiFieldContent);
        
        // Öppna field-group-lista
        $strVarbiFieldGroupPath = ENV("VARBI_DATA_PATH") . "dropdown/varbi_jobtype_field_group_0_0_0.txt";
        $myVarbiFieldGroupFile = fopen($strVarbiFieldGroupPath, "r");
        $myVarbiFieldGroupContent = fread($myVarbiFieldGroupFile,filesize($strVarbiFieldGroupPath));
        $myUnSerializedVarbiFieldGroupContent = unserialize($myVarbiFieldGroupContent);
        
        // Kommun, dvs kommun_0_0            
        foreach ($myUnSerializedVarbiKommunContent as $data) {
            $myKommunID = $data['id'];
            $myKommunArray = array();
            foreach ($myUnSerializedVarbiListaContent as $content) {
                $myListaID = $content['id'];
                $myListaTitle = $content['title'];
                $myListaTown = $content['town'];
                $myListaType = $content['type'];
                $myListaWorkinghours = $content['working_hours'];
                $myListaPublished = $content['published'];
                $myListaLastday = $content['lastday'];
                $myListaKommunID = $content['kommun_id'];
                $myListaGroupID = $content['group_id'];
                $myListaFieldID = $content['field_id'];
                if ($myListaKommunID == $myKommunID) {
                    array_push($myKommunArray, array(
                       'id' => $myListaID,
                       'title' => $myListaTitle,
                       'town' => $myListaTown,
                       'type' => $myListaType,
                       'working_hours' => $myListaWorkinghours,
                       'published' => $myListaPublished,
                       'lastday' => $myListaLastday,
                       'kommun_id' => $myListaKommunID,
                       'group_id' => $myListaGroupID,
                       'field_id' => $myListaFieldID
                    ));
                }
            }
            $myFilterKommundPath = ENV("VARBI_DATA_PATH") . "filter/varbi_filter_".$myKommunID."_0_0.txt";
            $myFilterKommunFile = fopen($myFilterKommundPath, "w");
            $mySerializedKommunArray = serialize($myKommunArray);
            fwrite($myFilterKommunFile, $mySerializedKommunArray);
        }
        
        // Kommun + Field, dvs kommun_field_0
        foreach ($myUnSerializedVarbiKommunContent as $data) {
            $myKommunID = $data['id'];
            foreach ($myUnSerializedVarbiFieldContent as $field) {
                $myFieldID = $field['id'];
                $myFieldName = $field['name'];
                $myKommunFieldArray = array();
                foreach ($myUnSerializedVarbiListaContent as $content) {
                    $myListaID = $content['id'];
                    $myListaTitle = $content['title'];
                    $myListaTown = $content['town'];
                    $myListaType = $content['type'];
                    $myListaWorkinghours = $content['working_hours'];
                    $myListaPublished = $content['published'];
                    $myListaLastday = $content['lastday'];
                    $myListaKommunID = $content['kommun_id'];
                    $myListaGroupID = $content['group_id'];
                    $myListaFieldID = $content['field_id'];
                    if ($myListaKommunID == $myKommunID && $myListaFieldID == $myFieldID) {
                        array_push($myKommunFieldArray, array(
                           'id' => $myListaID,
                           'title' => $myListaTitle,
                           'town' => $myListaTown,
                           'type' => $myListaType,
                           'working_hours' => $myListaWorkinghours,
                           'published' => $myListaPublished,
                           'lastday' => $myListaLastday,
                           'kommun_id' => $myListaKommunID,
                           'group_id' => $myListaGroupID,
                           'field_id' => $myListaFieldID
                        ));
                    }
                }
                $myFilterKommunFieldPath = ENV("VARBI_DATA_PATH") . "filter/varbi_filter_".$myKommunID."_".$myFieldID."_0.txt";
                $myFilterKommunFieldFile = fopen($myFilterKommunFieldPath, "w");
                $mySerializedKommunFieldArray = serialize($myKommunFieldArray);
                fwrite($myFilterKommunFieldFile, $mySerializedKommunFieldArray);
            }
        }
        
        // Field, dvs 0_field_0
        foreach ($myUnSerializedVarbiFieldContent as $field) {
            $myFieldID = $field['id'];
            $myFieldName = $field['name'];
            $myFieldArray = array();
            foreach ($myUnSerializedVarbiListaContent as $content) {
                $myListaID = $content['id'];
                $myListaTitle = $content['title'];
                $myListaTown = $content['town'];
                $myListaType = $content['type'];
                $myListaWorkinghours = $content['working_hours'];
                $myListaPublished = $content['published'];
                $myListaLastday = $content['lastday'];
                $myListaKommunID = $content['kommun_id'];
                $myListaGroupID = $content['group_id'];
                $myListaFieldID = $content['field_id'];
                if ($myListaFieldID == $myFieldID) {
                    array_push($myFieldArray, array(
                       'id' => $myListaID,
                       'title' => $myListaTitle,
                       'town' => $myListaTown,
                       'type' => $myListaType,
                       'working_hours' => $myListaWorkinghours,
                       'published' => $myListaPublished,
                       'lastday' => $myListaLastday,
                       'kommun_id' => $myListaKommunID,
                       'group_id' => $myListaGroupID,
                       'field_id' => $myListaFieldID
                    ));
                }
            }
            $myFilterFieldPath = ENV("VARBI_DATA_PATH") . "filter/varbi_filter_0_".$myFieldID."_0.txt";
            $myFilterFieldFile = fopen($myFilterFieldPath, "w");
            $mySerializedFieldArray = serialize($myFieldArray);
            fwrite($myFilterFieldFile, $mySerializedFieldArray);
        }
        
        // Field + Group, dvs 0_field_group
        foreach ($myUnSerializedVarbiFieldGroupContent as $data) {
            $myFieldID = $data['field_id'];
            $myGroupID = $data['group_id'];
            $myIDConcat = $data['id_concat'];
            $myFieldName = $data['field_name'];
            $myGroupName = $data['group_name'];
            $myFieldGroupArray = array();
            foreach ($myUnSerializedVarbiListaContent as $content) {
                $myListaID = $content['id'];
                $myListaTitle = $content['title'];
                $myListaTown = $content['town'];
                $myListaType = $content['type'];
                $myListaWorkinghours = $content['working_hours'];
                $myListaPublished = $content['published'];
                $myListaLastday = $content['lastday'];
                $myListaKommunID = $content['kommun_id'];
                $myListaGroupID = $content['group_id'];
                $myListaFieldID = $content['field_id'];
                if ($myListaFieldID == $myFieldID && $myListaGroupID == $myGroupID) {
                    array_push($myFieldGroupArray, array(
                       'id' => $myListaID,
                       'title' => $myListaTitle,
                       'town' => $myListaTown,
                       'type' => $myListaType,
                       'working_hours' => $myListaWorkinghours,
                       'published' => $myListaPublished,
                       'lastday' => $myListaLastday,
                       'kommun_id' => $myListaKommunID,
                       'group_id' => $myListaGroupID,
                       'field_id' => $myListaFieldID
                    ));
                }
            }
            $myFilterFieldGroupPath = ENV("VARBI_DATA_PATH") . "filter/varbi_filter_0_".$myFieldID."_".$myGroupID.".txt";
            $myFilterFieldGroupFile = fopen($myFilterFieldGroupPath, "w");
            $mySerializedFieldGroupArray = serialize($myFieldGroupArray);
            fwrite($myFilterFieldGroupFile, $mySerializedFieldGroupArray);
        }
        
        // Kommun + Group, dvs kommun_field_group
        foreach ($myUnSerializedVarbiKommunContent as $kommun) {
            $myKommunID = $kommun['id'];
            foreach ($myUnSerializedVarbiFieldGroupContent as $data) {
                $myFieldID = $data['field_id'];
                $myGroupID = $data['group_id'];
                $myIDConcat = $data['id_concat'];
                $myFieldName = $data['field_name'];
                $myGroupName = $data['group_name'];
                $myKommunFieldGroupArray = array();
                foreach ($myUnSerializedVarbiListaContent as $content) {
                    $myListaID = $content['id'];
                    $myListaTitle = $content['title'];
                    $myListaTown = $content['town'];
                    $myListaType = $content['type'];
                    $myListaWorkinghours = $content['working_hours'];
                    $myListaPublished = $content['published'];
                    $myListaLastday = $content['lastday'];
                    $myListaKommunID = $content['kommun_id'];
                    $myListaGroupID = $content['group_id'];
                    $myListaFieldID = $content['field_id'];
                    if ($myListaKommunID == $myKommunID && $myListaFieldID == $myFieldID && $myListaGroupID == $myGroupID) {
                        array_push($myKommunFieldGroupArray, array(
                           'id' => $myListaID,
                           'title' => $myListaTitle,
                           'town' => $myListaTown,
                           'type' => $myListaType,
                           'working_hours' => $myListaWorkinghours,
                           'published' => $myListaPublished,
                           'lastday' => $myListaLastday,
                           'kommun_id' => $myListaKommunID,
                           'group_id' => $myListaGroupID,
                           'field_id' => $myListaFieldID
                        ));
                    }
                }
                $myFilterKommunFieldGroupPath = ENV("VARBI_DATA_PATH") . "filter/varbi_filter_".$myKommunID."_".$myFieldID."_".$myGroupID.".txt";
                $myFilterKommunFieldGroupFile = fopen($myFilterKommunFieldGroupPath, "w");
                $mySerializedFieldKommunGroupArray = serialize($myKommunFieldGroupArray);
                fwrite($myFilterKommunFieldGroupFile, $mySerializedFieldKommunGroupArray);    
            }
        }

        // Fixa

    }

}