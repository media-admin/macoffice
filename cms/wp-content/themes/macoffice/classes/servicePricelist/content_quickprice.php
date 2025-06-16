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



<div class="service-prices__container">
    <h2 class="wp-block-heading">Wählen Sie zur Anzeige der Reparaturkosten Ihr Gerät aus:</h2>

        <div class="service-prices__device-type-section">
<!--    <div class="service-prices__device-type-heading row text-center justify-content-center mt-3 mb-3"> -->
            <h3 class="service-prices__device-type-heading wp-block-heading">Geräte-Art</h3>
            <div class="service-prices__device-type-listing service-prices__device-listing">
                <?php
                foreach($categories1 as $cat1):?>
                <div class="service-prices__device-type-listing-item service-prices__device-listing-item">
    <!--        <div class="col-md-2 p-2"> -->
                    <a class="service-prices__device-type-listing-link service-prices__device-listing-link" href=?cat=<?php echo $cat1['id']; ?>#jump1"><button class="<?php if(!empty($selected)){ if(($selected == $cat1['id'])){ echo 'selectedbutton';} else { echo 'notselected';} } else {echo 'livebutton';} ?>"><?php echo $cat1['name']; ?></button></a>
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
        </div>

        <div class="service-prices__device-category-section" id="jump01">
<!--    <div class="row text-center justify-content-center mt-5" id="jump1"> -->
            <?php
                if(!empty($selected)){
                    echo '<h3 class="service-prices__device-category-heading wp-block-heading">Geräte-Kategorie</h3>';
                    echo '<div class="service-prices__device-category-listing service-prices__device-listing">';
                }

                    foreach($categories2 as $cat2):
                        if(!empty($selected)){
                            if($selected == $cat2['parent']){
                            ?>
                            <div class="service-prices__device-category-listing-item service-prices__device-listing-item">
    <!--                    <div class="col-md-2 p-2"> -->
                                <a class="service-prices__device-category-listing-link service-prices__device-listing-link" href="?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $cat2['id']; ?>#jump2"><button class="<?php if(!empty($selected2)){ if(($selected2 == $cat2['id'])){ echo 'selectedbutton';} else { echo 'notselected';} } else {echo 'livebutton';}  ?>"><?php echo $cat2['name']; ?></button></a>
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
        </div>

<!--         <div class="service-prices__device-subcategory-section" id="jump2"> -->
<!--    <div class="row text-center justify-content-center mt-5" id="jump2"> -->

            <?php

                if(!empty($selected2) ) {
                    if(!$devices2==0 ) {
                        // echo count($devices2);
                    } else {
                        echo '<div class="service-prices__device-subcategory-section" id="jump02">';
                        echo '<h3 class="service-prices__device-subcategory-heading wp-block-heading">Geräte-Unterkategorie</h3>';
                        echo '<div class="service-prices__device-subcategory-listing service-prices__device-listing">';

                        foreach( $categories3 as $cat3):
                            if( !empty($selected2) ) {
                                if( $selected2 == $cat3['parent'] ) {
                                ?>
                                <div class="service-prices__device-subcategory-listing-item service-prices__device-listing-item">
        <!--                    <div class="col-md-2 p-2"> -->
                                    <a class="service-prices__device-subcategory-listing-link service-prices__device-listing-link" href="?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $selected2; ?>&amp;cat3=<?php echo $cat3['id']; ?>#jump3"><button class="<?php
                                        if(!empty($selected3))
                                        {
                                            if(($selected3 == $cat3['id']))
                                            {
                                                echo 'selectedbutton';
                                            }
                                            else
                                            {
                                                echo 'notselected';
                                            }
                                        } else {
                                            echo 'livebutton';
                                        } ?>"><?php echo $cat3['name']; ?></button></a>
                                </div>

                                <?php
                                if(!empty($selected3)) {
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
                    echo '</div>';
                echo '</div>';
                }
            } ?>


        <div class="service-prices__device-model-section" id="jump03">
<!--    <div class="row text-center justify-content-center mt-3" id="jump3"> -->
            <!-- <h3 class="service-prices__device-model-heading wp-block-heading">Geräte-Modell</h3>
            <div class="service-prices__device-model-listing service-prices__device-listing"> -->
            <?php



                    if(!empty($devices3)){

                        echo '<h3 class="service-prices__device-model-heading wp-block-heading">Geräte-Modell</h3>';
                        echo '<div class="service-prices__device-model-listing service-prices__device-listing">';


                        foreach($devices3 as $device3): ?>
                            <div class="service-prices__device-model-listing-item service-prices__device-listing-item">
    <!--                    <div class="col-md-3 mt-3"> -->
                                <a class="service-prices__device-model-listing-link service-prices__device-listing-link" href="?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $selected2; ?>&amp;cat3=<?php echo $selected3; ?>&amp;device=<?php echo $device3['id']; ?>#jump4"><button class="<?php if(!empty($selecteddev)){ if(($selecteddev == $device3['id'])){ echo 'selecteddev';} else {echo 'notselecteddev';} } else { echo 'devicebutton';} ?>"><?php echo $device3['name']; ?></button></a>
                            </div>
                        <?php
                        endforeach;
                    } else {
                        if(!empty($devices2)){
                            if(empty($selected3)){
                                echo '<h3 class="service-prices__device-model-heading wp-block-heading">Geräte-Modell</h3>';
                                echo '<div class="service-prices__device-model-listing service-prices__device-listing">';
                                foreach($devices2 as $device2):
                                ?>
                                    <div class="service-prices__device-model-listing-item service-prices__device-listing-item">
     <!--                           <div class="col-md-3 mt-3"> -->
                                        <a class="service-prices__device-model-listing-link service-prices__device-listing-link" href="?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $selected2; ?>&amp;device=<?php echo $device2['id']; ?>#jump4"><button class="<?php if(!empty($selecteddev)){ if(($selecteddev == $device2['id'])){ echo 'selecteddev';} else {echo 'notselecteddev';} } else { echo 'devicebutton';} ?>"><?php echo $device2['name']; ?></button></a>
                                    </div>
                                <?php
                                endforeach;
                            }
                        } else {
                            if(!empty($devices)){
                                if(empty($selected3)){
                                    if(empty($selected2)){
                                        echo '<h3 class="service-prices__device-model-heading wp-block-heading">Geräte-Modell</h3>';
                                        echo '<div class="service-prices__device-model-listing service-prices__device-listing">';
                                        foreach($devices as $device):
                                        ?>
                                            <div class="service-prices__device-model-listing-item service-prices__device-listing-item">
    <!--                                    <div class="col-md-3 mt-3"> -->
                                                <a class="service-prices__device-model-listing-link service-prices__device-listing-link" href="?cat=<?php echo $selected; ?>&amp;device=<?php echo $device['id']; ?>#jump4"><button class="<?php if(!empty($selecteddev)){ if(($selecteddev == $device['id'])){ echo 'selecteddev';} else {echo 'notselecteddev';} } else { echo 'devicebutton';} ?>"><?php echo $device['name']; ?></button></a>
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
        </div>

        <div class="service-prices__device-topic-section" id="jump04">
<!--    <div class="row text-center justify-content-center mb-3 mt-5" id="jump4"> -->
        <span class="anchor--scroll-60"></span>

            <?php
                if(!empty($repairdata && $selected3 || $repairdata && $selected2 || $repairdata && $selected)){
                    echo '<h3 class="service-prices__device-topic-heading wp-block-heading">Thema</h3>';
                    echo '<div class="service-prices__device-topic-listing service-prices__device-listing">';
                }

                if(!empty($repairdata)){
                    foreach($repairdata as $repair):

                        $r_name = $repair['name'];
                        $r_id = $repair['id'];
                        $r_dev = $repair['device_id'];

                        if(!empty($selected3)){
                        ?>
                            <div class="service-prices__device-topic-listing-item service-prices__device-listing-item">
<!--                        <div class="col-md-3 mt-3"> -->
                                <a class="service-prices__device-topic-listing-link service-prices__device-listing-link" href="?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $selected2; ?>&amp;cat3=<?php echo $selected3; ?>&amp;device=<?php echo $r_dev; ?>&amp;repair=<?php echo $r_id; ?>#jump5"><button class="<?php if(($selectedrep == $r_id)){echo 'selectedrepairbutton';} else { echo 'devicebutton'; } ?>"><?php echo $r_name; ?></button></a>
                            </div>
                        <?php
                        } else if(!empty($selected2)){
                        ?>
                            <div class="service-prices__device-topic-listing-item service-prices__device-listing-item">
<!--                        <div class="col-md-3 mt-3"> -->
                                <a class="service-prices__device-topic-listing-link service-prices__device-listing-link" href="?cat=<?php echo $selected; ?>&amp;cat2=<?php echo $selected2; ?>&amp;device=<?php echo $r_dev; ?>&amp;repair=<?php echo $r_id; ?>#jump5"><button class="<?php if(($selectedrep == $r_id)){echo 'selectedrepairbutton';} else { echo 'devicebutton'; } ?>"><?php echo $r_name; ?></button></a>
                            </div>
                        <?php
                        } else if(!empty($selected)){
                        ?>
                            <div class="service-prices__device-topic-listing-item service-prices__device-listing-item">
<!--                        <div class="col-md-3 mt-3"> -->
                                <a class="service-prices__device-topic-listing-link service-prices__device-listing-item" href="?cat=<?php echo $selected; ?>&amp;device=<?php echo $r_dev; ?>&amp;repair=<?php echo $r_id; ?>#jump5"><button class="<?php if(($selectedrep == $r_id)){echo 'selectedrepairbutton';} else { echo 'devicebutton'; } ?>"><?php echo $r_name; ?></button></a>
                            </div>
                    <?php }
                    endforeach;
                }
            ?>
            </div>
        </div>

        <div class="service-prices__border-section">
<!--    <div class="row text-center justify-content-center mt-3"> -->
<!--        <div class="col-md-12"> -->
<!--                 <hr class="service-prices__border"> -->
<!--            <hr class="border border-primary border-2 opacity-30"> -->
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

                <div class="service-prices__repair-bonus-section" id="jump05" >
<!--            <div class="row text-center justify-content-center mb-3" id="jump5"> -->
                    <div class="service-prices__repair-bonus-card">
<!--                <div class="col-md-8 card text-bg-primary m-1 text-center"> -->
                        <div class="service-prices__repair-bonus-header">
                            <p class="service-prices__repair-bonus-heading">Preis inkl. USt.:</p>
                        </div>
<!--                    <div class="card-header"><b>Preis (brutto):</b></div> -->
                            <div class="service-prices__repair-bonus-card-body">
<!--                        <div class="card-body"> -->
                                <p class="service-prices__repair-bonus-card-body-heading">€ <?php echo $preis; ?>,-</p>
                                <?php
                                if(($repbon == '1')){
                                    if ($preis > 400) {
                                        $rb = 200;
                                    } else $rb = floor($preis/2);
                                    ?>
                                        <p class="service-prices__repair-bonus-card-body-heading">Mögliche Reparaturbonus Förderung:</p>
<!--                                    <h6 class="mt-3">Reparaturbonus Förderung:</h6> -->
                                        <p class="service-prices__repair-bonus-card-body-data">€ <?php echo $rb; ?>,-</p>
                                    <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php echo do_shortcode('[shortcode_repair_instructions]'); ?>

            <?php } ?>

            <?php
                $query = "
                    SELECT name
                    FROM devices
                    WHERE id = $selecteddev
                ";

                $devicenameset = array();
            $sqlresult = mysqli_query($connect, $query);
            if (!empty($sqlresult))
                while ($row = mysqli_fetch_array($sqlresult)) {
                    $devicenameset[] = $row;
                }
                foreach($devicenameset as $devicename):
                    $dname = $devicename['name'];
                   
                endforeach;


            ?>


            <script type="text/javascript">
jQuery(document).ready(function($) {
    $('input[name="repair"]').val('<?php echo $name; ?>');
    $('input[name="device"]').val('<?php echo $dname; ?>');
});
</script>

