@foreach ($users as $connection)

<div class="p-2 shadow rounded mt-2  text-white bg-dark">{{$connection->name}} - {{$connection->email}}</div>

@endforeach

@if($users->hasMorePages())
  
  <div class="d-flex justify-content-center mt-2 py-3" id="load_more_btn_parent_{{$connection->id}}">
    <button class="btn btn-primary" onclick="getMoreCommonConnections({{auth()->id()}},{{$connection->id}})" data-next-page="{{ $connections->nextPageUrl() }}" id="load_more_btn_common_{{$connection->id}}">Load more</button>
  </div>

@endif