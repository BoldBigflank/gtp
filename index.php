<?php
// Pick an image from the images directory
$images = scandir('images');
// Beware . and .. files
$fp = "images/" . $images[rand(2, count($images)-1)];
$image = "<image class='image' src='$fp' alt=''></image>";

?><html>
<head>
	<title>Get the Picture</title>
	<link href='styles.css' rel='stylesheet' type='text/css'>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
</head>
<body>
	<body>
	<div id="header-bar"><span style="padding: 5px">Get the Picture</span></div>
	<div id="page-wrapper">
		<div class="image-wrapper">
			<span class="image-border"><?php echo $image; ?></span>
		</div>
		<div class="instructions-wrapper">
			<span class="instructions">Text your caption for this image.  Text the number of the best entry to vote!</span>
		</div>
		<div class="instructions-wrapper">
			Entries: <span id="entries-count" class="instructions">0</span>
			<button onClick="$('#entries-table').show()">Show Entries</button>
		Votes: <span id="votes-count" class="instructions">0</span>
		<button onClick="$('.votes').show(); $('.author').show()">Show Votes</button>
		</div>
		<div class="table-wrapper">
			<table id="entries-table" style="display: none;">
				<thead class="grad"><tr><th>ID</th><th>Phrase</th><th>Votes</th></tr></thead>
				<tbody id="entries-tbody"></tbody>
			</table>
		</div>
		<button onClick="var loc = window.location.href; window.location = './index.php';">New image</button>
	</div>
	<div class="smsBox">
		<span id="smsTitle">SMS Simulator</span><br>
		<input id="smsBody" type="text" /><br>
		<button id="smsSubmit" onClick="sendSMS()">Send SMS</button><br>
		<span id="smsResponse"></span>
	</div>
	<script>
	function sendSMS(){
		var Body = $("#smsBody").val();
		var From = "web" + Math.floor(Math.random()*100);
		$.ajax({
		  url: "sms.php?From=" + From + "&Body=" + encodeURIComponent(Body),
		  context: document.body,
		  success: function(data){
			  console.log(data);
			  $("#smsBody").val("")
			  $("#smsResponse").html(data)
		  }
		});
	}
	</script>
	<div pub-key="pub-key" sub-key="sub-key" ssl="off" origin="pubsub.pubnub.com" id="pubnub"></div>
	<script src="http://cdn.pubnub.com/pubnub-3.1.min.js"></script>
	<script>(function(){

	    // LISTEN FOR MESSAGES
	    PUBNUB.subscribe({
		    channel  : "image",
		    callback : function(message) { 
		    	if(message.vote){
		    		// Increment the entry by 1
		    		$('#votes' + message.vote).text(parseInt($('#votes' + message.vote).text()) + 1)

		    		// Increment total votes by 1
					$('#votes-count').text(parseInt($('#votes-count').text()) + 1)

		    	}
		    	if(message.entry){
		    		var row = $('#entries-tbody').children().length + 1;
		    		// Add another line to the bottom of the entries
		    		$('#entries-table > tbody:last').append('<tr><td>' + row + '</td><td><span style="display: none; color: blue;" class="author">' + message.author + ': </span>' + message.entry + '</td><td><span style="display: none" class="votes" id="votes' + row + '">0</span></td></tr>');
					$('#entries-count').text(parseInt($('#entries-count').text()) + 1)
		    	}
				$("#phrases").html(message.phrases)
			}
		})
	})();</script>
</body>
</body>
</html>