<?php include 'header.php'; ?>
<?php include 'navigation.php'; ?>
<div id="middleContainer"><div id="middle">

<!-- CONTENT START -->
<?php
	//Ensure a USER is viewing the user page
	$done = 0;$donee = 0;
	if (isset($_SESSION['Email'])){
		echo "<h1>User Account Controls</h1><hr>";
		
		// Password Change
		$match = 0;$wrong = 0;
		if (isset($_POST['word1'])&&isset($_POST['word2'])&&isset($_POST['word3'])){
			if($_POST['word2']!="" && $_POST['word3']!=""){
				if ($_POST['word2'] != $_POST['word3']){
					$match = 1;
				}
				
				$dbPassword = singleSQL("SELECT Password FROM users WHERE Email='$_SESSION[Email]'", $mysqli);
				if($dbPassword === md5($_POST['word1'])){
					$wrong = 0;
				}else{
					$wrong = 1;
				}
				
				if(!$match && (!$wrong)){
						$passw = md5($_POST['word2']);
						$sql = "UPDATE users SET `password` = '$passw' WHERE Email = '$_SESSION[Email]' LIMIT 1";
						runSQL($sql);
						$done = 1;
				}
			}else{echo 'You need to fill in all of the boxes.';}
		// End PW Change
		}else if (isset($_POST['cemail']) && isset($_POST['cphone']) && isset($_POST['cadd'])){
			$email = $_POST['cemail'];
			$phony = $_POST['cphone'];
			$dress = $_POST['cadd'];
			$_SESSION['Email'] = $email;
			$sql = "UPDATE users SET `ContactNum` = '$phony', `ShippingAddress` = '$dress', `Email` = '$email' WHERE Email = '$_SESSION[Email]' LIMIT 1";
			runSQL($sql);
			$donee = 1;
		}
?>
	<?php 
		if ($done){
			echo "<h3>Password successfully changed!</h3>";
		}else if ($donee){
			echo "<h3>User Details Changed!</h3>";
		}
	?>
	
	
	<div id=userinfo>
		<h2>My Details</h2>
		<?php 
			$sql = "SELECT * FROM users where Email = '$_SESSION[Email]'";
			$row = singleRowSQL($sql);
			echo "<form method='POST' action='./account.php'><b> Email:</b> <input type='email' name='cemail' value='$row[Email]'><br>";
			echo "<b> Name:</b> $row[FirstName] $row[Surname]<br>";
			echo "<b> Contact Number:</b> <input type='tel' name='cphone' value='$row[ContactNum]'><br>";
			echo "<b> Shipping Address:</b> <input type='text' name='cadd' value='$row[ShippingAddress]'><br><input type='submit' value='Change Details'><br></form>";
		?>
	</div>
	
	<div id=pwbox>
		<form id='passchange' method='POST'>
			<h2>Change Password</h2>
			<input name='word1' type='password' placeholder='Old Password'>
			<?php if ($wrong){ echo "Wrong Password!";} ?><br>
			<input name='word2' type='password' placeholder='New Password'>
			<input name='word3' type='password' placeholder='Repeat New Password'><br>
			<?php if ($match){echo "New Passwords Don't Match.<br>";} ?>
			<input type='submit' value='Change'>
		</form>
	</div>
	<hr>
	
	<h2>My Orders</h2>
	<table id="tableList">
	<th>ID</th>
	<th>Total Cost</th>
	<th>Items</th>
	<th>Date</th>
	<th>Status</th>
	<?php
	
		$email = $_SESSION['Email'];
		$userid = singleSQL("SELECT UserID FROM users WHERE Email='$email'");
		
		$ayy = multiSQL("SELECT OrderID, OrderCost, ItemsOrdered, DateOrdered, Status FROM orders WHERE CreatedBy=$userid");
		while($rows = mysqli_fetch_array($ayy,MYSQLI_BOTH)){
			echo "<tr>";
			echo "<td>Order #" . $rows['OrderID'] . "</td>";
			echo "<td>$" . sprintf('%0.2f',$rows['OrderCost']) . "</td>";
			echo "<td>";
			
			$items = explode(' ', $rows['ItemsOrdered']);
			
			for($i=0; count($items) > $i; $i++){
				$id = $items[$i];
				$itemInfo = singleRowSQL("SELECT Name, Price FROM designs WHERE DesignID=$id");
				echo "<a href='./product.php?item=" . $id . "'>" .$itemInfo['Name'] . "</a> ($" . sprintf('%0.2f',$itemInfo['Price']) . ")<br>";
			}
			
			echo "</td>";
			echo "<td>" . $rows['DateOrdered'] . "</td>";
			echo "<td>" . $rows['Status'] . "</td>";
			echo "</tr>";
		}
	
	?>
	</table>
	
<?php
	}else{
		echo $notLoggedInMessage;
	}
?>


<!-- CONTENT END -->

</div></div>
<?php include 'footer.php'; ?>