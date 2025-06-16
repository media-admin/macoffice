<?php

if(!empty ($_GET['cat'])){
$selected = $_GET['cat'];
    if(!empty ($_GET['cat2'])){
        $selected2 = $_GET['cat2'];
            if(!empty ($_GET['cat3'])){
            $selected3 = $_GET['cat3'];
            }
    }
}

if(!empty ($_GET['device'])){
    $selecteddev = $_GET['device'];
}

if(!empty($_GET['repair'])){
    $selectedrep = $_GET['repair'];
}

$query = "
    SELECT *
    FROM categories
    WHERE typ = '1'
";

$categories1 = array();
	$sqlresult = mysqli_query($connect, $query);
	if (!empty($sqlresult))
		while ($row = mysqli_fetch_array($sqlresult)) {
			$categories1[] = $row;
		}


        $query = "
        SELECT *
        FROM categories
        WHERE typ = '2'
        ORDER BY id DESC
    ";

    $categories2 = array();
        $sqlresult = mysqli_query($connect, $query);
        if (!empty($sqlresult))
            while ($row = mysqli_fetch_array($sqlresult)) {
                $categories2[] = $row;
            }




            $query = "
            SELECT *
            FROM categories
            WHERE typ = '3'
            ORDER BY id DESC
        ";

        $categories3 = array();
            $sqlresult = mysqli_query($connect, $query);
            if (!empty($sqlresult))
                while ($row = mysqli_fetch_array($sqlresult)) {
                    $categories3[] = $row;
                }

        if(!empty($selecteddev)){
            $query = "
                SELECT *
                FROM repairs
                WHERE device_id = $selecteddev
            ";

            $repairdata = array();
            $sqlresult = mysqli_query($connect, $query);
            if (!empty($sqlresult))
                while ($row = mysqli_fetch_array($sqlresult)) {
                    $repairdata[] = $row;
                }

            }

?>

<div class="container">
<div class="row mt-3">
<div class="col-md-8">
<h1>macoffice Servicepreise für Web</h1>



    </div>

    <div class="col-md-4 text-end">
    <a href="import.php"><button type="button" class="btn btn-dark">Importieren</button></a>
    </div>
</div>






                <div class="row text-center justify-content-center mt-3 mb-3">
                <?php
                    foreach($categories1 as $cat1):?>
                        <div class="col-md-2 p-2">
                            <a href="quickprice.php?cat=<?php echo $cat1['id']; ?>#jump1"><button class="<?php if(!empty($selected)){ if(($selected == $cat1['id'])){ echo 'selectedbutton';} else { echo 'notselected';} } else {echo 'livebutton';} ?>"><?php echo $cat1['name']; ?></button></a>
                        </div>

                    <?php
                        if(!empty($selected)){


                        $query = "
                        SELECT *
                        FROM devices
                        WHERE kat_id = $selected
                        ORDER BY id DESC
                        ";

                        $devices = array();
                            $sqlresult = mysqli_query($connect, $query);
                            if (!empty($sqlresult))
                                while ($row = mysqli_fetch_array($sqlresult)) {
                                    $devices[] = $row;
                                }

                            }

                    endforeach;


                ?>
                </div>

                <div class="row text-center justify-content-center mt-5" id="jump1">
                <?php

                foreach($categories2 as $cat2):
                    if(!empty($selected)){
                        if($selected == $cat2['parent']){
                        ?>
                                <div class="col-md-2 p-2">
                                    <a href="quickprice.php?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $cat2['id']; ?>#jump2"><button class="<?php if(!empty($selected2)){ if(($selected2 == $cat2['id'])){ echo 'selectedbutton';} else { echo 'notselected';} } else {echo 'livebutton';}  ?>"><?php echo $cat2['name']; ?></button></a>
                                </div>

                            <?php
                                    if(!empty($selected2)){
                                        $query = "
                                        SELECT *
                                        FROM devices
                                        WHERE kat_id = $selected2
                                        ORDER BY id DESC
                                        ";

                                        $devices2 = array();
                                            $sqlresult = mysqli_query($connect, $query);
                                            if (!empty($sqlresult))
                                                while ($row = mysqli_fetch_array($sqlresult)) {
                                                    $devices2[] = $row;
                                                }
                                            }
                                }
                                    }
                            endforeach;
                        ?>

                </div>

                <div class="row text-center justify-content-center mt-5" id="jump2">
                <?php

                foreach($categories3 as $cat3):
                    if(!empty($selected2)){
                        if($selected2 == $cat3['parent']){
                        ?>
                                <div class="col-md-2 p-2">
                                    <a href="quickprice.php?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $selected2; ?>&amp;cat3=<?php echo $cat3['id']; ?>#jump3"><button class="<?php if(!empty($selected3)){ if(($selected3 == $cat3['id'])){ echo 'selectedbutton';} else {echo 'notselected';} } else { echo 'livebutton';} ?>"><?php echo $cat3['name']; ?></button></a>
                                </div>

                            <?php
                                                if(!empty($selected3)){
                                                    $query = "
                                                    SELECT *
                                                    FROM devices
                                                    WHERE kat_id = $selected3
                                                    ORDER BY id DESC
                                                    ";

                                                    $devices3 = array();
                                                        $sqlresult = mysqli_query($connect, $query);
                                                        if (!empty($sqlresult))
                                                            while ($row = mysqli_fetch_array($sqlresult)) {
                                                                $devices3[] = $row;
                                                            }
                                                        }
                                }
                                    }
                            endforeach;
                        ?>

                </div>




                <div class="row text-center justify-content-center mt-3" id="jump3">
                    <?php


                            if(!empty($devices3)){
                                foreach($devices3 as $device3): ?>
                                <div class="col-md-3 mt-3">
                                    <a href="quickprice.php?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $selected2; ?>&amp;cat3=<?php echo $selected3; ?>&amp;device=<?php echo $device3['id']; ?>#jump4"><button class="<?php if(!empty($selecteddev)){ if(($selecteddev == $device3['id'])){ echo 'selecteddev';} else {echo 'notselecteddev';} } else { echo 'devicebutton';} ?>"><?php echo $device3['name']; ?></button></a>

                                </div>
                                <?php endforeach;
                            } else {
                            if(!empty($devices2)){
                                if(empty($selected3)){
                                foreach($devices2 as $device2):
                                    ?>
                                    <div class="col-md-3 mt-3">
                                    <a href="quickprice.php?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $selected2; ?>&amp;device=<?php echo $device2['id']; ?>#jump4"><button class="<?php if(!empty($selecteddev)){ if(($selecteddev == $device2['id'])){ echo 'selecteddev';} else {echo 'notselecteddev';} } else { echo 'devicebutton';} ?>"><?php echo $device2['name']; ?></button></a>
                                </div>
                                <?php
                                endforeach;
                            }
                            } else {
                                if(!empty($devices)){
                                    if(empty($selected3)){
                                        if(empty($selected2)){
                                        foreach($devices as $device):
                                            ?>
                                            <div class="col-md-3 mt-3">
                                            <a href="quickprice.php?cat=<?php echo $selected; ?>&amp;device=<?php echo $device['id']; ?>#jump4"><button class="<?php if(!empty($selecteddev)){ if(($selecteddev == $device['id'])){ echo 'selecteddev';} else {echo 'notselecteddev';} } else { echo 'devicebutton';} ?>"><?php echo $device['name']; ?></button></a>
                                        </div>
                                    <?php
                                        endforeach;
                                        }
                                    }
                                }
                            }
                        }

                    ?>
                </div>



                <div class="row text-center justify-content-center mb-3 mt-5" id="jump4">


                        <?php


                            if(!empty($repairdata)){

                                foreach($repairdata as $repair):

                                    $r_name = $repair['name'];
                                    $r_id = $repair['id'];
                                    $r_dev = $repair['device_id'];



                                    if(!empty($selected3)){
                                    ?>

                                    <div class="col-md-3 mt-3">
                                    <a href="quickprice.php?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $selected2; ?>&amp;cat3=<?php echo $selected3; ?>&amp;device=<?php echo $r_dev; ?>&amp;repair=<?php echo $r_id; ?>#jump5"><button class="<?php if(($selectedrep == $r_id)){echo 'selectedrepairbutton';} else { echo 'devicebutton'; } ?>"><?php echo $r_name; ?></button></a>

                                    </div>
                            <?php
                                    } else if(!empty($selected2)){
                                    ?>

                                    <div class="col-md-3 mt-3">
                                    <a href="quickprice.php?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $selected2; ?>&amp;device=<?php echo $r_dev; ?>&amp;repair=<?php echo $r_id; ?>#jump5"><button class="<?php if(($selectedrep == $r_id)){echo 'selectedrepairbutton';} else { echo 'devicebutton'; } ?>"><?php echo $r_name; ?></button></a>

                                    </div>
                            <?php
                                    } else if(!empty($selected)){
                                        ?>

                                        <div class="col-md-3 mt-3">
                                        <a href="quickprice.php?cat=<?php echo $selected; ?>&amp;device=<?php echo $r_dev; ?>&amp;repair=<?php echo $r_id; ?>#jump5"><button class="<?php if(($selectedrep == $r_id)){echo 'selectedrepairbutton';} else { echo 'devicebutton'; } ?>"><?php echo $r_name; ?></button></a>

                                        </div>
                                <?php
                                        }
                                 endforeach;

                            }
                        ?>
                </div>

                <div class="row text-center justify-content-center mt-3">

                <div class="col-md-12">
                <hr class="border border-primary border-2 opacity-30">
                </div>

                </div>


                <?php
                if(!empty($selectedrep)){

                    $query = "
                        SELECT *
                        FROM repairs
                        WHERE id = $selectedrep
                    ";

                                                        $repdataset = array();
                                                        $sqlresult = mysqli_query($connect, $query);
                                                        if (!empty($sqlresult))
                                                            while ($row = mysqli_fetch_array($sqlresult)) {
                                                                $repdataset[] = $row;
                                                            }

                                                            foreach($repdataset as $rep):

                                                                $name = $rep['name'];
                                                                $id = $rep['id'];
                                                                $dev = $rep['device_id'];
                                                                $preis = $rep['preis'];
                                                                $repbon = $rep['repbon'];


                                                            endforeach;





                ?>


                <div class="row text-center justify-content-center mb-3" id="jump5">






                        <div class="col-md-8 card text-bg-primary m-1 text-center">



                            <div class="card-header"><b>Preis (brutto):</b></div>

                            <div class="card-body">
                            <h2>€ <?php echo $preis; ?>,-</h2>

                            <?php
                                if(($repbon == '1')){
                                    if ($preis > 400) {
                                        $rb = 200;
                                    } else $rb = floor($preis/2);

                                    ?>
                                    <h6 class="mt-3">Reparaturbonus Förderung:</h6>
                                    <h4>€ <?php echo $rb; ?>,-</h4>
                                    <?php } ?>











                            </div>
                        </div>





                        </div>
                </div>
                            <?php } ?>



                            </div>










</div>