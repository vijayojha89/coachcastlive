<?php 
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\file\FileInput;
$gnl = new \common\components\GeneralComponent();
$this->title = "Video Call";
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;


// Required for all Twilio access tokens
$twilioAccountSid = "AC2a98af900eacd0fb79e11ecb1648d225";
$twilioApiKey = "SKf47eb882faef24a65a67713b8fe41647";
$twilioApiSecret = "oJLARbEtcujL8jlNESDXq5v276zWchWd";

// A unique identifier for this user
$identity = "alice_".uniqid();
// The specific Room we'll allow the user to access
$roomName = 'DailyStandup1';


// Create access token, which we will serialize and send to the client
$token = new AccessToken($twilioAccountSid, $twilioApiKey, $twilioApiSecret, 3600, $identity);

// Create Video grant
$videoGrant = new VideoGrant();
$videoGrant->setRoom($roomName);

// Add grant to token
$token->addGrant($videoGrant);


if(\Yii::$app->user->identity->role == 'user')
{
    echo $this->render('//user/_user_header.php');
}
else if(\Yii::$app->user->identity->role == 'trainer')
{
    echo $this->render('//trainer/_trainer_header.php');
}

?>  


<div class="inner_container">
    <section class="contentsection">
	<div class="row">
        <div class="col-md-6"><div id="remote-media"></div></div>
        <div class="col-md-6"><div id="local-media"></div></div>
      </div>
	

<div id="controls">
  <div id="preview">
    
    <button id="button-preview" style="display:none;">Preview My Camera</button>
  </div>
  <div id="room-controls" style="visibility:hidden;">
    <button id="button-join">Join Room</button>
    <button id="button-leave">Leave Room</button>
  </div>
  <div id="log" style="display:none;"></div>
</div>
		<div style="clear:both;"></div>
    </section>
</div>
<?php  $this->registerJsFile('//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js', [yii\web\JqueryAsset::className()]); ?> 
<?php 
      $this->registerJs("   
    $('#button-join').trigger('click');
             





",\yii\web\VIEW::POS_READY);?>
<script type="text/javascript">

var activeRoom;
var previewTracks;
var identity;
var roomName;

// Attach the Tracks to the DOM.
function attachTracks(tracks, container) {
  tracks.forEach(function(track) {
    container.appendChild(track.attach());
  });
}

// Attach the Participant's Tracks to the DOM.
function attachParticipantTracks(participant, container) {
  var tracks = Array.from(participant.tracks.values());
  attachTracks(tracks, container);
}

// Detach the Tracks from the DOM.
function detachTracks(tracks) {
  tracks.forEach(function(track) {
    track.detach().forEach(function(detachedElement) {
      detachedElement.remove();
    });
  });
}

// Detach the Participant's Tracks from the DOM.
function detachParticipantTracks(participant) {
  var tracks = Array.from(participant.tracks.values());
  detachTracks(tracks);
}

// When we are about to transition away from this page, disconnect
// from the room, if joined.
window.addEventListener('beforeunload', leaveRoomIfJoined);


  identity = "Alice";
  document.getElementById('room-controls').style.display = 'block';
  
  // Bind button to join Room.
  document.getElementById('button-join').onclick = function() {
    roomName = ("<?php echo $roomName; ?>");
    if (!roomName) {
      alert('Please enter a room name.');
      return;
    }

    log("Joining room '" + roomName + "'...");
    var connectOptions = {
      name: roomName,
      logLevel: 'debug'
    };

    if (previewTracks) {
      connectOptions.tracks = previewTracks;
    }

    // Join the Room with the token from the server and the
    // LocalParticipant's Tracks.
    Twilio.Video.connect("<?php echo $token; ?>", connectOptions).then(roomJoined, function(error) {
      log('Could not connect to Twilio: ' + error.message);
    });
  };

  // Bind button to leave Room.
  document.getElementById('button-leave').onclick = function() {
    log('Leaving room...');
    activeRoom.disconnect();
  };


// Successfully connected!
function roomJoined(room) {
  window.room = activeRoom = room;

  log("Joined as '" + identity + "'");
  document.getElementById('button-join').style.display = 'none';
  document.getElementById('button-leave').style.display = 'inline';

  // Attach LocalParticipant's Tracks, if not already attached.
  var previewContainer = document.getElementById('local-media');
  if (!previewContainer.querySelector('video')) {
    attachParticipantTracks(room.localParticipant, previewContainer);
  }

  // Attach the Tracks of the Room's Participants.
  room.participants.forEach(function(participant) {
    log("Already in Room: '" + participant.identity + "'");
    var previewContainer = document.getElementById('remote-media');
    attachParticipantTracks(participant, previewContainer);
  });

  // When a Participant joins the Room, log the event.
  room.on('participantConnected', function(participant) {
    log("Joining: '" + participant.identity + "'");
  });

  // When a Participant adds a Track, attach it to the DOM.
  room.on('trackAdded', function(track, participant) {
    log(participant.identity + " added track: " + track.kind);
    var previewContainer = document.getElementById('remote-media');
    attachTracks([track], previewContainer);
  });

  // When a Participant removes a Track, detach it from the DOM.
  room.on('trackRemoved', function(track, participant) {
    log(participant.identity + " removed track: " + track.kind);
    detachTracks([track]);
  });

  // When a Participant leaves the Room, detach its Tracks.
  room.on('participantDisconnected', function(participant) {
    log("Participant '" + participant.identity + "' left the room");
    detachParticipantTracks(participant);
  });

  // Once the LocalParticipant leaves the room, detach the Tracks
  // of all Participants, including that of the LocalParticipant.
  room.on('disconnected', function() {
    log('Left');
    if (previewTracks) {
      previewTracks.forEach(function(track) {
        track.stop();
      });
    }
    detachParticipantTracks(room.localParticipant);
    room.participants.forEach(detachParticipantTracks);
    activeRoom = null;
    document.getElementById('button-join').style.display = 'inline';
    document.getElementById('button-leave').style.display = 'none';
  });
}

// Preview LocalParticipant's Tracks.
document.getElementById('button-preview').onclick = function() {
  var localTracksPromise = previewTracks
    ? Promise.resolve(previewTracks)
    : Twilio.Video.createLocalTracks();

  localTracksPromise.then(function(tracks) {
    window.previewTracks = previewTracks = tracks;
    var previewContainer = document.getElementById('local-media');
    if (!previewContainer.querySelector('video')) {
      attachTracks(tracks, previewContainer);
    }
  }, function(error) {
    console.error('Unable to access local media', error);
    log('Unable to access Camera and Microphone');
  });
};

// Activity log.
function log(message) {
  var logDiv = document.getElementById('log');
  logDiv.innerHTML += '<p>&gt;&nbsp;' + message + '</p>';
  logDiv.scrollTop = logDiv.scrollHeight;
}

// Leave Room.
function leaveRoomIfJoined() {
  if (activeRoom) {
    activeRoom.disconnect();
  }
}

</script>
<style>
div#remote-media {
  height: 43%;
  width: 100%;
  background-color: #fff;
  text-align: center;
  margin: auto;
}

div#remote-media video {
  border: 1px solid #272726;
  /*margin: 3em 2em;
  height: 70%;
  max-width: 27% !important;
  */
  background-color: #272726;
  background-repeat: no-repeat;
  width:100%;
  height:500px;
}

div#controls {
  /*padding: 3em;*/
  max-width: 1200px;
  margin: 0 auto;
}

div#controls div {
  float: left;
}

div#controls div#room-controls,
div#controls div#preview {
  width: 16em;
  /*margin: 0 1.5em;*/
  text-align: center;
}

div#controls p.instructions {
  text-align: left;
  margin-bottom: 1em;
  font-family: Helvetica-LightOblique, Helvetica, sans-serif;
  font-style: oblique;
  font-size: 1.25em;
  color: #777776;
}

div#controls button {
  width: 15em;
  /*height: 2.5em;*/
  /*margin-top: 1.75em;*/
  border-radius: 1em;
  font-family: "Helvetica Light", Helvetica, sans-serif;
  font-size: .8em;
  font-weight: lighter;
  outline: 0;
}

div#controls div#room-controls input {
  font-family: Helvetica-LightOblique, Helvetica, sans-serif;
  font-style: oblique;
  font-size: 1em;
}

div#controls button:active {
  position: relative;
  top: 1px;
}

div#controls div#preview div#local-media {
  width: 270px;
  height: 202px;
  border: 1px solid #cececc;
  box-sizing: border-box;
  background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+Cjxzdmcgd2lkdGg9IjgwcHgiIGhlaWdodD0iODBweCIgdmlld0JveD0iMCAwIDgwIDgwIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnNrZXRjaD0iaHR0cDovL3d3dy5ib2hlbWlhbmNvZGluZy5jb20vc2tldGNoL25zIj4KICAgIDwhLS0gR2VuZXJhdG9yOiBTa2V0Y2ggMy4zLjEgKDEyMDAyKSAtIGh0dHA6Ly93d3cuYm9oZW1pYW5jb2RpbmcuY29tL3NrZXRjaCAtLT4KICAgIDx0aXRsZT5GaWxsIDUxICsgRmlsbCA1MjwvdGl0bGU+CiAgICA8ZGVzYz5DcmVhdGVkIHdpdGggU2tldGNoLjwvZGVzYz4KICAgIDxkZWZzPjwvZGVmcz4KICAgIDxnIGlkPSJQYWdlLTEiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIHNrZXRjaDp0eXBlPSJNU1BhZ2UiPgogICAgICAgIDxnIGlkPSJjdW1tYWNrIiBza2V0Y2g6dHlwZT0iTVNMYXllckdyb3VwIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMTU5LjAwMDAwMCwgLTE3NDYuMDAwMDAwKSIgZmlsbD0iI0ZGRkZGRiI+CiAgICAgICAgICAgIDxnIGlkPSJGaWxsLTUxLSstRmlsbC01MiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMTU5LjAwMDAwMCwgMTc0Ni4wMDAwMDApIiBza2V0Y2g6dHlwZT0iTVNTaGFwZUdyb3VwIj4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0zOS42ODYsMC43MyBDMTcuODUsMC43MyAwLjA4NSwxOC41IDAuMDg1LDQwLjMzIEMwLjA4NSw2Mi4xNyAxNy44NSw3OS45MyAzOS42ODYsNzkuOTMgQzYxLjUyMiw3OS45MyA3OS4yODcsNjIuMTcgNzkuMjg3LDQwLjMzIEM3OS4yODcsMTguNSA2MS41MjIsMC43MyAzOS42ODYsMC43MyBMMzkuNjg2LDAuNzMgWiBNMzkuNjg2LDEuNzMgQzYxLjAwNSwxLjczIDc4LjI4NywxOS4wMiA3OC4yODcsNDAuMzMgQzc4LjI4Nyw2MS42NSA2MS4wMDUsNzguOTMgMzkuNjg2LDc4LjkzIEMxOC4zNjcsNzguOTMgMS4wODUsNjEuNjUgMS4wODUsNDAuMzMgQzEuMDg1LDE5LjAyIDE4LjM2NywxLjczIDM5LjY4NiwxLjczIEwzOS42ODYsMS43MyBaIiBpZD0iRmlsbC01MSI+PC9wYXRoPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTQ3Ljk2LDUzLjMzNSBMNDcuOTYsNTIuODM1IEwyMC4wOTMsNTIuODM1IEwyMC4wOTMsMjcuODI1IEw0Ny40NiwyNy44MjUgTDQ3LjQ2LDM4LjI1NSBMNTkuMjc5LDMwLjgwNSBMNTkuMjc5LDQ5Ljg1NSBMNDcuNDYsNDIuNDA1IEw0Ny40Niw1My4zMzUgTDQ3Ljk2LDUzLjMzNSBMNDcuOTYsNTIuODM1IEw0Ny45Niw1My4zMzUgTDQ4LjQ2LDUzLjMzNSBMNDguNDYsNDQuMjE1IEw2MC4yNzksNTEuNjY1IEw2MC4yNzksMjguOTk1IEw0OC40NiwzNi40NDUgTDQ4LjQ2LDI2LjgyNSBMMTkuMDkzLDI2LjgyNSBMMTkuMDkzLDUzLjgzNSBMNDguNDYsNTMuODM1IEw0OC40Niw1My4zMzUgTDQ3Ljk2LDUzLjMzNSIgaWQ9IkZpbGwtNTIiPjwvcGF0aD4KICAgICAgICAgICAgPC9nPgogICAgICAgIDwvZz4KICAgIDwvZz4KPC9zdmc+);
  background-position: center;
  background-repeat: no-repeat;
  margin: 0 auto;
}

div#controls div#preview div#local-media video {
  max-width: 100%;
  max-height: 100%;
  border: none;
}

div#controls div#preview button#button-preview {
  background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+Cjxzdmcgd2lkdGg9IjE3cHgiIGhlaWdodD0iMTJweCIgdmlld0JveD0iMCAwIDE3IDEyIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnNrZXRjaD0iaHR0cDovL3d3dy5ib2hlbWlhbmNvZGluZy5jb20vc2tldGNoL25zIj4KICAgIDwhLS0gR2VuZXJhdG9yOiBTa2V0Y2ggMy4zLjEgKDEyMDAyKSAtIGh0dHA6Ly93d3cuYm9oZW1pYW5jb2RpbmcuY29tL3NrZXRjaCAtLT4KICAgIDx0aXRsZT5GaWxsIDM0PC90aXRsZT4KICAgIDxkZXNjPkNyZWF0ZWQgd2l0aCBTa2V0Y2guPC9kZXNjPgogICAgPGRlZnM+PC9kZWZzPgogICAgPGcgaWQ9IlBhZ2UtMSIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCIgc2tldGNoOnR5cGU9Ik1TUGFnZSI+CiAgICAgICAgPGcgaWQ9ImN1bW1hY2siIHNrZXRjaDp0eXBlPSJNU0xheWVyR3JvdXAiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0xMjUuMDAwMDAwLCAtMTkwOS4wMDAwMDApIiBmaWxsPSIjMEEwQjA5Ij4KICAgICAgICAgICAgPHBhdGggZD0iTTEzNi40NzEsMTkxOS44NyBMMTM2LjQ3MSwxOTE5LjYyIEwxMjUuNzY3LDE5MTkuNjIgTDEyNS43NjcsMTkxMC4wOCBMMTM2LjIyMSwxOTEwLjA4IEwxMzYuMjIxLDE5MTQuMTUgTDE0MC43ODUsMTkxMS4yNyBMMTQwLjc4NSwxOTE4LjQyIEwxMzYuMjIxLDE5MTUuNTUgTDEzNi4yMjEsMTkxOS44NyBMMTM2LjQ3MSwxOTE5Ljg3IEwxMzYuNDcxLDE5MTkuNjIgTDEzNi40NzEsMTkxOS44NyBMMTM2LjcyMSwxOTE5Ljg3IEwxMzYuNzIxLDE5MTYuNDUgTDE0MS4yODUsMTkxOS4zMyBMMTQxLjI4NSwxOTEwLjM3IEwxMzYuNzIxLDE5MTMuMjQgTDEzNi43MjEsMTkwOS41OCBMMTI1LjI2NywxOTA5LjU4IEwxMjUuMjY3LDE5MjAuMTIgTDEzNi43MjEsMTkyMC4xMiBMMTM2LjcyMSwxOTE5Ljg3IEwxMzYuNDcxLDE5MTkuODciIGlkPSJGaWxsLTM0IiBza2V0Y2g6dHlwZT0iTVNTaGFwZUdyb3VwIj48L3BhdGg+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4=)1em center no-repeat #fff;
  border: none;
  padding-left: 1.5em;
}

div#controls div#log {
  border: 1px solid #686865;
}

div#controls div#room-controls {
  display: none;
}

div#controls div#room-controls input {
  width: 100%;
  height: 2.5em;
  padding: .5em;
  display: block;
}

div#controls div#room-controls button {
  color: #fff;
  background: 0 0;
  border: 1px solid #686865;
}

div#controls div#room-controls button#button-leave {
  display: none;
}

div#controls div#log {
  width: 35%;
  height: 9.5em;
  margin-top: 2.75em;
  text-align: left;
  padding: 1.5em;
  float: right;
  overflow-y: scroll;
}

div#controls div#log p {
  color: #686865;
  font-family: 'Share Tech Mono', 'Courier New', Courier, fixed-width;
  font-size: 1.25em;
  line-height: 1.25em;
  margin-left: 1em;
  text-indent: -1.25em;
  width: 90%;
}
#local-media video{
	height:500px;
}	
</style>