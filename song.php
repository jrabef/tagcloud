<html land="en">
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/likebutton.css" />

	
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
			<li><a href='playlist.php'><span>Playlists</span></a></li>
			<li class='active'><a href='band.php'><span>Bands</span></a></li>
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
	<!--- list song details  --->
	<!-------------------------->
	<?php
		//Execute code ONLY if connections were successful 	
		if ($dbSuccess) {
			
			//search for song
			if (isset($_GET["id"]))
			{
				if (!empty (htmlspecialchars($_GET["id"])))
				{
					$songid = htmlspecialchars($_GET["id"]);
					
					$result = mysql_query("select name, year, length from song where songid = ".$songid);
					$result2 = mysql_query("select albumid, trackno from albumsong where songid = ".$songid);
					
					if ($result != false && $result2 != false) {

						// display song info //
						$row = mysql_fetch_assoc($result);
						$row2 = mysql_fetch_assoc($result2);
						
						$name = $row["name"];
						$year = $row["year"];
						$lengthinsecs = $row["length"];
						$trackno = $row2["trackno"];
						$albumid = $row2["albumid"];
						
						$result3 = mysql_query("select name, bandid from album where albumid = ".$albumid);
						$row3 = mysql_fetch_assoc($result3);
						
						$albumname = $row3["name"];
						$bandid = $row3["bandid"];
						$row4 = mysql_fetch_assoc( mysql_query("select name from band where bandid = ".$bandid) );
						$bandname = $row4["name"];

						$mins = intval($lengthinsecs / 60);
						$secs = str_pad(intval($lengthinsecs % 60),2,"0",STR_PAD_LEFT);
						
						echo "<p id=\"mainheading\">".$name." (".$year.")";

						
					// TODO:: if user logged in, can like a song
					// like button
					if ($fgmembersite->CheckLogin() ) {
						echo "<div class='like_box'><a href='song.php?id=".$songid."' id='".$songid."' class='like_button'><img class='like_button' src='vote_up.png'></a></div>";
						echo "<span id='liked_msg'>Liked!</span>";
					}
					// unlike button
					if ($fgmembersite->CheckLogin() ) {
						echo "<div class='unlike_box'><a href='song.php?id=".$songid."' id='".$songid."' class='unlike_button'><img class='like_button' src='vote_down.png'></a></div>";
						echo "<span id='unliked_msg'>Disliked!</span>";
					}
					
					// handle like button clicked 
					if(!empty($_POST["like_id"]))
					{
						$like_id= trim($_POST["like_id"]);
						$like_id = mysql_escape_string($like_id);
													
						$user_qry = mysql_query('select userid from user where username = "'.$fgmembersite->getUsername().'"');
						$userid = mysql_fetch_assoc($user_qry);
						$userid = $userid["userid"];
						$uid_sql=mysql_query("select songid from songrating where songid=".$like_id." and creatorid=".$userid);
						$count=mysql_num_rows($uid_sql);

						if($count==0)
						{
							$sql_in=mysql_query("INSERT into songrating values ('".$like_id."','".$userid."',1)");
							echo "<script language=javascript>alert('Song liked.');
									</script>";
						}
						else
						{
							echo "<script language=javascript>alert('You already rated this song.');
									</script>";
						}
					}
					
					// handle unlike button clicked 
					if(!empty($_POST["unlike_id"]))
					{
						$like_id= trim($_POST["unlike_id"]);
						$like_id = mysql_escape_string($like_id);
													
						$user_qry = mysql_query('select userid from user where username = "'.$fgmembersite->getUsername().'"');
						$userid = mysql_fetch_assoc($user_qry);
						$userid = $userid["userid"];
						$uid_sql=mysql_query("select songid from songrating where songid=".$like_id." and creatorid=".$userid);
						$count=mysql_num_rows($uid_sql);

						if($count==0)
						{
							$sql_in=mysql_query("INSERT into songrating values ('".$like_id."','".$userid."',0)");
							echo "<script language=javascript>alert('Song disliked.');
									</script>";
						}
						else
						{
							echo "<script language=javascript>alert('You already rated this song.');
									</script>";
						}
					}
					?>
					
					<!--like button script-->
					<script>
					$( document ).ready(function() {
						$("#liked_msg").hide();
						$("#unliked_msg").hide();

						$(".like_button").on("click", function() {

						var id = $(".like_button").attr("id");
						var url = $(".like_button").attr("href");

							$.post(url, {like_id:id}, function(data) {
								$(".like_box").text("<strong>Liked!</strong>");	
								$("#liked_msg").show().delay(5000).fadeOut();
									
							} );

						});

					});


					$( document ).ready(function() {

						$(".unlike_button").on("click", function() {

						var unid = $(".unlike_button").attr("id");
						var url = $(".unlike_button").attr("href");

							$.post(url, {unlike_id:unid}, function(data) {
								$(".unlike_box").append("<strong>Disliked!</strong>");	
								$("#unliked_msg").show().delay(5000).fadeOut();

							} );
						});

					});
					</script>
					
					<?php
					
					echo "<hr></p><p>&nbsp;</p>";
										
					// ********add song to playlist 
					// if user logged in, can add playlist
					if( $fgmembersite->CheckLogin() ) {
						$result = mysql_query('select userid from user where username = "'.$fgmembersite->getUsername().'"');
						$row = mysql_fetch_assoc($result);
						$userid = $row["userid"];
						
						// display list of playlists
						echo '<form action="song.php?id='.$songid.'" name="myform" method="POST">';
						echo '<label>Add to playlist: </label>';
						
						$lists = mysql_query("select playlistid, name from playlist where userid = " . $userid);
						
						if ($lists != false)
						{
							echo "<select name='id' required>";
							while ($row = mysql_fetch_array($lists, MYSQL_NUM)) {;
								echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
							}
							echo "</select>";
							
							mysql_free_result($lists);
						}
						
						echo '<input id="submit" name="submit" type="submit" value="Add">
						</form>';
			
						// Button clicked, Add song to playlist
						if (!empty ($_POST["submit"]))
						{
							if (!empty($_POST["id"])) {
								$id = trim($_POST["id"]);
								$id = mysql_escape_string($id);
								
								$result = mysql_query('select max(position) from playlistsong where playlistid = '.$id);
								$nextpos = mysql_fetch_array($result, MYSQL_NUM)[0] + 1;
								$result2 = mysql_query('insert into playlistsong values ('.$id.', '.$songid.', '.$nextpos.')');
									
								if (!$result2)
									die('Invalid query: ' . mysql_error());
								else {
									echo "<script language=javascript>alert('Song added.');
									location.reload();
									</script>";
								}
							}
						}
					}
						
						echo "<br>&nbsp;<br>";
					
						echo "Found on the album:<br>&nbsp;<br>";
						
						echo '<table border="1" width="600">
						<tr>
						<th>Album</th><th>Track</th><th>Length</th>
						</tr>
						<tr>
						<td width=80%">'.$bandname.' - '.$albumname.'</td><td width="10%">'.$trackno.'</td> <td>'.$mins.':'.$secs.'</td>
						</tr>
						</table>';

					}
					
					echo "<br>&nbsp;<br>";
					echo "Found on playlists:<br>&nbsp;<br>";
					
					// list playlists with song on it
					$result = mysql_query("select distinct playlistid from playlistsong where songid = " . $songid);

					if ($result != false)
					{
						echo "<ul>";
								
						while ($findplaylist = mysql_fetch_array($result, MYSQL_NUM)) {
							$playlistid = $findplaylist[0];
							
							$result2 = mysql_query("select name, postdate from playlist where playlistid = " . $playlistid);
							$playlistdata = mysql_fetch_array($result2);
							$name = $playlistdata[0];
							$postdate = $playlistdata[1];
						
							echo "<li><a href=\"playlist.php?id=".$playlistid."\">".$name." (".$postdate.")</a></li>";
						}
						
						echo "</ul>";
						mysql_free_result($result);
						mysql_free_result($result2);
					}
					
					echo "<br>&nbsp;<br>";
					
					// display tags //
					echo "<div class=\"tags\">";
					$result = mysql_query("select count(*) as total from songtag where songid = " . $songid);
						
					if ($result != false) {
							$row = mysql_fetch_assoc($result);
							$total = $row["total"];
							
							echo "<h2><span id=\"tag-heading\"><p>Tags</p></span></h2>";
								
							mysql_free_result($result);
								
							$tags = mysql_query("select tag, count(*) as tagcount from songtag where songid = ".$songid." group by tag");
							
							if ($tags != false) {
								echo "<ul>";
								while ($row = mysql_fetch_array($tags, MYSQL_NUM)) {
									
									if ( ($row[1] / $total ) < .02 )
										$class = 'smallest';

									else if ( ($row[1] / $total ) < .07 )
										$class = 'smaller';

									else if ( ($row[1] / $total ) < .10 )
										$class = 'small';

									else if ( ($row[1] / $total ) < .18 )
										$class = 'medium';

									else if ( ($row[1] / $total ) < .23 )
										$class = 'large';

									else 
										$class = 'largest';
										
										echo '<li class="'.$class.'"><a href="songtag.php?tag='.$row[0].'">'.$row[0].'</a></li>';
							
								}	
								echo "</ul>";
								mysql_free_result($tags);
							}
					}					
					echo "</div>";
						
					// if user logged in, can add a tag
					if( $fgmembersite->CheckLogin() ) {
					
						// display add tag form
						echo '<form action="song.php?id='.$_GET["id"].'" name="myform" method="POST">
						
							<label>Add tag: </label>
							<input name="tag" type="text" required>
				
							<input id="submit" name="submit" type="submit" value="Add">
							 
							</form>';
					
						// Button clicked, Add a tag
						if (!empty ($_POST["submit"]))
						{
							if (!empty($_POST["tag"])) {
								$tag = $_POST["tag"];
								
								$result = mysql_query('insert into songtag (songid, tag, creatorid) values ('.$songid.',"'.$tag.'",'.$userid.')');
									
								if (!$result)
									die('Invalid query: ' . mysql_error());
								else {
									echo "<script language=javascript>alert('Tag added.');
									location.reload();
									</script>";
								}
							}
						}
					}
											
					echo "<br><br>";
					
					
					
			}
			else
			{
					echo "Song not found.";
			}
			
		}
		
		else
		{
			// DISPLAY LIST OF SONGS
/* 			echo "<div class=\"tags\">";
			$result = mysql_query("select name from band");
			
				if ($result != false) {
					echo "<p id=\"mainheading\">Song List<hr></p><p>&nbsp;</p>";
					
					echo "<ul>";
							
					while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
						echo "<li><a href=\"band.php?band=".$row[0]."\">".$row[0]."</a></li>";
					}
					
					echo "</ul>";
					mysql_free_result($result);
				}
			echo "</div>"; */
		}
			
			
	}
	?>

	

</div>

</body>
</html>


