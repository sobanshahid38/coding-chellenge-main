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
  var button = $('#load_more_btn');
  var nextPageUrl = button.data('next-page');
  
  // Show loading skeletons
  $('#connections_in_common_skeleton').removeClass('d-none');

  $.ajax({
      url: nextPageUrl,
      type: 'GET',
      data:{mode:mode},
      success: function(data) {
          // Append the new connections to the container
          $('#load_more_btn_parent').remove();
          $('#sent-requests-container').append(data.content);
          
          // Hide the loading skeletons
          $('#connections_in_common_skeleton').addClass('d-none');

          // Check if there is a next page
         
      },
      error: function() {
          alert('Could not load more connections. Please try again later.');
      }
  });
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
  var button = $('#load_more_btn');
  var nextPageUrl = button.data('next-page');
  
  // Show loading skeletons
  $('#connections_in_common_skeleton').removeClass('d-none');

  $.ajax({
      url: nextPageUrl,
      type: 'GET',
      success: function(data) {
          // Append the new connections to the container
          $('#load_more_btn_parent').remove();
          $('#connections-container').append(data.content);
          
          // Hide the loading skeletons
          $('#connections_in_common_skeleton').addClass('d-none');

          // Check if there is a next page
         
      },
      error: function() {
          alert('Could not load more connections. Please try again later.');
      }
  });
}


function getConnectionsInCommon(userId, connectionId) {
  var functionsOnSuccess = [
    [commonConnectionOnSuccess, [connectionId,'response']]
  ];
  var form = new FormData;
  form.append('connection_id',connectionId);
  form.append('user_id',userId);

  ajax('/common-connections', 'POST', functionsOnSuccess,form);
}

function getMoreConnectionsInCommon(userId, connectionId) {
  var button = $('#load_more_btn_common_'+connectionId);
  var nextPageUrl = button.data('next-page');
 

  // Show loading skeletons
  $('#connections_in_common_skeleton').removeClass('d-none');

  $.ajax({
    url: nextPageUrl,
    type: 'POST',
    data: {
      connection_id: connectionId,
      _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
    },
    success: function(data) {
      // Append the new connections to the container
      $('#load_more_btn_parent_'+connectionId).remove();
      $('#content_' + connectionId).append(data.content);

      // Hide the loading skeletons
      $('#connections_in_common_skeleton').addClass('d-none');
    },
    error: function() {
      alert('Could not load more connections. Please try again later.');
    }
  });
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
  var button = $('#load_more_btn');
  var nextPageUrl = button.data('next-page');
  
  // Show loading skeletons
  $('#connections_in_common_skeleton').removeClass('d-none');

  $.ajax({
      url: nextPageUrl,
      type: 'GET',
      success: function(data) {
          // Append the new connections to the container
          $('#load_more_btn_parent').remove();
          $('#suggestions-container').append(data.content);
          
          // Hide the loading skeletons
          $('#connections_in_common_skeleton').addClass('d-none');

          // Check if there is a next page
         
      },
      error: function() {
          alert('Could not load more connections. Please try again later.');
      }
  });
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
  var functionsOnSuccess = [
    [connectionOnSuccess, [connectionId,'response']]
  ];
  var form = new FormData;
  form.append('request_id',connectionId);
  form.append('user_id',userId);

  ajax('/withdraw-request', 'POST', functionsOnSuccess,form);
}

function connectRequestOnSuccess(suggestionId,response) {
  $('#suggestion-' + suggestionId).remove();
 
}

function RequestOnSuccess(requestId,response) {
  if (response == 'success') {
    $('#request-' + requestId).remove();
  }
}

function connectionOnSuccess(connectionId,response) {
  if (response == 'success') {
    $('#connection-' + connectionId).remove();
  }
}

function commonConnectionOnSuccess(connectionId,response) {

    $('#content_' + connectionId).html(response['content']);
  
}

function updateAllcounts() {
  $.ajax({
    url: '/all-counts',
    type: 'GET',
    success: function(data) {
        // Update the label with the new count
        
            $('#get_suggestions_btn').text('Suggestions (' + data.suggestions_count + ')');
       
            $('#get_sent_requests_btn').text('Sent Requests (' + data.send_request_count + ')');
       
            $('#get_received_requests_btn').text('Received Requests (' + data.recive_request_count + ')');
       
            $('#get_connections_btn').text('Connections (' + data.connections_count + ')');
       
    },
    error: function() {
        alert('Could not fetch counts. Please try again later.');
    }
});
}



$(function () {
  updateAllcounts();
  getSuggestions();

  $(document).off('click', '.get-connections-in-common').on('click', '.get-connections-in-common', function() {
    var connectionId = $(this).data('connection-id');
    var userId = $(this).data('user-id');
    var targetCollapse = $(this).data('bs-target');

    // Use the targetCollapse ID to attach the event listener
    $(targetCollapse).one('shown.bs.collapse', function() {
        // Load connections in common
        getConnectionsInCommon(userId, connectionId, targetCollapse);
    });
});


});