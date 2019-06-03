
<?php
include_once('Call.php');



$target_dir = "uploads/";
$target_file = $target_dir.basename($_FILES["fileUpload"]["name"]);

    //uploading file.
    if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)){
      echo "file name: " .basename($_FILES["fileUpload"]["name"]);
	  $data = new callData($target_file);
	  $allCustomerID = $data->getAllCustomerID();
      echo "<table>
	<tr>
	<td>customer id
	</td>
	<td> Number of customer's calls within same continent:
	</td>
	<td>
	Total duration of customer's calls within same continent:
	</td>
	<td>
	Number of all customer's calls:
	</td>
	<td>
	Total duration of all customer's calls:
	</td>
	</tr>" ;

	  foreach($allCustomerID as $cID){
		  $numCallsSameCnt = $data->getNumCallsSameContinent($cID);
		  $durationSameCnt = $data->getDurationCallsSameContinent($cID);
		  $numCallsCid = $data->getNumCallsForCustomer($cID);
		  $durationCallsCid = $data->getCallsDurationForCustomer($cID);
		  echo "<tr><td>".$cID."</td><td>".$numCallsSameCnt."</td>
                <td>".$durationSameCnt."</td><td>".$numCallsCid."</td><td>".$durationCallsCid."</td></tr>";

	  }
	  echo "</table>";
    }
    else{
      echo "faild to upload file";
    }




?>
 