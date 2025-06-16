<style>
    .ac_devices_ul {
        list-style-type: none;
        margin-top: 30px;
    }

    .ac_devices_li {
        display: inline-flex; /* Puts <li> items in a single line */
        padding: 20px;
        border-style: solid;
        border-radius: 50px;
        color: black;
        align-items: center; /* Centers vertically */
        justify-content: center; /* Centers horizontally */
    }

    .topmargin {
      margin-top: 60px;
    }

    .bottommargin {
      margin-bottom: 55px;
    }

    .biggerbutton {
      font-size: 2rem;
    }

    .slightlybiggerbutton {
      font-size: 14px;
    }

    .actable {
      table-layout: fixed;
    }

    td {
      text-align: center;
    }

    @media (max-width: 768px) {
  /* Make each td behave like a block on small screens */
  .table-stacked tbody td {
    display: block;
    width: 100%;
    text-align: left;
    padding: 10px;
    border-top: 1px solid #dee2e6; /* Optional: Adds border between rows */
  }

.table-stacked tbody th {
    display: block;
    width: 100%;
    text-align: left;
    padding: 10px;
    border-top: 1px solid #dee2e6; 
  }
  

  .table-stacked thead {
    display: none;
  }

  /* Display the header label within each cell */
  .table-stacked tbody td:before {
    content: attr(data-label);
    font-weight: bold;
    display: block;
    width: 50%; /* Adjust width for label alignment */
    padding-right: 10px;
    color: #6c757d; /* Optional: Style the label text color */
  }
}
    
</style>

<?php
// Content AppleCare


if(!empty($_GET['device'])){
    switch($_GET['device']) {
        case 'iphone' : $device = 'iPhone'; break;
        case 'ipad' : $device = 'iPad'; break;
        case 'mac' : $device = 'Mac'; break;
        case 'watch' : $device = 'Watch'; break;
        case 'kopfhoerer' : $device = 'Kopfhörer'; break;
        case 'display' : $device = 'Display'; break;
        case 'tv' : $device = 'TV'; break;
        case 'homepod' : $device = 'HomePod'; break;
        default : $device = 'null';
    }
echo '<div id="first"></div>';
echo '<h3 class="topmargin">AppleCare für <b>';
echo $device;
echo ' </b>abschließen (Betrag anklicken)</h3>';
echo ' <a href="https://www.macoffice.at/applecare">(zurück)</a>';
if (($device) == 'iPhone'){
    ?>

<table class="table actable table-stacked">
  <thead>
    <tr>
      <th>Modell</th>
      <th>AppleCare+ (2 Jahre)</th>
      <th>AppleCare+ mit Diebstahl und Verlust (2 Jahre)</th>
      <th>AppleCare+ (monatlich)</th>
      <th>AppleCare+ mit Diebstahl und Verlust (monatlich)</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>iPhone 16 Pro, 16 Pro Max</th>
      <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'iPhone 16 Pro, 16 Pro Max', vertrag: 'AppleCare+ (2 Jahre) um € 229,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 229,-</button></a></td>
      <td data-label="AppleCare+ mit Diebstahl und Verlust (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'iPhone 16 Pro, 16 Pro Max', vertrag: 'AppleCare+ mit Diebstahl und Verlust (2 Jahre) um € 299,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 299,-</button></a></td>
      <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: 'iPhone 16 Pro, 16 Pro Max', vertrag: 'AppleCare+ (monatlich) um € 11,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 11,99</button></a></td>
      <td data-label="AppleCare+ mit Diebstahl und Verlust (monatlich)"><a onclick="updateUrlWithParams({modell: 'iPhone 16 Pro, 16 Pro Max', vertrag: 'AppleCare+ mit Diebstahl und Verlust (monatlich) um € 14,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 14,99</button></a></td>
    </tr>
    <tr>
      <th>iPhone 16 Plus, 15 Plus, 14 Plus</th>
      <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'iPhone 16 Plus, 15 Plus, 14 Plus', vertrag: 'AppleCare+ (2 Jahre) um € 199,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 199,-</button></a></td>
      <td data-label="AppleCare+ mit Diebstahl und Verlust (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'iPhone 16 Plus, 15 Plus, 14 Plus', vertrag: 'AppleCare+ mit Diebstahl und Verlust (2 Jahre) um € 269,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 269,-</button></a></td>
      <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: 'iPhone 16 Plus, 15 Plus, 14 Plus', vertrag: 'AppleCare+ (monatlich) um € 9,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 9,99</button></a></td>
      <td data-label="AppleCare+ mit Diebstahl und Verlust (monatlich)"><a onclick="updateUrlWithParams({modell: 'iPhone 16 Plus, 15 Plus, 14 Plus', vertrag: 'AppleCare+ mit Diebstahl und Verlust (monatlich) um € 13,49'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 13,49</button></a></td>
    </tr>
    <tr>
      <th>iPhone 16, 15, 14, 13</th>
      <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'iPhone 16, 15, 14', vertrag: 'AppleCare+ (2 Jahre) um € 169,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 169,-</button></a></td>
      <td data-label="AppleCare+ mit Diebstahl und Verlust (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'iPhone 16, 15, 14', vertrag: 'AppleCare+ mit Diebstahl und Verlust (2 Jahre) um € 229,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 229,-</button></a></td>
      <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: 'iPhone 16, 15, 14', vertrag: 'AppleCare+ (monatlich) um € 8,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 8,99</button></a></td>
      <td data-label="AppleCare+ mit Diebstahl und Verlust (monatlich)"><a onclick="updateUrlWithParams({modell: 'iPhone 16, 15, 14', vertrag: 'AppleCare+ mit Diebstahl und Verlust (monatlich) um € 11,49'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 11,49</button></a></td>
    </tr>
    <tr>
      <th>iPhone 16e</th>
      <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'iPhone 16e', vertrag: 'AppleCare+ (2 Jahre) um € 139,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 139,-</button></a></td>
      <td data-label="AppleCare+ mit Diebstahl und Verlust (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'iPhone 16e', vertrag: 'AppleCare+ mit Diebstahl und Verlust (2 Jahre) um € 199,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 199,-</button></a></td>
      <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: 'iPhone 16e', vertrag: 'AppleCare+ (monatlich) um € 6,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 6,99</button></a></td>
      <td data-label="AppleCare+ mit Diebstahl und Verlust (monatlich)"><a onclick="updateUrlWithParams({modell: 'iPhone 16e', vertrag: 'AppleCare+ mit Diebstahl und Verlust (monatlich) um € 9,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 9,99</button></a></td>
    </tr>
  </tbody>
</table>


    <?php
}

if (($device) == 'iPad'){
  ?>

<table class="table actable table-stacked">
<thead>
  <tr>
    <th>Modell</th>
    <th>AppleCare+ (2 Jahre)</th>
    <th>AppleCare+ (monatlich)</th>
  </tr>
</thead>
<tbody>
  <tr>
    <th>13" iPad Pro (M4)</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: '13-inch iPad Pro (M4)', vertrag: 'AppleCare+ (2 Jahre) um € 179,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 179,-</button></a></td>
    <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: '13-inch iPad Pro (M4)', vertrag: 'AppleCare+ (monatlich) um € 8,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 8,99</button></a></td>
  </tr>
  <tr>
    <th>11" iPad Pro (M4)</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: '11-inch iPad Pro (M4)', vertrag: 'AppleCare+ (2 Jahre) um € 159,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 159,-</button></a></td>
    <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: '11-inch iPad Pro (M4)', vertrag: 'AppleCare+ (monatlich) um € 7,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 7,99</button></a></td>
  </tr>
  <tr>
    <th>13" iPad Air (M3)</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: '13-inch iPad Air (M3)', vertrag: 'AppleCare+ (2 Jahre) um € 109,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 109,-</button></a></td>
    <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: '13-inch iPad Air (M3)', vertrag: 'AppleCare+ (monatlich) um € 5,49'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 5,49</button></a></td>
  </tr>
  <tr>
    <th>11" iPad Air (M3)</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: '11-inch iPad Air (M3)', vertrag: 'AppleCare+ (2 Jahre) um € 89,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 89,-</button></a></td>
    <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: '11-inch iPad Air (M3)', vertrag: 'AppleCare+ (monatlich) um € 4,49'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 4,49</button></a></td>
  </tr>
  <tr>
    <th>iPad, iPad mini</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'iPad, iPad mini', vertrag: 'AppleCare+ (2 Jahre) um € 79,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 79,-</button></a></td>
    <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: 'iPad, iPad mini', vertrag: 'AppleCare+ (monatlich) um € 3,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 3,99</button></a></td>
  </tr>
</tbody>
</table>

  <?php
}

if (($device) == 'Mac'){
  ?>

<table class="table actable table-stacked">
<thead>
  <tr>
    <th>Modell</th>
    <th>AppleCare+ (3 Jahre)</th>
    <th>AppleCare+ (jährlich)</th>
  </tr>
</thead>
<tbody>
  <tr>
    <th>13" MacBook Air (M2)</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: '13-inch MacBook Air (M2)', vertrag: 'AppleCare+ (3 Jahre) um € 199,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 199,-</button></a></td>
    <td data-label="AppleCare+ (jährlich)"><a onclick="updateUrlWithParams({modell: '13-inch MacBook Air (M2)', vertrag: 'AppleCare+ (jährlich) um € 69,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 69,99</button></a></td>
  </tr>
  <tr>
    <th>13" MacBook Air (M3/M4)</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: '13-inch MacBook Air (M3/M4)', vertrag: 'AppleCare+ (3 Jahre) um € 219,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 219,-</button></a></td>
    <td data-label="AppleCare+ (jährlich)"><a onclick="updateUrlWithParams({modell: '13-inch MacBook Air (M3/M4)', vertrag: 'AppleCare+ (jährlich) um € 79,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 79,99</button></a></td>
  </tr>
  <tr>
    <th>15" MacBook Air (M3/M4)</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: '15-inch MacBook Air (M3/M4)', vertrag: 'AppleCare+ (3 Jahre) um € 249,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 249,-</button></a></td>
    <td data-label="AppleCare+ (jährlich)"><a onclick="updateUrlWithParams({modell: '15-inch MacBook Air (M3/M4)', vertrag: 'AppleCare+ (jährlich) um € 89,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 89,99</button></a></td>
  </tr>
  <tr>
    <th>14" MacBook Pro</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: '14-inch MacBook Pro', vertrag: 'AppleCare+ (3 Jahre) um € 299,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 299,-</button></a></td>
    <td data-label="AppleCare+ (jährlich)"><a onclick="updateUrlWithParams({modell: '14-inch MacBook Pro', vertrag: 'AppleCare+ (jährlich) um € 109,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 109,99</button></a></td>
  </tr>
  <tr>
    <th>16" MacBook Pro</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: '16-inch MacBook Pro', vertrag: 'AppleCare+ (3 Jahre) um € 429,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 429,-</button></a></td>
    <td data-label="AppleCare+ (jährlich)"><a onclick="updateUrlWithParams({modell: '16-inch MacBook Pro', vertrag: 'AppleCare+ (jährlich) um € 159,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 159,99</button></a></td>
  </tr>
  <tr>
    <th>iMac</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: 'iMac', vertrag: 'AppleCare+ (3 Jahre) um € 179,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 179,-</button></a></td>
    <td data-label="AppleCare+ (jährlich)"><a onclick="updateUrlWithParams({modell: 'iMac', vertrag: 'AppleCare+ (jährlich) um € 64,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 64,99</button></a></td>
  </tr>
  <tr>
    <th>Mac mini</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: 'Mac mini', vertrag: 'AppleCare+ (3 Jahre) um € 119,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 119,-</button></a></td>
    <td data-label="AppleCare+ (jährlich)"><a onclick="updateUrlWithParams({modell: 'Mac mini', vertrag: 'AppleCare+ (jährlich) um € 44,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 44,99</button></a></td>
  </tr>
  <tr>
    <th>Mac Studio</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: 'Mac Studio', vertrag: 'AppleCare+ (3 Jahre) um € 179,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 179,-</button></a></td>
    <td data-label="AppleCare+ (jährlich)"><a onclick="updateUrlWithParams({modell: 'Mac Studio', vertrag: 'AppleCare+ (jährlich) um € 64,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 64,99</button></a></td>
  </tr>
  <tr>
    <th>Mac Pro</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: 'Mac Pro', vertrag: 'AppleCare+ (3 Jahre) um € 529,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 529,-</button></a></td>
    <td data-label="AppleCare+ (jährlich)"><a onclick="updateUrlWithParams({modell: 'Mac Pro', vertrag: 'AppleCare+ (jährlich) um € 189,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 189,99</button></a></td>
  </tr>
</tbody>
</table>

  <?php
}

if (($device) == 'Watch'){
  ?>

<table class="table actable table-stacked">
<thead>
  <tr>
    <th>Modell</th>
    <th>AppleCare+ (2 Jahre)</th>
    <th>AppleCare+ (monatlich)</th>
  </tr>
</thead>
<tbody>
  <tr>
    <th>Apple Watch Ultra 2</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'Apple Watch Ultra 2', vertrag: 'AppleCare+ (2 Jahre) um € 109,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 109,-</button></a></td>
    <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: 'Apple Watch Ultra 2', vertrag: 'AppleCare+ (monatlich) um € 5,49'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 5,49</button></a></td>
  </tr>
  <tr>
    <th>Apple Watch Series 10</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'Apple Watch Series 10', vertrag: 'AppleCare+ (2 Jahre) um € 89,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 89,-</button></a></td>
    <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: 'Apple Watch Series 10', vertrag: 'AppleCare+ (monatlich) um € 4,49'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 4,49</button></a></td>
  </tr>
  <tr>
    <th>Apple Watch SE</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'Apple Watch SE', vertrag: 'AppleCare+ (2 Jahre) um € 65,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 65,-</button></a></td>
    <td data-label="AppleCare+ (monatlich)"><a onclick="updateUrlWithParams({modell: 'Apple Watch SE', vertrag: 'AppleCare+ (monatlich) um € 2,99'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 2,99</button></a></td>
  </tr>
</tbody>
</table>

  <?php
}

if (($device) == 'Kopfhörer'){
  ?>

<table class="table actable table-stacked">
<thead>
  <tr>
    <th>Modell</th>
    <th>AppleCare+ (2 Jahre)</th>
    </tr>
</thead>
<tbody>
  <tr>
    <th>AirPods, AirPods Pro, Beats</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'AirPods, AirPods Pro, Beats', vertrag: 'AppleCare+ (2 Jahre) um € 35,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 35,-</button></a></td>
    </tr>
  <tr>
    <th>AirPods Max</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'AirPods Max', vertrag: 'AppleCare+ (2 Jahre) um € 59,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 59,-</button></a></td>
    </tr>
</tbody>
</table>

  <?php
}

if (($device) == 'Display'){
  ?>

<table class="table actable table-stacked">
<thead>
  <tr>
    <th>Modell</th>
    <th>AppleCare+ (3 Jahre)</th>
    </tr>
</thead>
<tbody>
  <tr>
    <th>Apple Studio Display</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: 'Apple Studio Display', vertrag: 'AppleCare+ (3 Jahre) um € 139,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 139,-</button></a></td>
    </tr>
  <tr>
    <th>Pro Display XDR</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: 'Pro Display XDR', vertrag: 'AppleCare+ (3 Jahre) um € 499,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 499,-</button></a></td>
    </tr>
</tbody>
</table>

  <?php
}

if (($device) == 'TV'){
  ?>

<table class="table actable table-stacked">
<thead>
  <tr>
    <th>Modell</th>
    <th>AppleCare+ (3 Jahre)</th>
    </tr>
</thead>
<tbody>
  <tr>
    <th>Apple TV</th>
    <td data-label="AppleCare+ (3 Jahre)"><a onclick="updateUrlWithParams({modell: 'Apple TV', vertrag: 'AppleCare+ (3 Jahre) um € 29,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 29,-</button></a></td>
    </tr>
</tbody>
</table>

  <?php
}

if (($device) == 'HomePod'){
  ?>

<table class="table actable table-stacked">
<thead>
  <tr>
    <th>Modell</th>
    <th>AppleCare+ (2 Jahre)</th>
    </tr>
</thead>
<tbody>
  <tr>
    <th>HomePod</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'HomePod', vertrag: 'AppleCare+ (2 Jahre) um € 39,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 39,-</button></a></td>
    </tr>
  <tr>
    <th>HomePod mini</th>
    <td data-label="AppleCare+ (2 Jahre)"><a onclick="updateUrlWithParams({modell: 'HomePod mini', vertrag: 'AppleCare+ (2 Jahre) um € 15,-'})"><button type="button" class="btn--gray slightlybiggerbutton">€ 15,-</button></a></td>
    </tr>
</tbody>
</table>

  <?php
}


} else {

?>

<h3 class="topmargin">Für welches Gerät möchten Sie AppleCare+ abschließen?</h3>

<div class="topmargin">
    <a onclick="updateUrlWithDeviceiphone()"><button type="button" class="btn--gray biggerbutton">iPhone</button></a>
    <a onclick="updateUrlWithDeviceipad()"><button type="button" class="btn--gray biggerbutton">iPad</button></a>
    <a onclick="updateUrlWithDevicemac()"><button type="button" class="btn--gray biggerbutton">Mac</button></a>
    <a onclick="updateUrlWithDevicewatch()"><button type="button" class="btn--gray biggerbutton">Watch</button></a>
    <a onclick="updateUrlWithDevicekopfhoerer()"><button type="button" class="btn--gray biggerbutton">Kopfhörer</button></a>
    <a onclick="updateUrlWithDevicedisplay()"><button type="button" class="btn--gray biggerbutton">Display</button></a>
    <a onclick="updateUrlWithDevicetv()"><button type="button" class="btn--gray biggerbutton">TV</button></a>
    <a onclick="updateUrlWithDevicehomepod()"><button type="button" class="btn--gray biggerbutton">HomePod</button></a>
</div>

<?php 

}

?>

<div class="bottommargin" id="second"></div>

<script>
function updateUrlWithDeviceiphone() {
  const url = new URL(window.location.href);
  url.searchParams.set('device', 'iphone'); // Adds or updates the 'device' parameter
  url.hash = "first";
  window.location.href = url; // Navigates to the updated URL
}

function updateUrlWithDeviceipad() {
  const url = new URL(window.location.href);
  url.searchParams.set('device', 'ipad'); // Adds or updates the 'device' parameter
  url.hash = "first";
  window.location.href = url; // Navigates to the updated URL
}

function updateUrlWithDevicemac() {
  const url = new URL(window.location.href);
  url.searchParams.set('device', 'mac'); // Adds or updates the 'device' parameter
  url.hash = "first";
  window.location.href = url; // Navigates to the updated URL
}

function updateUrlWithDevicewatch() {
  const url = new URL(window.location.href);
  url.searchParams.set('device', 'watch'); // Adds or updates the 'device' parameter
  url.hash = "first";
  window.location.href = url; // Navigates to the updated URL
}

function updateUrlWithDevicekopfhoerer() {
  const url = new URL(window.location.href);
  url.searchParams.set('device', 'kopfhoerer'); // Adds or updates the 'device' parameter
  url.hash = "first";
  window.location.href = url; // Navigates to the updated URL
}

function updateUrlWithDevicedisplay() {
  const url = new URL(window.location.href);
  url.searchParams.set('device', 'display'); // Adds or updates the 'device' parameter
  url.hash = "first";
  window.location.href = url; // Navigates to the updated URL
}

function updateUrlWithDevicetv() {
  const url = new URL(window.location.href);
  url.searchParams.set('device', 'tv'); // Adds or updates the 'device' parameter
  url.hash = "first";
  window.location.href = url; // Navigates to the updated URL
}

function updateUrlWithDevicehomepod() {
  const url = new URL(window.location.href);
  url.searchParams.set('device', 'homepod'); // Adds or updates the 'device' parameter
  url.hash = "first";
  window.location.href = url; // Navigates to the updated URL
}

// N = Normal   F = Fix   D = Diebstahl + Verlust   A = Abo


function updateUrlWithParams(params) {
  const url = new URL(window.location.href);
  
  // Loop through each key-value pair in the `params` object and set them in the URL
  Object.keys(params).forEach(key => {
    url.searchParams.set(key, params[key]);
  });

  url.hash = "second";

  window.location.href = url; // Navigates to the updated URL
}

</script>


