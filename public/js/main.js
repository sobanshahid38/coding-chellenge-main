var skeletonId = 'skeleton';
var contentId = 'content';
var skipCounter = 0;
var takeAmount = 10;

$(document).ready(function() {
  $('input[name="btnradio"]').change(function() {
      if ($('#btnradio1').is(':checked')) {
          // Action for Suggestions
          console.log('Suggestions selected');
          getSuggestions();
          // Add your AJAX call or other logic here
      } else if ($('#btnradio2').is(':checked')) {
          // Action for Sent Requests
          console.log('Sent Requests selected');
          // Add your AJAX call or other logic here
      } else if ($('#btnradio3').is(':checked')) {
          // Action for Received Requests
          console.log('Received Requests selected');
          // Add your AJAX call or other logic here
      } else if ($('#btnradio4').is(':checked')) {
          // Action for Connections
          console.log('Connections selected');
          // Add your AJAX call or other logic here
      }
  });
});



function getRequests(mode) {
  // your code here...
}

function getMoreRequests(mode) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnections() {
  // your code here...
}

function getMoreConnections() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnectionsInCommon(userId, connectionId) {
  // your code here...
}

function getMoreConnectionsInCommon(userId, connectionId) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getSuggestions() {
  var functionsOnSuccess=[];
  ajax('/suggestions', 'POST', functionsOnSuccess);

}

function getMoreSuggestions() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function sendRequest(userId, suggestionId) {
  // your code here...
}

function deleteRequest(userId, requestId) {
  // your code here...
}

function acceptRequest(userId, requestId) {
  // your code here...
}

function removeConnection(userId, connectionId) {
  // your code here...
}

$(function () {
  //getSuggestions();
});