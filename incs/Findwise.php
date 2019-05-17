	<?php
/**
 * Class för att indexera Varbi-data
 * 
 * @package  Region Halland
 * @category Varbi
 * @author   Roland Hyden <Roland.Hyden@RegionHalland.se>
 * @link     Roland.hyden@RegionHalland.se
 */
class Findwise
{
    public function __construct() {
    }
	
    public function prepareFindwiseData() {
		
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
        $strVarbiFilePath = ENV("VARBI_DATA_PATH") . "varbi_findwise.txt";
        
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
            $mySaveVarbi['today'] = $today;

            $mySerializedSaveVarbi = serialize($mySaveVarbi);
            
            // Spara biljett i fil
            fwrite($myVarbiFile, $mySerializedSaveVarbi);

            $myPositionLista = array();
            $myPosition = array();
            
            foreach ($arrDecodedData['positions'] as $data) {
                
                // Variabler
                $myID = $data['id'];
                $myContent = $data['name'] . " " . $data['description'] . " " . $data['descs']['footer']; 
                $myTitle = $data['title'];
                $myModified = $data['admission'];
                $myUrl = $data['applyURI'];
            
                // Data för aktuell post
                $doc = array(
                    "content" => $myContent,
                    "title" => $myTitle,
                    "modified" => $myModified,
                    "url" => $myUrl
                );
    
                // Dessa värden kommer från ENV-fil
                $indexServiceUrl = env('FINDWISE_INDEX_SERVICE_URL');
                $collectionName = env('FINDWISE_COLLECTION_NAME_VARBI');
                    
                // Här finns bara publicerade
                $myStatus = 1;

                // Skicka data via curl
                $r = $this->region_halland_varbi_works_postToIndexService(
                    $indexServiceUrl,
                    $collectionName,
                    $myID,
                    $doc,
                    $myStatus
                );
 
            }
            		
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
                $mySaveVarbi['today'] = $today;

                $mySerializedSaveVarbi = serialize($mySaveVarbi);
            
                // Spara biljett i fil
                fwrite($myVarbiFile, $mySerializedSaveVarbi);

                $myPositionLista = array();
                $myPosition = array();
            
                foreach ($arrDecodedData['positions'] as $data) {
                
                    // Variabler
                    $myID = $data['id'];
                    $myContent = $data['name'] . " " . $data['description'] . " " . $data['descs']['footer']; 
                    $myTitle = $data['title'];
                    $myModified = $data['admission'];
                    $myUrl = $data['applyURI'];
            
                    // Data för aktuell post
                    $doc = array(
                        "content" => $myContent,
                        "title" => $myTitle,
                        "modified" => $myModified,
                        "url" => $myUrl
                    );
    
                    // Dessa värden kommer från ENV-fil
                    $indexServiceUrl = env('FINDWISE_INDEX_SERVICE_URL');
                    $collectionName = env('FINDWISE_COLLECTION_NAME_VARBI');
                    
                    // Här finns bara publicerade
                    $myStatus = 1;

                    // Skicka data via curl
                    $r = $this->region_halland_varbi_works_postToIndexService(
                        $indexServiceUrl,
                        $collectionName,
                        $myID,
                        $doc,
                        $myStatus
                    );
 
                }
            
            }

        }

        return "OK!";
	
	}

    // Skicka data via curl
    private function region_halland_varbi_works_postToIndexService($indexServiceUrl, $docCollection, $docId, $docContent, $status) {
        
        // Preparera data
        $doc = $this->region_halland_varbi_works_createDocument($docId, $docContent, $status);
        
        // Full url till curl-service
        $fullUrl = sprintf("%s/rest/v2/collections/%s/documents",
                $indexServiceUrl,
                $docCollection);

        // Initiera curl
        $client = curl_init();

        // Sätt curl options
        curl_setopt($client, CURLOPT_URL, $fullUrl);
        curl_setopt($client, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($client, CURLOPT_POSTFIELDS, $doc);
        curl_setopt($client, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($doc)
        ));

        // Exekvera curl
        $result = curl_exec($client);
        
        // Returnera eventuellt error
        $error = curl_error($client);

        // Stäng curl
        curl_close($client);

        // Om error, returnera detta, annars tomt
        return $error;
    
    }

    // Preparera data
    private function region_halland_varbi_works_createDocument($docId, $content, $status) {
        
        // Temporär array
        $fields = array();
        
        // Omvandla arrayen till en array med name, type och value
        foreach ($content as $name => $value) {
            if ($value) {
                if (is_array($value)) {
                    foreach($value as $val) {
                        $fields[] = array(
                            "name" => $name,
                            "type" => "string",
                            "value" => (string)$val);
                    }
                } else {
                    $fields[] = array(
                        "name" => $name,
                        "type" => "string",
                        "value" => (string)$value);
                }
            }
        }

        // Temporär array
        $doc = array(
            "_id" => "works_" . $docId,
            "status" => $status,
            "fields" => $fields
        );

        // Wrappa temporär array som en array
        $docWrapped = array($doc);
        
        // Json-encoda arrayen
        $docJson = json_encode($docWrapped);
        
        // Returnera den json-encodade arrayen
        return $docJson;

    }

}