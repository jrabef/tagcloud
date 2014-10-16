<html land="en">
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/likebutton.css" />
	<script type="text/javascript" src="like_message.js"></script>
	
	<?php include_once 'includes.php';
		  include 'connect.php';
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
			<li><a href='search.php'><span>Search</span></a></li>
			<li class='active'><a href='playlist.php'><span>Playlists</span></a></li>
			<li><a href='band.php'><span>Bands</span></a></li>
				<ul>
					<li><a href='bandtag.php'><span>Band Tags</span></a></li>
					<li><a href='songtag.php'><span>Song Tags</span></a></li>
				</ul>
		</ul>
	</div>
	
	<!--- show user menu --->
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

	<!-------------------------->
	<!--- list band details  --->
	<!-------------------------->
	<?php
		//Execute code ONLY if connections were successful 	
		if ($dbSuccess) {
			
			//search for list
			if (isset($_GET["id"]))
			{
				if (!empty (htmlspecialchars($_GET["id"])))
				{
					$playlistid = htmlspecialchars($_GET["id"]);
					
					$result2 = mysql_query("select name, postdate from playlist where playlistid = " . $playlistid);
					$playlistdata = mysql_fetch_array($result2);
					$name = $playlistdata[0];
					$postdate = $playlistdata[1];
					
					echo "<br>&nbsp;<br><h2><span id=\"tag-heading\"><p>".$name." (".$postdate.")</p></span></h2>";
					
					$result = mysql_query("select songid, position from playlistsong where playlistid = " . $playlistid . " order by position");
			
					if ($result != false) {
							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								$songid = $row[0];
								$num = $row[1];
								
								// add songs
								$songs = mysql_query("select name, length from song where songid = ".$songid);

								if ($songs != false) {
										while ($songrow = mysql_fetch_array($songs, MYSQL_NUM)) {
											$name = $songrow[0];
											$lengthinsecs = $songrow[1];
											
											$mins = intval($lengthinsecs / 60);
											$secs = str_pad(intval($lengthinsecs % 60),2,"0",STR_PAD_LEFT);

											
											echo '<table border="1" width="500">
											<tr>
											<th>Track</th><th>Title</th><th>Length</th>
											</tr>
											<tr>
											<td width="10%">'.$num.'</td><td width="80%">  <a href="song.php?id='.$songid.'">'.$name.'</a> </td> <td>'.$mins.':'.$secs.'</td>
											</tr>
											</table>';
										}	

								}
								
							}	
							echo "</ul><br>&nbsp;<br>";
							mysql_free_result($result);
							mysql_free_result($result2);
					}			

				}
				else
					echo "Playlist not found";
			}
			else
			{
				// display list of all playlists
				echo 'Playlists<hr>';
				$lists = mysql_query("select playlistid, name, userid from playlist");

				echo '<form action="login-home.php" name="myform" method="POST">';
				
				if ($lists != false)
				{
					while ($row = mysql_fetch_array($lists, MYSQL_NUM)) {;
						$result = mysql_query('select username from user where userid = '.$row[2]);
						$row2 = mysql_fetch_assoc($result);
						$user = $row2["username"];

						echo "<a href=\"playlist.php?id=".$row[0]."\">".$row[1]."</a> by ".$user."<br />";
						

					}
					mysql_free_result($lists);
					mysql_free_result($result);
				}
			}
			
		}	
	?>

	

</div>

</body>
</html>


