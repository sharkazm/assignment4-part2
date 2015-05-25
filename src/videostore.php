<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">      
	<title>Mike's Video</title>  
	<style>
		table, td, th{border-style: ridge; 
					background-color:white;
					background-size: 75%;
					border-width: thick; 
					border-color:red; 
					border-collapse: collapse; 
					width: 1455px; 
					text-align: center; 
					padding: 10px;
					font-family: Century Gothic, sans-serif;};
	</style>
</head>

<body style = "background-color:blue">
	<div id = "addInv" style = "font-family: Century Gothic, sans-serif;
								text-align: center; 
								background-color: white; 
								padding:10px; 
								margin-left: 15%;
								margin-right: 15%; 
								border-style: ridge; 
								border-width: thick; 
								border-color:red">

		<H4>Enter new inventory info:</H4>
		<form action = "videostore.php" method = 'POST'>
			Video name:
			<input type = 'text' name = 'name'>

			Video category:
			<input type = 'text' name = 'category'>

			Video length:
			<input type = 'number' name = 'length' min = '0'><br><br> 

			<input type = 'hidden' name = 'type' value = 'add'>
			<input type = 'submit' value = 'Add to inventory' /><br><br>

		</form>
			<form action = "videostore.php" method = 'GET'>
			<input type = 'hidden' name = 'clear' value = 'all'>
			<input type = 'submit' value = "Empty inventory">
		</form>
	</div>

<?php

$errorFree = true;

if($_SERVER['REQUEST_METHOD'] == 'POST')		//add a new video to inventory
{
	if($_POST['type'] == 'add')			
	{
	    $name = $_POST['name'];
	    $category = $_POST['category'];
	    $length = $_POST['length'];

	    if(!(strlen($name) > 0))		//if a name is not entered
	    {
	    	echo "Error.  You must enter a video name.\n";
	    	$errorFree = false;
	    }
	}
}
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if($_POST['type'] == 'remove')
	{
	  	
		$deleteName = $_POST['ToRemove'];

	  	$conn=mysqli_connect("oniddb.cws.oregonstate.edu","sharkazm-db","wPb48qsJFnowXv5n","sharkazm-db");  //connection code adapted from onid.oregonstate.edu
	  																										//connects to the database
   
    	if (mysqli_connect_errno($con))
    	{
    			echo "Could not connect to database: " . mysqli_connect_error();
    	}    
		
		$sql = "DELETE FROM inventory290a4 WHERE name = '$deleteName'";

		if ($connection->query($sql) === TRUE) 
		{
		    echo "Video deleted successfully";
		} 

		else 
		{
		    echo "Error deleting video: " . $connection->error;
		}

		$connection->close(); 
	}
}

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "sharkazm-db", "wPb48qsJFnowXv5n", "sharkazm-db");

if(!$mysqli || $mysqli->connect_errno)
{
	echo "Connection error ".$mysqli->connect_errno . "".$mysqli->connect_error;
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if($_POST['type'] == 'edit')
	{
		$editName = $_POST['ToEdit'];
		$inOrOut = $_POST['stockStatus'];
		
		if($inOrOut == 'Available')
			$changeRented = 'out';
		else
			$changeRented = 'in';
		
		$lineInput = "UPDATE inventory290a4 SET rented = '$changeRented' WHERE name = '$editName'";

		if ($mysqli->query($lineInput) === TRUE) 
		{
    		echo "    ";
		} 
		
		else 
		{
		    echo "Error updating video: " . $mysqli->error;
		}  
	}
}

if($errorFree == true && $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == 'add')		//prep code adapted from the php manual
{
	
	if((strlen($category) > 0) && ($_POST['length'] == NULL))
	{
		
		if (!($stmt = $mysqli->prepare("INSERT INTO inventory290a4(name, category) VALUES (?, ?)"))) 
		{
		    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}	

		if (!$stmt->bind_param("ss", $name, $category)) 
		{
	    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
	}
	
	else if((strlen($category) > 0) && ($_POST['length'] != NULL))
	{
		
		if (!($stmt = $mysqli->prepare("INSERT INTO inventory290a4(name, length, category) VALUES (?, ?, ?)"))) 
		{
		    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}	

		if (!$stmt->bind_param("sis", $name, $length, $category)) 
		{
	    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}

		if (!$stmt->execute()) 
		{
	    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
	}

	else if((strlen($category) == 0) && ($_POST['length'] == NULL))
	{
		if (!($stmt = $mysqli->prepare("INSERT INTO inventory290a4(name) VALUES (?)"))) 
		{
		    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}	

		if (!$stmt->bind_param("s", $name))
		{
	    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}

		if (!$stmt->execute()) 
		{
	    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
	}

	else if((strlen($category) == 0) && ($_POST['length'] != NULL))
	{
		if (!($stmt = $mysqli->prepare("INSERT INTO inventory290a4(name, length) VALUES (?, ?)"))) 
		{
		    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}	

		if (!$stmt->bind_param("si", $name, $length)) 
		{
	    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}

		if (!$stmt->execute()) 
		{
	    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
	}
}

function deleteTable()
{
 	$con=mysqli_connect("oniddb.cws.oregonstate.edu","sharkazm-db","wPb48qsJFnowXv5n","sharkazm-db");	//connect

    if (mysqli_connect_errno($con))						//connection check
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }    

    $sql = "TRUNCATE TABLE inventory290a4";					
    mysqli_query($con, $sql) or die(mysqli_error());
}

if($_SERVER['REQUEST_METHOD'] == 'GET')		//delete the table
{
	if($_GET['clear'] == 'all')
		deleteTable();
}

echo "<br><br>
	<div id = 'DynamicSection' style = 'background-color:white; 
										width:97%; 
										height:1500px; 
										display: inline-block;  
										padding:20px; 
										margin-all:20px; 
										border-style: groove; 
										border-width: thick; 
										border-color:red; 
										margin-right:20px'>
		<table> 
			<caption> 
				<h2> 
					<font: 15px georgia, serif; font color = 'blue'; text-align: center;> 
						Mike's Video Inventory 
					</font>
				</h2>
			</caption>
		<tr>
			<th> 
				Name 
			<th> 
				Category 
			<th> 
				Length 
			<th> 
				Availability 
			</th>";
	
	
	
	$cats = 'SELECT DISTINCT category FROM inventory290a4';
	if($row = $mysqli->query($cats))
	{
		echo "<form action = 'videostore.php' method = 'POST'>";
		echo "<input type = 'hidden' name = 'type' value = 'filter'>";
		echo "<select name ='var' onchange = 'this.form.submit()'>";

		$all = 'All movies';
		$choice = 'Sort by category';

		echo "<option value = '".$choice."'>".$choice."</option>";
		echo "<option value = '".$all."'>".$all."</option>";

		while($distinctCategory = $row->fetch_array(MYSQL_NUM))
		{
			if(strlen($distinctCategory[0]) > 0)
			echo "<option value = '".$distinctCategory[0]."'>".$distinctCategory[0]."</option>";
		}

		echo'</select></form>';
	}

	$selection = "SELECT name, category, length, rented FROM inventory290a4";

	$specCat = false;

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if($_POST['type'] == 'filter' || $_POST['type'] == 'edit')
		{
			if($_POST['var'] == "All movies")
			{
				$selection = "SELECT name, category, length, rented FROM inventory290a4";
			}	
			else
			{
				$choice = $_POST['var'];
				$selection = "SELECT name, category, length, rented FROM inventory290a4 WHERE category='$choice'";
				$specCat = true;
			}
		}
	}

	$queryResults = $mysqli->query($selection);

	while($row = $queryResults->fetch_row())
	{
		if($row[3] == "in")
			$avail = "Available";

		else
			$avail = "Checked Out";

		echo "<tr>
				<td>
					$row[0]
				<td>
					$row[1]
				<td>
					$row[2]";

		$editName = $row[0];

		echo "<td>
				<form action= 'videostore.php' method='POST'
					><input type= 'hidden' name='type' value='edit'>";

		if($specCat == false)
			$setCat = "All movies";
		else
			$setCat = $_POST['var'];

		echo "		<input type = 'hidden' name = 'var' value = '$setCat'>";
		echo "		<input type = 'hidden' name = 'ToEdit' value = '$editName'>
					<input type = 'hidden' name = 'stockStatus' value = '$avail'>
					<input type = 'submit' value = '$avail'>
		 		</form>";

		$remName = $row[0];

		echo "	<td>
					<form action = 'videostore.php' method = 'POST'>
						<input type = 'hidden' name = 'type' value = 'remove'>
						<input type = 'hidden' name = 'ToRemove' value = '$remName'>
						<input type = 'submit' value = 'Delete'>
		 			</form>
		 		</td>
		 	</tr>";  
	}	
?>
		</table>
	
	</div>

</body>

<html>
