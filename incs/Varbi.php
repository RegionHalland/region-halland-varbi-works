	<?php
/**
 * Class för att hantera Varbi-data
 * 
 * @package  Region Halland
 * @category Varbi
 * @author   Roland Hyden <Roland.Hyden@RegionHalland.se>
 * @link     Roland.hyden@RegionHalland.se
 */
class Varbi
{
    public function __construct() {
    }
	
    public function prepareVarbiData() {
		
        // Variabler för url till Varbi
        $myFeed = env('VARBI_PROD_FEED');
        $myToken = env('VARBI_TOKEN');
        
        // Jobb
        $myPath = $myFeed; 
        
        // Jobb + interna jobb
        //$myPath = $myFeed . "?token=" . $myToken;
        
        // Hämta data från Varbi        
        $arrData = file_get_contents($myPath);

        // Decoda data till rätt json-format
        $arrDecodedData = json_decode($arrData, true);
        
        //echo "<pre>";
        //print_r($arrDecodedData);
        //echo "<pre>";
        //die();

        $myPositionLista = array();
        $myPosition = array();
        $myPositionMunicipality = array();
        $myPositionJobtypeName = array();
        $myPositionJobtypeGroup = array();
        $myPositionJobtypeField = array();
        $myPositionJobtypeFieldGroup = array();
        $myMunicipalityString = "";
        $myMunicipalityCheckString = "x";
        $myJobtypeNameString = "";
        $myJobtypeNameCheckString = "x";
        $myJobtypeFieldString = "";
        $myJobtypeFieldCheckString = "x";
        $myJobtypeGroupString = "";
        $myJobtypeGroupCheckString = "x";
        $myJobtypeFieldGroupString = "";
        $myJobtypeFieldGroupCheckString = "x";

        foreach ($arrDecodedData['positions'] as $data) {
            
            // Data för varje enskilt jobb
            $myPosition['id'] = $data['id'];    
            $myPosition['name'] = $data['name'];    
            $myPosition['title'] = $data['title'];  
            $myPosition['town'] = $data['town'];    
            $myPosition['country'] = $data['country'];  
            $myPosition['published'] = $data['published'];  
            $myPosition['lastday'] = $data['ends']; 
            $myPosition['ref_no'] = $data['ref_nr'];    
            $myPosition['hours'] = $data['hours'];  
            $myPosition['type'] = $data['type'];    
            $myPosition['description'] = $data['description'];  
            $myPosition['footer'] = $data['descs']['footer'];  
            $myPosition['working_hours'] = $data['working_hours'];
            $myPosition['applyURI'] = $data['applyURI'];
            $myPosition['admission'] = $data['admission'];
            $myPosition['pay'] = $data['pay'];
            $myPosition['nr_pos'] = $data['nr_pos'];
            $myPosition['county'] = $data['county'];
            $myPosition['kontakt'] = $data['position_contact'];
            $myPosition['facket'] = $data['union_representative'];
            //$myPosition['kontakt_namn'] = $data['position_contact']['0']['0'];
            //$myPosition['kontakt_telefon'] = $data['position_contact']['0']['2'];
            //$myPosition['facket_namn'] = $data['union_representative']['0']['0'];
            //$myPosition['facket_telefon'] = $data['union_representative']['0']['2'];
            
            $myPositionEnskildPath = ENV("VARBI_DATA_PATH") . "varbi_". $data['id'] .".txt";
            $myPositionEnskildFile = fopen($myPositionEnskildPath, "w");
            $mySerializedPositionEnskild = serialize($myPosition);
            fwrite($myPositionEnskildFile, $mySerializedPositionEnskild);
            
            array_push($myPositionLista, array(
                'id' => $data['id'],
                'title' => $data['title'],
                'town' => $data['town'],
                'type' => $data['type'],
                'working_hours' => $data['working_hours'],
                'published' => $data['published'],
                'lastday' => $data['ends'],
                'kommun_id' => $data['codes']['municipality'],
                'group_id' => $data['jobtype']['groupid'],
                'group_name' => $data['jobtype']['group'],
                'field_id' => $data['jobtype']['fieldid'],
                'field_name' => $data['jobtype']['field']
            ));

            // Skapa array för kommuner med ID
            $myMunicipalityID = $data['codes']['municipality'];
            $myMunicipalityName = $this->region_halland_varbi_works_get_municipality_name($myMunicipalityID);
            $myMunicipalityCheck = "," . $myMunicipalityID . ",";
            $myMunicipalityPos = strpos($myMunicipalityCheckString,$myMunicipalityCheck);
            if (is_numeric($myMunicipalityPos) == 1) {
                $myMunicipalityUnique = 0;
            } else {
                $myMunicipalityUnique = 1;
            }
            if ($myMunicipalityUnique == 1) {
                array_push($myPositionMunicipality, array(
                    'id' => $myMunicipalityID,
                    'name' => $myMunicipalityName
                ));
            }
            $myMunicipalityString = $myMunicipalityString . "," . $myMunicipalityID;
            $myMunicipalityCheckString = $myMunicipalityString . ",";
                        
            // Skapa array för jobtype field med ID
            $myJobtypeFieldID = $data['jobtype']['fieldid'];
            $myJobtypeField = $data['jobtype']['field'];
            $myJobtypeFieldCheck = "," . $myJobtypeFieldID . ",";
            $myJobtypeFieldPos = strpos($myJobtypeFieldCheckString,$myJobtypeFieldCheck);
            if (is_numeric($myJobtypeFieldPos) == 1) {
                $myJobtypeFieldUnique = 0;
            } else {
                $myJobtypeFieldUnique = 1;
            }
            if ($myJobtypeFieldUnique == 1) {
                array_push($myPositionJobtypeField, array(
                    'id' => $myJobtypeFieldID,
                    'name' => $myJobtypeField
                ));
            }
            $myJobtypeFieldString = $myJobtypeFieldString . "," . $myJobtypeFieldID;
            $myJobtypeFieldCheckString = $myJobtypeFieldString . ",";

            // Skapa array för jobtype group med ID
            $myJobtypeGroupID = $data['jobtype']['groupid'];
            $myJobtypeGroup = $data['jobtype']['group'];
            $myJobtypeGroupCheck = "," . $myJobtypeGroupID . ",";
            $myJobtypeGroupPos = strpos($myJobtypeGroupCheckString,$myJobtypeGroupCheck);
            if (is_numeric($myJobtypeGroupPos) == 1) {
                $myJobtypeGroupUnique = 0;
            } else {
                $myJobtypeGroupUnique = 1;
            }
            if ($myJobtypeGroupUnique == 1) {
                array_push($myPositionJobtypeGroup, array(
                    'id' => $myJobtypeGroupID,
                    'name' => $myJobtypeGroup
                ));
            }
            $myJobtypeGroupString = $myJobtypeGroupString . "," . $myJobtypeGroupID;
            $myJobtypeGroupCheckString = $myJobtypeGroupString . ",";
            
            // Skapa array för jobtype field-group med ID
            $myJobtypeFieldIDConcat = $data['jobtype']['fieldid'];
            $myJobtypeFieldConcat = $data['jobtype']['field'];
            $myJobtypeGroupIDConcat = $data['jobtype']['groupid'];
            $myJobtypeGroupConcat = $data['jobtype']['group'];
            $myJobtypeFieldGroupCheck = "," . $myJobtypeFieldIDConcat . "-" . $myJobtypeGroupIDConcat . ",";
            $myJobtypeFielGroupdPos = strpos($myJobtypeFieldGroupCheckString,$myJobtypeFieldGroupCheck);
            if (is_numeric($myJobtypeFielGroupdPos) == 1) {
                $myJobtypeFieldGroupUnique = 0;
            } else {
                $myJobtypeFieldGroupUnique = 1;
            }
            if ($myJobtypeFieldGroupUnique == 1) {
                array_push($myPositionJobtypeFieldGroup, array(
                    'field_id' => $myJobtypeFieldIDConcat,
                    'group_id' => $myJobtypeGroupIDConcat,
                    'id_concat' => $myJobtypeFieldIDConcat . "-" . $myJobtypeGroupIDConcat,
                    'field_name' => $myJobtypeFieldConcat,
                    'group_name' => $myJobtypeGroupConcat                        
                ));
            }
            $myJobtypeFieldGroupString = $myJobtypeFieldGroupString . "," . $myJobtypeFieldIDConcat . "-" . $myJobtypeGroupIDConcat;
            $myJobtypeFieldGroupCheckString = $myJobtypeFieldGroupString . ",";

        }

        // Spara array för kommuner med ID som en serialiserad array
        $myPositionMunicipalityPath = ENV("VARBI_DATA_PATH") . "dropdown/varbi_kommun_0_0_0.txt";
        $myPositionMunicipalityFile = fopen($myPositionMunicipalityPath, "w");
        $mySerializedPositionMunicipality = serialize($myPositionMunicipality);
        fwrite($myPositionMunicipalityFile, $mySerializedPositionMunicipality);
        
        // Spara array för jobtype field med ID som en serialiserad array
        $myPositionJobtypeFieldPath = ENV("VARBI_DATA_PATH") . "dropdown/varbi_jobtype_field_0_0_0.txt";
        $myPositionJobtypeFieldFile = fopen($myPositionJobtypeFieldPath, "w");
        $mySerializedPositionJobtypeField = serialize($myPositionJobtypeField);
        fwrite($myPositionJobtypeFieldFile, $mySerializedPositionJobtypeField);

        // Spara array för jobtype group med ID som en serialiserad array
        $myPositionJobtypeGroupPath = ENV("VARBI_DATA_PATH") . "dropdown/varbi_jobtype_group_0_0_0.txt";
        $myPositionJobtypeGroupFile = fopen($myPositionJobtypeGroupPath, "w");
        $mySerializedPositionJobtypeGroup = serialize($myPositionJobtypeGroup);
        fwrite($myPositionJobtypeGroupFile, $mySerializedPositionJobtypeGroup);

        // Spara array för jobtype field med ID som en serialiserad array
        $myPositionJobtypeFieldGroupPath = ENV("VARBI_DATA_PATH") . "dropdown/varbi_jobtype_field_group_0_0_0.txt";
        $myPositionJobtypeFieldGroupFile = fopen($myPositionJobtypeFieldGroupPath, "w");
        $mySerializedPositionJobtypeFieldGroup = serialize($myPositionJobtypeFieldGroup);
        fwrite($myPositionJobtypeFieldGroupFile, $mySerializedPositionJobtypeFieldGroup);

        // Sortera om arrayen efter senast publicerad
        usort($myPositionLista, 'static::region_halland_varbi_sort_by_published');
        
        // Spara listan
        $myPositionListaPath = ENV("VARBI_DATA_PATH") . "varbi_lista.txt";
        $myPositionListaFile = fopen($myPositionListaPath, "w");
        $mySerializedPositionLista = serialize($myPositionLista);
        fwrite($myPositionListaFile, $mySerializedPositionLista);
    
        // Byta statts för att skapa om filter-listorna
        $myFilterVarbi = array();
        $myFilterVarbi['update'] = 1;
        $myPositionFilterPath = ENV("VARBI_DATA_PATH") . "varbi_filter.txt";
        $myPositionFilterFile = fopen($myPositionFilterPath, "w");
        $mySerializedPositionFilter = serialize($myFilterVarbi);
        fwrite($myPositionFilterFile, $mySerializedPositionFilter);

        return "OK!";
	
	}

    public static function region_halland_varbi_sort_by_published($a, $b) {
        return strcmp($b['published'],$a['published']);
    }

    // Första bokstaven som nummer
    private function region_halland_varbi_works_get_municipality_name($id) {
        switch ($id) {
             case '1279':
                 $strKommunName = "Båstad";
                 break;
             case '1315':
                 $strKommunName = "Hylte";
                 break;
             case '1380':
                 $strKommunName = "Halmstad";
                 break;
             case '1381':
                 $strKommunName = "Laholm";
                 break;
             case '1382':
                 $strKommunName = "Falkenberg";
                 break;
             case '1383':
                 $strKommunName = "Varberg";
                 break;
             case '1384':
                 $strKommunName = "Kungsbacka";
                 break;
             default:
                 $strKommunName = "N/A";
         }

         // Returnera namn på kommun
         return $strKommunName;
    }

}