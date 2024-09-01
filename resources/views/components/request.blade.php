

<div id="sent-requests-container">
  @foreach ($requests as $request)
  <div class="my-2 shadow text-white bg-dark p-1" id="request-{{$request->id}}">
    <div class="d-flex justify-content-between">
      @if ($mode == 'sent')
      <td class="align-middle">{{$request->connectedUser->name}}</td>
      <td class="align-middle"> - </td>
      <td class="align-middle">{{$request->connectedUser->email}}</td>
      <td class="align-middle">  
      @else
      <td class="align-middle">{{$request->user->name}}</td>
      <td class="align-middle"> - </td>
      <td class="align-middle">{{$request->user->email}}</td>
      <td class="align-middle">
      @endif
      <table class="ms-1">
      
      </table>
      <div>
        @if ($mode == 'sent')
          <button onclick="deleteRequest('{{$request->user_id}}','{{$request->id}}')" id="cancel_request_btn_" class="btn btn-danger me-1"
            onclick="">Withdraw Request</button>
        @else
          <button onclick="acceptRequest('{{$request->connected_user_id}}','{{$request->id}}')" id="accept_request_btn_" class="btn btn-primary me-1"
            onclick="">Accept</button>
        @endif
      </div>
    </div>
  </div>
  @endforeach
</div>

@if($requests->hasMorePages())
<div class="d-flex justify-content-center mt-2 py-3 {{-- d-none --}}" id="load_more_btn_parent">
  <button class="btn btn-primary" onclick="getMoreRequests('{{$mode}}')" data-next-page="{{ $requests->nextPageUrl() }}" id="load_more_btn">Load more</button>
</div>
@endif
