<?php 

$device = $_GET['device'];

switch($device){
    case 'air-m2-15': $device2 = 'MacBook Air M2 15-inch (2023)'; break;
    case 'air-m2-2022': $device2 = 'MacBook Air M2 13-inch (2022)'; break;
    case 'air-m1-2020': $device2 = 'MacBook Air M1'; break;
    case 'air-2020': $device2 = 'MacBook Air (2020)'; break;
    case 'air-2019': $device2 = 'MacBook Air (2019)'; break;
    case 'air-2018': $device2 = 'MacBook Air (2018)'; break;
    case 'air-2017': $device2 = 'MacBook Air (2017)'; break;
    case 'air-13-2015': $device2 = 'MacBook Air 13-inch (2015)'; break;
    case 'air-11-2015': $device2 = 'MacBook Air 11-inch (2015)'; break;
    case 'air-13-2014': $device2 = 'MacBook Air 13-inch (2014)'; break;
    case 'air-11-2014': $device2 = 'MacBook Air 11-inch (2014)'; break;
    case 'air-13-2013': $device2 = 'MacBook Air 13-inch (2013)'; break;
    case 'air-11-2013': $device2 = 'MacBook Air 11-inch (2013)'; break;
    case 'pro-16-2023': $device2 = 'MacBook Pro 16-inch (2023)'; break;
    case 'pro-14-2023': $device2 = 'MacBook Pro 14-inch (2023)'; break;
    case 'pro-13-m2': $device2 = 'MacBook Pro M2 13-inch'; break;
    case 'pro-16-2021': $device2 = 'MacBook Pro 16-inch (2021)'; break;
    case 'pro-14-2021': $device2 = 'MacBook Pro 14-inch (2021)'; break;
    case 'pro-13-m1': $device2 = 'MacBook Pro M1 13-inch'; break;
    case 'pro-13-2020-4tbt': $device2 = 'MacBook Pro 13-inch (2020, 4TBT)'; break;
    case 'pro-13-2020-2tbt': $device2 = 'MacBook Pro 13-inch (2020, 2TBT)'; break;
    case 'pro-16-2019': $device2 = 'MacBook Pro 16-inch (2019)'; break;
    case 'pro-13-2019-2tbt': $device2 = 'MacBook Pro 13-inch (2019, 2TBT)'; break;
    case 'pro-15-2019': $device2 = 'MacBook Pro 15-inch (2019)'; break;
    case 'pro-13-2019-4tbt': $device2 = 'MacBook Pro 13-inch (2019, 4TBT)'; break;
    case 'pro-15-2018': $device2 = 'MacBook Pro 15-inch (2018)'; break;
    case 'pro-13-2018': $device2 = 'MacBook Pro 13-inch (2018)'; break;
    case 'pro-15-2017': $device2 = 'MacBook Pro 15-inch (2017)'; break;
    case 'pro-13-2017-4tbt': $device2 = 'MacBook Pro 13-inch (2017, 4TBT)'; break;
    case 'pro-13-2017-2tbt': $device2 = 'MacBook Pro 13-inch (2017, 2TBT)'; break;
    case 'pro-15-2016': $device2 = 'MacBook Pro 15-inch (2016)'; break;
    case 'pro-13-2016-4tbt': $device2 = 'MacBook Pro 13-inch (2016, 4TBT)'; break;
    case 'pro-13-2016-2tbt': $device2 = 'MacBook Pro 13-inch (2016, 2TBT)'; break;
    case 'pro-15-2015': $device2 = 'MacBook Pro 15-inch (2015)'; break;
    case 'pro-13-2015': $device2 = 'MacBook Pro 13-inch (2015)'; break;
    case 'pro-15-2014': $device2 = 'MacBook Pro 15-inch (2014)'; break;
    case 'pro-13-2014': $device2 = 'MacBook Pro 13-inch (2014)'; break;
    case 'pro-15-late2013': $device2 = 'MacBook Pro 15-inch (Late 2013)'; break;
    case 'pro-13-late2013': $device2 = 'MacBook Pro 13-inch (Late 2013)'; break;
    case 'pro-15-early2013': $device2 = 'MacBook Pro 15-inch (Early 2013)'; break;
    case 'pro-13-early2013': $device2 = 'MacBook Pro 13-inch (Early 2013)'; break;
    case '12-2015': $device2 = 'MacBook (12-inch, 2015)'; break;
    case '12-2016': $device2 = 'MacBook (12-inch, 2016)'; break;
    case '12-2017': $device2 = 'MacBook (12-inch, 2017)'; break;


    default : header("Location: servicepreise_start.php");
    
}



?>

<div class="container">

<div class="row mt-4">

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">

    <li class="breadcrumb-item"><a href="servicepreise_start.php">Servicepreise</a></li>
    <li class="breadcrumb-item"><a href="servicepreise_device.php?device=mac">Gerät auswählen</a></li>
    <li class="breadcrumb-item active" aria-current="page">Mac</li>
  </ol>
</nav>


    </div>

<div class="row mt-4">
<div class="col-md-4 text-center">
    <h3>Ausgewähltes Gerät:</h3>
</div>
<div class="col-md-4 text-center">
    <h3><?php echo $device2; ?></h3>
</div>

<div class="col-md-2 text-center">
    <a href="servicepreise_device.php?device=mac"><button type="button" class="btn btn-secondary">Gerät ändern</button></a>
</div>
<div class="col-md-2 text-center">
    <a href="servicepreise_start.php"><button type="button" class="btn btn-dark">Kategorie ändern</button></a>
</div>
</div>

<div class="row mt-4">


<div class="col-md-4">
    </div>
    
<div class="col-md-4">
</div>

<div class="col-md-4">
        </div>
    
</div>
<?php


if(!empty($_POST['teil'])) {

    switch($_POST['teil']){
    case 'akku' : $teillang = 'Akku'; break;
    case 'display' : $teillang = 'Display'; break;
    case 'mlb' : $teillang = 'Logic Board'; break;
    case 'topcase' : $teillang = 'Top Case'; break;
    
    default: $teillang = 'Kein Ersatzteil ausgewählt';
} 

    
        $echo1 = '
        
        
                    <div class="row mt-4">
                <div class="col-md-4 text-center">
                    <h4>Ausgewähltes Ersatzteil:</h4>
                </div>
                <div class="col-md-4 text-center">
                    <h4>';
                    echo $echo1; echo $teillang; $echo2 = '</h4>
                </div>
    
                
                <div class="col-md-4 text-center">
                    <a href=""><button type="button" class="btn btn-dark">Ersatzteil zurücksetzen</button></a>
                </div>
                </div>
        
        
        
        
        
            
        ';
    
        $echo3 = '
            <form method="POST" action="">
            <div class="row text-center">
                
                
        
                </div>
            
            ';
    
            $echo5 ='<input type="hidden" name="device" id="device" value="';
            
            $echo6 ='">
            <input type="hidden" name="hiddenteil" id="hiddenteil" value="';
            $echo7 ='" </div> </div>';
            
    
    
            
            $echo10 ='</h6>
                                </div>

                            </div>
                        </div>
                        </div>
            </div>
    
            <div class="col-md-2">
            </div>
    
    
        </div>
    
        </div>
        </form>
        ';
     
    
    
            
            echo $echo2;
    
            
    
                echo $echo3;
    
            
    
            echo $echo5;
            echo $device;
            echo $echo6;
            echo $_POST['teil'];
            echo $echo7;
    
            
    
           
         
    /* ********** PREIS QUERY *********** */
   
    $tabelle = $_POST['teil'];
    $modell = $device;

    $query = "
        SELECT vk
        FROM $tabelle
        WHERE modell = '".$modell."'
    ";

    $dataset = array();
	$sqlresult = mysqli_query($connect, $query);
	if (!empty($sqlresult))
		while ($row = mysqli_fetch_array($sqlresult)) {
			$dataset[] = $row;
		}

            foreach($dataset as $data):
			
            $vp = $data['vk'];
                    
            endforeach;
                    
                  
    
             


    


    
    if ($vp > 400) {
        $repbon = $vp-200;
    } else $repbon = ceil($vp/2);
   
    ?>

    <div class="row mt-4 text-center">
        <div class="col-md-1">
        </div>
        <div class="col-md-5 mt-4 border border-primary border-5 rounded-pill">
            <h2>Verkaufspreis: € <?php echo $vp; ?>,-</h2>
        </div>
        <div class="col-md-5 mt-4 border border-success border-5 rounded-pill">
            <h2>Preis mit Reparaturbonus: € <?php echo $repbon; ?>,-</h2>
        </div>
        <div class="col-md-1">
        </div>
</div>
    
    

<?php
    

   
    
    
    echo $echo10;
    


} else {
    
    
    $echo = <<<'HTML'

    <form method="POST" action="">

    <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
      
    <select name="teil" id="teil" class="form-select" aria-label="Default select example">
      <option value="" selected>Ersatzteil wählen</option>
      
      
    HTML;

    echo $echo;



                    /* AKKU */
                
                $query = "
                SELECT *
                FROM akku
                WHERE modell = '$device'
            ";

            $array = array();
                $sqlresult = mysqli_query($connect, $query);
                if (!empty($sqlresult))
                    while ($row = mysqli_fetch_array($sqlresult)) {
                        $array[] = $row;
                    }

                    if(!empty($array)) {
                        echo '<option value="akku">Akku</option>';
                    }
    
    
                    /* DISPLAY */
    
        $query = "
        SELECT *
        FROM display
        WHERE modell = '$device'
    ";
    
    $array = array();
        $sqlresult = mysqli_query($connect, $query);
        if (!empty($sqlresult))
            while ($row = mysqli_fetch_array($sqlresult)) {
                $array[] = $row;
            }
    
            if(!empty($array)) {
                echo '<option value="display">Display</option>';
            }
    
    
     /* Logic Board */
    
     $query = "
     SELECT *
     FROM mlb
     WHERE modell = '$device'
    ";
    
    $array = array();
     $sqlresult = mysqli_query($connect, $query);
     if (!empty($sqlresult))
         while ($row = mysqli_fetch_array($sqlresult)) {
             $array[] = $row;
         }
    
         if(!empty($array)) {
             echo '<option value="mlb">Logic Board</option>';
         }
    
    
          /* Top Case */
    
     $query = "
     SELECT *
     FROM topcase
     WHERE modell = '$device'
    ";
    
    $array = array();
     $sqlresult = mysqli_query($connect, $query);
     if (!empty($sqlresult))
         while ($row = mysqli_fetch_array($sqlresult)) {
             $array[] = $row;
         }
    
         if(!empty($array)) {
             echo '<option value="topcase">Top Case</option>';
         }
    
    
         
    ?>
    
    
    
    
    
    <?php 
    
    $echo = <<<'HTML'
    
    
    </select>
        </div>
    
    
        <div class="col-md-4">
        </div>
    
    
        </div>
    
        <div class="row text-center mt-4">
    
            <div class="col-md-4">
            </div>
    
            <div class="col-md-4">
            <button type="submit" class="btn btn-success">Berechnen</button>
            </div>
    
            <div class="col-md-4">
            </div>
    
        </div>
    
    
    
        </form>
    
        
        </div>
    </div>


    HTML;

    echo $echo;
}

?>


