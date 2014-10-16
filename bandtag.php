<html land="en">
<head>
    <meta charset="utf-8">
	<?php include_once 'includes.php'; ?>
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
			<li><a href='band.php'><span>Bands</span></a></li>
				<ul>
					<li class='active'><a href='bandtag.php'><span>Band Tags</span></a></li>
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

	<?php
	/*
	*list band tag page*
	*/
		include 'connect.php';
		
		//Execute code ONLY if connections were successful 	
		if ($dbSuccess) {
			
			if (isset($_GET["tag"]))
			{
				if (!empty (htmlspecialchars($_GET["tag"])))
				{
					$tag = htmlspecialchars($_GET["tag"]);
					
					if (isset($_GET["userid"]))
					{
						$userid = $_GET["userid"];
						// display list of bands user tagged with $tag
						$bandids = mysql_query('select bandid, count(*) as tagcount from bandtag where tag like "'.$tag.'" and creatorid = '.$userid.' group by bandid order by tagcount desc');
						
						if ($bandids != false) {
						
							echo "<p id=\"mainheading\">".$tag."<hr></p><p>&nbsp;</p>";
							echo "<ul>";
									
							while ($row = mysql_fetch_array($bandids, MYSQL_NUM)) {
							
								$bandname = mysql_fetch_assoc(  mysql_query("select name from band where bandid = ".$row[0])  );
								echo "<li><a href=\"band.php?band=".$bandname["name"]."\">".$bandname["name"]."</a> - Tagged ".$row[1]." times</li>";
							}
							
							echo "</ul>";
							mysql_free_result($bandids);
						}
					}
					else 
					{
						// display list of bands with $tag
						$bandids = mysql_query('select bandid, count(*) as tagcount from bandtag where tag like "'.$tag.'" group by bandid order by tagcount desc');
						
						if ($bandids != false) {
						
							echo "<p id=\"mainheading\">".$tag."<hr></p><p>&nbsp;</p>";
							echo "<ul>";
									
							while ($row = mysql_fetch_array($bandids, MYSQL_NUM)) {
							
								$bandname = mysql_fetch_assoc(  mysql_query("select name from band where bandid = ".$row[0])  );
								echo "<li><a href=\"band.php?band=".$bandname["name"]."\">".$bandname["name"]."</a> - Tagged ".$row[1]." times</li>";
							}
							
							echo "</ul>";
							mysql_free_result($bandids);
						}
						
					}
				}
				else
				{
					echo "Tag not found.";
				}
			}
			else
			{	
				// get tags //
				echo "<div class=\"tags\">";
				$result = mysql_query("select count(*) as total from bandtag");
				
				if ($result != false) {
					$row = mysql_fetch_assoc($result);
					$total = $row["total"];
					
					echo "<p id=\"mainheading\">Band Tags<hr></p><br>";
						
					mysql_free_result($result);
						
					$tags = mysql_query("select tag, count(*) as tagcount from bandtag group by tag");
					
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
								
								echo '<li class="'.$class.'"><a href="bandtag.php?tag='.$row[0].'">'.$row[0].'</a></li>';
					
						}	
						echo "</ul>";
						mysql_free_result($tags);
					}
					
					
				}
				echo "</div>";
						
			}
		
		}
	?>

	

</div>

</body>
</html>