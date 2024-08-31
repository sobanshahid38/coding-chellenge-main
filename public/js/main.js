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
           getRequests('sent');
          console.log('Sent Requests selected');
          // Add your AJAX call or other logic here
      } else if ($('#btnradio3').is(':checked')) {
        getRequests('received');
          console.log('Received Requests selected');
          // Add your AJAX call or other logic here
      } else if ($('#btnradio4').is(':checked')) {
          // Action for Connections
          getConnections();
          console.log('Connections selected');
          // Add your AJAX call or other logic here
      }
  });
});



function getRequests(mode) {
  $('#content').addClass('d-none')

  $('#connections_in_common_skeleton').removeClass('d-none')
  var functionsOnSuccess = [
    [exampleOnSuccessFunction, ['varibale', 'response']]
  ];
  ajax('/sent-requests?mode='+mode, 'GET', functionsOnSuccess);

}

function getMoreRequests(mode) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnections() {
  $('#content').addClass('d-none')

  $('#connections_in_common_skeleton').removeClass('d-none')
  var functionsOnSuccess = [
    [exampleOnSuccessFunction, ['varibale', 'response']]
  ];
  ajax('/connections', 'GET', functionsOnSuccess);
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
  $('#content').addClass('d-none')

  $('#connections_in_common_skeleton').removeClass('d-none')
  var functionsOnSuccess = [
    [exampleOnSuccessFunction, ['varibale', 'response']]
  ];
  ajax('/suggestions', 'GET', functionsOnSuccess);

}

function getMoreSuggestions() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function sendRequest(userId, suggestionId) {
  var functionsOnSuccess = [
    [connectRequestOnSuccess, [suggestionId,'response']]
  ];
  var form = new FormData;
  form.append('suggestion_id',suggestionId);
  form.append('user_id',userId);

  ajax('/connect', 'POST', functionsOnSuccess,form);
}

function deleteRequest(userId, requestId) {
  var functionsOnSuccess = [
    [RequestOnSuccess, [requestId,'response']]
  ];
  var form = new FormData;
  form.append('request_id',requestId);
  form.append('user_id',userId);

  ajax('/withdraw-request', 'POST', functionsOnSuccess,form);
}

function acceptRequest(userId, requestId) {
  var functionsOnSuccess = [
    [RequestOnSuccess, [requestId,'response']]
  ];
  var form = new FormData;
  form.append('request_id',requestId);
  form.append('user_id',userId);

  ajax('/accept-request', 'POST', functionsOnSuccess,form);

}

function removeConnection(userId, connectionId) {
  // your code here...
}

function connectRequestOnSuccess(suggestionId,response) {
  $('#suggestion-' + suggestionId).remove();
 
}

function RequestOnSuccess(requestId,response) {
  if (response == 'success') {
    $('#request-' + requestId).remove();
  }
}



$(function () {
  getSuggestions();

  $(document).on('click', '#load_more_btn', function() {
    var button = $(this);
    var nextPageUrl = button.data('next-page');
    $('#connections_in_common_skeleton').removeClass('d-none')


    $.ajax({
        url: nextPageUrl,
        type: 'GET',
        success: function(data) {
            $('#suggestions-container').append(data.content);
            $('#connections_in_common_skeleton').addClass('d-none')

            if (data.next_page) {
                button.data('next-page', data.next_page);
            } else {
                button.remove(); // Remove the button if there are no more pages
            }
        },
        error: function() {
            alert('Could not load more suggestions. Please try again later.');
        }
    });
});

});