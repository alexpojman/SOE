<!DOCTYPE html>
<head>
	<title>SaVaTest</title>
	<style type="text/css">
		div#content{
			text-align: center;
		}

		div#canvas{
			background-color: #eee;
			-moz-box-shadow: 3px 3px 4px #999; /* Firefox/Mozilla */  
			-webkit-box-shadow: 3px 3px 4px #999; /*Safari/Chrome */  
			box-shadow: 3px 3px 4px #999; /* Opera & hoe het zou moeten */
			border: 1px solid #ccc;
			width: 90%;
			height: 600px;
			margin-left: auto;
			margin-right: auto;
		}

		div.definition{
			background-color: #CCCCCC;
			width: 100px;
			height: 50px;
			margin: 10px;
		}

		div.option{
			text-align:center;
			padding-top: 3px;
			height: 25px;
		}

		div.option:hover{
			background-color: #6FB9D6;

		}

		textarea{
			border: 1px solid #ccc;
			font-family: inherit;
			width: 170px;
			height: 60px;
		}

		div.chunk{
			background-color: #fff;
			border: 1px solid #ddd;
			font-family: Arial;
			text-align: left;
			height: 100px;
			width: 180px;
			padding: 3px;
		}

		div.active{
			border: 2px solid #B8D3DB;
		}

		div.delete{
			color: #fff;
			float: right;
			background-color: #000;
			width: 20px;

		}

		h2{
			margin: 5px;
			font-size: 16px;
		}

		p{
			font-size: 12px;
		}

		div#chapters{
			margin-left: 50px;
		}

		div.chapter{
			float: left;
			margin: 5px;
			background-color: #eee;
		}

	</style>
	<script src="http://code.jquery.com/jquery-1.8.3.js "></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js "></script>
	<script type="text/javascript">                                         
		$(document).ready(function() {
			
			var currentChapter;

			$("#canvas").click(function(e){
					placeChunk(e);
			});

			$("#addChapter").click(function(e){
				e.preventDefault();
				var name = prompt("Please give a name to the new chapter:");
				var chapterid;

				$.post("addchapter.php", {name: name, projectid: <?= $_GET['id']?>}, function(data){
					$("#chapters").append('<div class="chapter">'+name+'</div>');


				});
				
			});
		});

		$(document).keyup(function (e){
			if(e.keyCode == 13){
				deactivateChunks();
			}
		});
		
		$(document).mouseup(function (e)
		{
		    //Click outside active containers
		    var container = $(".chunk.active");
		    if (container.has(e.target).length === 0)
		    {
		        deactivateChunks();
		        
		    }
		});

		function deactivateChunks(){
			var container = $(".chunk.active");
			$(container).removeClass("active");

		        var title = $(container).find(".chunkTitle");
		        var text = $(container).find(".chunkText");
		        title.focus(); //Werkt niet...
		        $(container).children().remove();
		        
		        if(title.val()!="" || text.val()!="")
		        {
		        	$(container).append('<h2 class="title">'+title.val()+'</h2><p class="text">'+text.val()+'</p>');
		        }else{
		        	$(container).remove();//Remove empty container
		        }
		}
		
		
		function chunkClicked(e){
			e.stopPropagation();

			if($(this).hasClass("active")==false){
				var title = $(this).find(".title").html();
			    var text = $(this).find(".text").html();
			    $(this).children().remove();
			    $(this).append('<input style="text" placeholder="Title" class="chunkTitle" value="'+title+'" /><div class="delete"></div><br /><textarea placeholder="Text" class="chunkText" >'+text+'</textarea>');
			    $(this).addClass("active");
			}

		}

		function placeChunk(e){
			var x = e.pageX-100;
			var y = e.pageY-80;
			

			//Chunk toevoegen
			var newDiv = '<div class="chunk active" style="position:absolute; display:block; top: '+y+'px; left:'+x+'px;"><input style="text" placeholder="Title" class="chunkTitle" /><div class="delete"></div><br /><textarea placeholder="Text" class="chunkText" ></textarea></div>';
			$("#canvas").append(newDiv);
			$(".title").focus();

			$(".chunk").draggable();
			$(".chunk").click(chunkClicked);	
		}


		
	</script> 
	<?php
		include("connect.php");

		if(isset($_POST['projectsubmit'])){
			$projectname = $_POST['projectname'];
			$query = "INSERT INTO projects (name) VALUES ('".$projectname."')";
			mysql_query($query) or die (mysql_error()); 
						
		}else{
			if(isset($_GET['id'])){
				$result = mysql_query("SELECT name FROM projects WHERE id='".$_GET['id']."'") or die(mysql_error());
				$projectname = mysql_result($result, 0);
				
			}
		}

	?>
</head>
<body>

	<div id="content">
		<h1><?= $projectname ?></h1>
		<div id="chapters">Chapters: 
			<?php 
			$chapters = mysql_query("SELECT id, name FROM chapters WHERE projectid='".$_GET['id']."'") or die(mysql_error());
			while($row = mysql_fetch_array( $chapters )) 
				{
					echo '<div class="chapter">'.$row['name'].'</div>';
				}
			?>
		</div>
			<a href="#" id="addChapter">Add new chapter</a>
		<div id="canvas"></div>
	</div>
</body>
</html>