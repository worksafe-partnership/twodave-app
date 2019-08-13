@if(isset($comments) && $comments->isNotEmpty())
    <table class="table is-striped">
        <thead>
            <tr>
                <th>Date/Time</th>
                <th>Author</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
                <tr>
                    <td>{{$comment->niceApprovedDT()}}</td>
                    <td>{{$comment->completedByName()}}</td>
                    <td>{!!$comment->comment!!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No comments yet</p>
@endif
