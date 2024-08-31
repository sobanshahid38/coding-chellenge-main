<div id="suggestions-container">
  @foreach ($suggestions as $suggestion)
      <div class="my-2 shadow text-white bg-dark p-2" id="suggestion-{{$suggestion->id}}">
          <div class="d-flex justify-content-between align-items-center">
              <div>
                  <span class="ms-1">{{ $suggestion->name }}</span>
                  <span> - </span>
                  <span>{{ $suggestion->email }}</span>
              </div>
              <div>
                  <button onclick="sendRequest('{{auth()->id()}}','{{$suggestion->id}}')" id="create_request_btn_{{$suggestion->id}}" class="btn btn-primary me-1">Connect</button>
              </div>
          </div>
      </div>
  @endforeach
</div>

@if($suggestions->hasMorePages())
  {{-- <div class="text-center mt-4">
      <button id="load-more-btn" class="btn btn-secondary" data-next-page="{{ $suggestions->nextPageUrl() }}">Load More</button>
  </div> --}}
  <div class="d-flex justify-content-center mt-2 py-3 {{-- d-none --}}" id="load_more_btn_parent">
    <button class="btn btn-primary" onclick="" data-next-page="{{ $suggestions->nextPageUrl() }}" id="load_more_btn">Load more</button>
  </div>
@else
@endif
