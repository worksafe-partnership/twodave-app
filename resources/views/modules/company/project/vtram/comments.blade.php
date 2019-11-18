<div style="height: 65px;text-align:right;">
    <button class="button is-success is-primary" id="comments-button">View Comments ( {{ $comments->count() }} )</button>
</div>
<div id="comments-sidebar" class="column hidden">
    <p class="sub-heading">Comments
        <button class="button is-success is-small" style="float:right" id="close-comments">Close</button>
    </p>

    @if(isset($comments) && $comments->isNotEmpty())
        @foreach($comments as $comment)
            <p>{!!$comment->comment!!}</i></p>
            <p><i>{{$comment->completedByName()}} {{$comment->created_at->format('d/m/Y')}}</i></p>
            <hr>
        @endforeach
    @else
        <p>No Comments</p>
    @endif
</div>
@push('scripts')
<script>
    // Comments
    $('#comments-button').on('click', function() {
        $('#comments-sidebar').removeClass('hidden');
        $('#comments-button-div').addClass('hidden');
    })

    $('#close-comments').on('click', function() {
      $('#comments-sidebar').addClass('hidden');
      $('#comments-button-div').removeClass('hidden');
    });
</script>
@endpush
