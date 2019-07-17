@if(isset($comments) && $comments->isNotEmpty())
    @foreach($comments as $comment)
        <p>{{$comment->comment}} - <i> {{$comment->completedByName()}} </i></p>
    @endforeach
@else
    <p>No comments yet</p>
@endif
