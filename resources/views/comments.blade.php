<div class="modal" id="comments{{ $dptGoals->id }}">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View comments</h5>
            </div>
            <form method="POST" action="{{url('store_comments')}}" onsubmit="show()">
                @csrf
                <input type="hidden" name="departmental_goals_id" value="{{ $dptGoals->id }}">
                <div class="modal-body">
                    <div class="feed-activity-list">
                        @foreach ($dptGoals->comments as $comment)
                        <div class="feed-element">
                            <div class="media-body ">
                                <small class="pull-right">{{ $comment->created_at->diffForHumans() }}</small>
                                <strong>{{ $comment->user->name }}</strong> <br>
                                <small class="text-muted">@if(date('Y-m-d', strtotime($comment->created_at)) == date('Y')) Today @endif {{ date('h:i A', strtotime($comment->created_at)) }} - {{ date('m.d.Y', strtotime($comment->created_at)) }}</small> <br>
                                {{-- <div class="well">
                                </div> --}}
                                <p class="m-t-md">
                                    {!! nl2br(e($comment->comment)) !!}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="media-body">
                        <textarea class="form-control" name="comment" placeholder="Write comment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>