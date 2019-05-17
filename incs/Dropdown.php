	<?php
/**
 * Class för att filtrerar dropdown-data
 * 
 * @package  Region Halland
 * @category Varbi
 * @author   Roland Hyden <Roland.Hyden@RegionHalland.se>
 * @link     Roland.hyden@RegionHalland.se
 */
class Dropdown
{
    public function __construct() {
    }
	
    public function prepareDropdownData() {
        
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
        
        //echo "<pre>";
        //print_r($myUnSerializedVarbiFieldContent);
        //echo "<pre>";
        //die();

        //foreach ($myUnSerializedVarbiFieldContent as $data) {
        //    $myID = $data['id'];
        //    $myName = $data['name'];
        //    echo $myID . "<br>";
        //    echo $myName . "<br><br>";
        //    foreach ($myUnSerializedVarbiFieldContent as $data) {
        //}
        die();
    }

}