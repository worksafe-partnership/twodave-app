@if(isset($comments) && $comments->isNotEmpty())
    <table class="table is-striped is-fullwidth">
        <thead>
            <tr>
                <th>Date/Time</th>
                <th>Author</th>
                <th>Type</th>
                <th>Comment</th>
                <th>Status at time of approval</th>
                <th>Review Document</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
                <tr>
                    <td>{{$comment->niceApprovedDT()}}</td>
                    <td>{{$comment->completed_by}}</td>
                    <td>{{ $comment->niceType() }}</td>
                    <td>{!!$comment->comment!!}</td>
                    <td>{{ $comment->niceStatus() }}</td>
                    <td>
                        @if ($comment->review_document != null)
                            <a href="/image/{{ $comment->review_document }}" download class="button is-primary">Review Document</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No comments yet</p>
@endif
