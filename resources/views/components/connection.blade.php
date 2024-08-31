{{-- <div class="my-2 shadow text-white bg-dark p-1" id="">
  <div class="d-flex justify-content-between">
    <table class="ms-1">
      <td class="align-middle">Name</td>
      <td class="align-middle"> - </td>
      <td class="align-middle">Email</td>
      <td class="align-middle">
    </table>
    <div>
      <button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button"
        data-bs-toggle="collapse" data-bs-target="#collapse_" aria-expanded="false" aria-controls="collapseExample">
        Connections in common ()
      </button>
      <button id="create_request_btn_" class="btn btn-danger me-1">Remove Connection</button>
    </div>

  </div>
  <div class="collapse" id="collapse_">

    <div id="content_" class="p-2">
      Display data here
      <x-connection_in_common />
    </div>
    <div id="connections_in_common_skeletons_">
      Paste the loading skeletons here via Jquery before the ajax to get the connections in common
    </div>
    <div class="d-flex justify-content-center w-100 py-2">
      <button class="btn btn-sm btn-primary" id="load_more_connections_in_common_">Load
        more</button>
    </div>
  </div>
</div> --}}

@foreach ($connections as $connection)
    <div class="my-2 shadow text-white bg-dark p-1" id="connection-{{$connection->id}}">
        <div class="d-flex justify-content-between">
            @if ($connection->user_id == auth()->id())
            <table class="ms-1">
              <td class="align-middle">{{$connection->connectedUser->name}}</td>
              <td class="align-middle"> - </td>
              <td class="align-middle">{{$connection->connectedUser->email}}</td>
              <td class="align-middle">
          </table>
            @else
            <table class="ms-1">
              <td class="align-middle">{{$connection->user->name}}</td>
              <td class="align-middle"> - </td>
              <td class="align-middle">{{$connection->user->email}}</td>
              <td class="align-middle">
          </table>
            @endif
            <div>
                <button style="width: 220px" id="get_connections_in_common_{{$connection->id}}" class="btn btn-primary"
                    type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{$connection->id}}"
                    aria-expanded="false" aria-controls="collapseExample">
                    Connections in common ({{$connection->common_count}})
                </button>
                <button id="remove_connection_btn_{{$connection->id}}" class="btn btn-danger me-1"
                    onclick="removeConnection({{ $connection->id }})">Remove Connection</button>
            </div>
        </div>
        <div class="collapse" id="collapse_{{$connection->id}}">
            <div id="content_{{$connection->id}}" class="p-2">
                {{-- Display data here --}}
                <x-connection_in_common :targetUserId="$connection->id" />
            </div>
            <div id="connections_in_common_skeletons_{{$connection->id}}">
                {{-- Paste the loading skeletons here via jQuery before the AJAX to get the connections in common --}}
            </div>
            <div class="d-flex justify-content-center w-100 py-2">
                <button class="btn btn-sm btn-primary" id="load-more-connections-in-common_{{$connection->id}}"
                    onclick="loadMoreConnectionsInCommon({{ $connection->id }})">Load More</button>
            </div>
        </div>
    </div>
@endforeach

@if($connections->hasMorePages())
  {{-- <div class="text-center mt-4">
      <button id="load-more-btn" class="btn btn-secondary" data-next-page="{{ $suggestions->nextPageUrl() }}">Load More</button>
  </div> --}}
  <div class="d-flex justify-content-center mt-2 py-3 {{-- d-none --}}" id="load_more_btn_parent">
    <button class="btn btn-primary" onclick="" data-next-page="{{ $suggestions->nextPageUrl() }}" id="load_more_btn">Load more</button>
  </div>

@endif

