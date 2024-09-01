

<div id="connections-container">

    @foreach ($connections as $connection)
        <div class="my-2 shadow text-white bg-dark p-1" id="connection-{{ $connection->id }}">
            <div class="d-flex justify-content-between">
                @if ($connection->user_id == auth()->id())
                    <table class="ms-1">
                        <td class="align-middle">{{ $connection->connectedUser->name }}</td>
                        <td class="align-middle"> - </td>
                        <td class="align-middle">{{ $connection->connectedUser->email }}</td>
                        <td class="align-middle">
                    </table>
                @else
                    <table class="ms-1">
                        <td class="align-middle">{{ $connection->user->name }}</td>
                        <td class="align-middle"> - </td>
                        <td class="align-middle">{{ $connection->user->email }}</td>
                        <td class="align-middle">
                    </table>
                @endif
                <div>
                    <button class="btn btn-primary get-connections-in-common" style="width: 220px"
                        id="get_connections_in_common_{{ $connection->id }}" data-user-id="{{ auth()->id() }}"
                        data-connection-id="{{ $connection->id }}" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse_{{ $connection->id }}" aria-expanded="false"
                        aria-controls="collapseExample">
                        Connections in common ({{ $connection->common_count }})
                    </button>


                    <button id="remove_connection_btn_{{ $connection->id }}" class="btn btn-danger me-1"
                        onclick="removeConnection({{ auth()->id() }},{{ $connection->id }})">Remove
                        Connection</button>
                </div>
            </div>
            <div class="collapse" id="collapse_{{ $connection->id }}">
                <div id="content_{{ $connection->id }}" class="p-2">
                    {{-- Display data here --}}
                </div>
                <div id="connections_in_common_skeletons_{{ $connection->id }}">
                    {{-- Paste the loading skeletons here via jQuery before the AJAX to get the connections in common --}}
                </div>

            </div>
        </div>
    @endforeach

</div>

@if ($connections->hasMorePages())
    <div class="d-flex justify-content-center mt-2 py-3" id="load_more_btn_parent">
        <button class="btn btn-primary" onclick="getMoreConnections()"
            data-next-page="{{ $connections->nextPageUrl() }}" id="load_more_btn">Load more</button>
    </div>
@endif
