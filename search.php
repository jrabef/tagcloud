<html land="en">
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/likebutton.css" />
	<script type="text/javascript" src="like_message.js"></script>
	
	<?php include_once 'includes.php';
		  include 'connect.php';
		  include 'include\build_search_query.php';
		  include 'include\get_song_data.php';
	?>
</head>

<body>

	<h1>Defiant Rock</h1>

	<div id="cssmenu">
		<ul>
			<li><a href='index.php'><span>Home</span></a></li>
			<li><a href='user.php'><span>Users</span></a></li>
			<li><a href='article.php'><span>Articles</span></a></li>
				<ul>
					<li><a href='#'><span>Reviews</span></a></li>
					<li><a href='#'><span>Interviews</span></a></li>
				</ul>
			<li class='active'><a href='search.php'><span>Search</span></a></li>
			<li><a href='playlist.php'><span>Playlists</span></a></li>
			<li><a href='band.php'><span>Bands</span></a></li>
				<ul>
					<li><a href='bandtag.php'><span>Band Tags</span></a></li>
					<li><a href='songtag.php'><span>Song Tags</span></a></li>
				</ul>
		</ul>
	</div>
	
	<!---------------------->
	<!--- show user menu --->
	<!---------------------->
	<div class="floatright">
	<?PHP
		require_once("./include/membersite_config.php");

		if(!$fgmembersite->CheckLogin()) {
			echo "<a href='register.php'>Register</a> or <a href='login.php'>Login</a>";
		}
		else{
			echo '<a href="login-home.php">'.$fgmembersite->UserFullName().'</a><br>';
			echo "<a href='logout.php'>Logout</a>";
		}
	?>
	</div>
	
	<div id="outerWrapper">

	<?php
		//Execute code ONLY if connections were successful 	
		if ($dbSuccess) {
			
			//search button clicked
			if (!empty ($_POST["submit"]))
			{
				if (!empty($_POST["band"])) {
					$user_search = trim($_POST["band"]);
					$user_search = mysql_escape_string($user_search);
					$user_search = build_search_query($user_search, "band");
					
					$result = mysql_query($user_search);
					if ($result != false) {

						// DISPLAY LIST OF BANDS
						echo "<div class=\"tags\">";
						echo "<p id=\"mainheading\">Search Results<hr></p><br />";
						
						echo "<ul>";
								
						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
							echo "<li><a href=\"band.php?band=".$row[1]."\">".$row[1]."</a></li>";
						}
						
						echo "</ul>";
						mysql_free_result($result);

						echo "</div>";
					}
				}

				if (!empty($_POST["playlist"])) {
					$user_search = trim($_POST["playlist"]);
					$user_search = mysql_escape_string($user_search);
					$user_search = build_search_query($user_search, "playlist");
					
					$result = mysql_query($user_search);
					if ($result != false) {

						// DISPLAY LIST OF PLAYLISTS
						echo "<div class=\"tags\">";
						echo "<p id=\"mainheading\">Search Results<hr></p><br />";
						
						echo "<ul>";
								
						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
							echo "<li><a href=\"playlist.php?id=".$row[0]."\">".$row[2]."</a></li>";
						}
						
						echo "</ul>";
						mysql_free_result($result);

						echo "</div>";
					}
				}

				if (!empty($_POST["bandtag"])) {
					$tag = trim($_POST["bandtag"]);
					
					$result = mysql_query('select distinct tag from bandtag where tag like "%'.$tag.'%"');
					if ($result != false) {

						// DISPLAY LIST OF TAGS
						echo "<div class=\"tags\">";
						echo "<p id=\"mainheading\">Search Results<hr></p><br />";
						
						echo "<ul>";
								
						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
							echo "<li><a href=\"bandtag.php?tag=".$row[0]."\">".$row[0]."</a></li>";
						}
						
						echo "</ul>";
						mysql_free_result($result);

						echo "</div>";
					}
				}
				
				if (!empty($_POST["songtag"])) {
					$tag = trim($_POST["songtag"]);
					
					$result = mysql_query('select distinct tag from songtag where tag like "%'.$tag.'%"');
					if ($result != false) {

						// DISPLAY LIST OF TAGS
						echo "<div class=\"tags\">";
						echo "<p id=\"mainheading\">Search Results<hr></p><br />";
						
						echo "<ul>";
								
						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
							echo "<li><a href=\"songtag.php?tag=".$row[0]."\">".$row[0]."</a></li>";
						}
						
						echo "</ul>";
						mysql_free_result($result);

						echo "</div>";
					}
				}
				
 				if (!empty($_POST["song"])) {
					$user_search = trim($_POST["song"]);
					$user_search = mysql_escape_string($user_search);
					$user_search = build_search_query($user_search, "song");

					$result =  mysql_query($user_search);
					if ($result != false) {

						// DISPLAY LIST OF SONGS
						echo "<div class=\"tags\">";
						echo "<p id=\"mainheading\">Search Results<hr></p><br />";
						
						echo "<ul>";
						
						echo '<table border="1" width="600">
							<tr>
							<th>Song</th><th>Band</th><th>Album</th><th>Track</th><th>Year</th><th>Length</th>
							</tr>';
						
						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

							$data = get_song_by_id($row[0]);
							
							echo '<tr>
							<td valign="top"><a href="song.php?id='.$row[0].'">'.$data[0].'</a></td>
							<td valign="top"><a href="band.php?band='.$data[6].'">'.$data[6].'</a></td>
							<td valign="top">'.$data[5].'</td>
							<td valign="top">'.$data[4].'</td>
							<td valign="top">'.$data[1].'</td>
							<td valign="top">'.$data[2].':'.$data[3].'</td>
							</tr>';
							
							
						}
						echo '</table>';
						
						
						echo "</ul>";
						mysql_free_result($result);

						echo "</div>";
					}
				}
				
				echo '<form action="search.php" name="myform" method="POST">

				<button id="submit" name="" type="submit" value="Search">Search Again</button>
				 
				</form>';
			}
			else
			{
				// display search form
				echo "<div>";
					echo '<form action="search.php" name="myform" method="POST">

						<label>Search for a band:</label>
						<input name="band" type="text">
            
						<label>Search for a song:</label>
						<input name="song" type="text">

						<label>Search for a playlist:</label>
						<input name="playlist" type="text">

						<label>Search for a song tag:</label>
						<input name="songtag" type="text">
						
						<label>Search for a band tag:</label>
						<input name="bandtag" type="text">	
						
						<br><br><i>*Separate search terms with commas.</i><br>
						<button id="submit" name="submit" type="submit" value="Search">Search</button>
						 
						</form>';
					
				echo "</div>";
				
			}
		
		}
	?>

	

</div>

</body>
</html>