<?php include 'header.php'; ?>
<?php include 'navigation.php'; ?>
<div id="middleContainer"><div id="middle">

<!-- CONTENT START -->
	<?php
		if(isset($_GET['item'])){
			$id=$_GET['item'];
			$row = singleRowSQL("SELECT DesignID, Name, Price, Available, Material, Categories FROM designs WHERE DesignID=$id");
			if($row == 0){
				echo "<h2>Product not found :(<br><br>#sadboys2001</h2>";
			}else{
				echo '
				<img src="http://img2.wikia.nocookie.net/__cb20140518071050/towerofsaviors/images/archive/4/47/20140518072131!Placeholder.png" id="itemImg">
				<h2>' . $row["Name"] . ' - $' . sprintf('%0.2f',$row["Price"]) . '</h2>
				<p>
					Material: ' . $row['Material'] . '<br><br>
					ayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao
					ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy
					lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmao
					ayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmao
					ayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmaoayy lmao ayy lmao<br><br>
					Tags: ' . $row["Categories"] . '
				</p>
				<input type="button" value="Add To Cart" onclick="addToCart(' . $id . ');">
				<div class="clear"></div>';
			}
		}
		else{
		
			if(isset($_SESSION['Email']) && isset($_POST['name']) && isset($_POST['material']) && isset($_POST['price']) && isset($_POST['categories'])){
				
				$email = $_SESSION['Email'];
				$userid = singleSQL("SELECT UserID FROM users WHERE Email='$email'");			
				
				$originalFile = $_FILES["model"]["name"];
				$tmp = explode('.', $originalFile);
				$extension = end($tmp);
				
				$fileName = $userid . "-" . date("Y-m-d-H-i-s") . "-model." . $extension;
				move_uploaded_file($_FILES["model"]["tmp_name"], "./ModelFiles/" . $fileName);
				
				$name = $_POST['name'];
				$material = $_POST['material'];
				$price = $_POST['price'];
				$category = $_POST['categories'];
									
				$designID = singleSQL('SELECT DesignID FROM designs ORDER BY DesignID DESC LIMIT 1') + 1;
				
				$sql1 = runSQL("INSERT INTO designs (designID, File, Categories, Name, Price, Available, Author, Material) VALUES($designID,'$fileName','$category','$name',$price,'Yes',$userid,'$material')");

				if($sql1){
					echo 'Design submitted.';
				} else { echo 'Something went wrong.' . "INSERT INTO designs (designID, File, Categories, Name, Price, Available, Author, Material) VALUES($designID,'$fileName','$category','$name',$price,'Yes',$userid,'$material')"; }
					
			}
	
		?>

		<h1>My Products</h1>
		<table id="myProducts">
			<tr>
				<th>Name</th>
				<th>Price</th>
				<th>Available</th>
				<th>Material</th>
			</tr>
			<?php
				$email = $_SESSION['Email'];
				$userid = singleSQL("SELECT UserID FROM users WHERE Email='$email'");
				
				$ayy = multiSQL("SELECT DesignID, Name, Price, Available, Material FROM designs WHERE Author=$userid");
				while($rows = mysqli_fetch_array($ayy,MYSQLI_BOTH)){
					echo "<tr>";
					echo "<td><a href='./product.php?item=" . $rows['DesignID'] . "'>" . $rows['Name'] . "</a></td>";
					echo "<td>$" . sprintf('%0.2f',$rows['Price']) . "</td>";
					echo "<td>" . $rows['Available'] . "</td>";
					echo "<td>" . $rows['Material'] . "</td>";
					echo "</tr>";
				}
			?>
		</table>
		<h1>Submit New Product</h1>
		
		<form method="post" action="" enctype="multipart/form-data" >
			<label>Name of product</label><br><input type="text" name="name"><br>
			<label>Material to be made out of</label><br>
			<select name="material">
			<?php
				$materials = multiSQL('SELECT MaterialID, Name, CostPerCubicCM FROM materials', $mysqli);
				while($rows = mysqli_fetch_array($materials,MYSQLI_BOTH)){
					echo '<option value="' . $rows['MaterialID'] . '">' . $rows['Name'] . ' - $' . sprintf('%0.2f',$rows['CostPerCubicCM']) . '</option>';
				}
			?>	
			</select><br>
			<label>Price to sell at</label><br><input type="number" step="any" name="price"><br>
			<label>Categories/Tags</label><br><input type="text" name="categories"><br>
			<label>Model File</label><br><input type="file" name="model"><br><br>
			<input type="submit" value="Submit Design">
		</form>
<?php } ?>

<!-- CONTENT END -->

</div></div>
<?php include 'footer.php'; ?>