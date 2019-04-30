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
        
        // Idag
        $today = date("Y-m-d");
		
        // Url till Varbi komplett array
        $strVarbiFilePath = ENV("VARBI_DATA_PATH") . "varbi_komplett.txt";
        
        // Kolla om fil finns
        $varbiFleExist = file_exists($strVarbiFilePath);
        

        if (!$varbiFleExist){
        	
            // Hämta data från Varbi		
            $arrData = file_get_contents($myPath);

            // Decoda data till rätt json-format
            $arrDecodedData = json_decode($arrData, true);

            // Skapa fil
            $myVarbiFile = fopen($strVarbiFilePath, "w");

            // Skapa en ny array med token & expire
            $mySaveVarbi = array();
            $mySaveVarbi['data'] = $arrDecodedData;
            $mySaveVarbi['today'] = $today;

            $mySerializedSaveVarbi = serialize($mySaveVarbi);
            
            // Spara biljett i fil
            fwrite($myVarbiFile, $mySerializedSaveVarbi);

            $myPositionLista = array();
            $myPosition = array();
            
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
                
                $myPositionEnskildPath = ENV("VARBI_DATA_PATH") . "varbi_". $data['id'] .".txt";
                $myPositionEnskildFile = fopen($myPositionEnskildPath, "w");
                $mySerializedPositionEnskild = serialize($myPosition);
                fwrite($myPositionEnskildFile, $mySerializedPositionEnskild);
                
                array_push($myPositionLista, array(
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'title' => $data['title'],
                    'town' => $data['town'],
                    'country' => $data['country'],
                    'published' => $data['published'],
                    'lastday' => $data['ends'],
                    'ref_no' => $data['ref_nr'],
                    'hours' => $data['hours'],
                    'type' => $data['type'],
                    'working_hours' => $data['working_hours'],
                    'applyURI' => $data['applyURI']
                ));

            }
            
            $myPositionListaPath = ENV("VARBI_DATA_PATH") . "varbi_lista.txt";
            $myPositionListaFile = fopen($myPositionListaPath, "w");
            $mySerializedPositionLista = serialize($myPositionLista);
            fwrite($myPositionListaFile, $mySerializedPositionLista);
		
        } else {
        	
            // Öppna filen
            $myVarbiFile = fopen($strVarbiFilePath, "r");

            // Läs innehåll
            $myVarbiFileContent = fread($myVarbiFile,filesize($strVarbiFilePath));

            // Unserializera filens innehåll
            $mySerializedVarbiFileContent = unserialize($myVarbiFileContent);

            if ($today != $mySerializedVarbiFileContent['today']) {

                // Hämta data från Varbi        
                $arrData = file_get_contents($myPath);

                // Decoda data till rätt json-format
                $arrDecodedData = json_decode($arrData, true);

                // Skapa fil
                $myVarbiFile = fopen($strVarbiFilePath, "w");

                // Skapa en ny array med token & expire
                $mySaveVarbi = array();
                $mySaveVarbi['data'] = $arrDecodedData;
                $mySaveVarbi['today'] = $today;

                $mySerializedSaveVarbi = serialize($mySaveVarbi);
            
                // Spara biljett i fil
                fwrite($myVarbiFile, $mySerializedSaveVarbi);

                $myPositionLista = array();
                $myPosition = array();
            
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
                
                    $myPositionEnskildPath = ENV("VARBI_DATA_PATH") . "varbi_". $data['id'] .".txt";
                    $myPositionEnskildFile = fopen($myPositionEnskildPath, "w");
                    $mySerializedPositionEnskild = serialize($myPosition);
                    fwrite($myPositionEnskildFile, $mySerializedPositionEnskild);
                
                    array_push($myPositionLista, array(
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'title' => $data['title'],
                        'town' => $data['town'],
                        'country' => $data['country'],
                        'published' => $data['published'],
                        'lastday' => $data['ends'],
                        'ref_no' => $data['ref_nr'],
                        'hours' => $data['hours'],
                        'type' => $data['type'],
                        'working_hours' => $data['working_hours'],
                        'applyURI' => $data['applyURI']
                    ));

                }
            
                $myPositionListaPath = ENV("VARBI_DATA_PATH") . "varbi_lista.txt";
                $myPositionListaFile = fopen($myPositionListaPath, "w");
                $mySerializedPositionLista = serialize($myPositionLista);
                fwrite($myPositionListaFile, $mySerializedPositionLista);

            }

        }

        return "OK!";
	
	}

}