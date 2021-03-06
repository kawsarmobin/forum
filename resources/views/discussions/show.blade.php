@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <img src="{{ $discussion->user->avatar }}" alt="" width="30px" height="30px" style="border-radius: 50px">
            &nbsp; <span>{{ $discussion->user->name  }} <b>({{ $discussion->user->points }})</b> - <code>{{ $discussion->created_at->diffForHumans() }}</code></span>

            @if ($discussion->hasBestAnswer())
                <span class="btn btn-sm btn-outline-info float-right">Close</span>
            @else
                <span class="btn btn-sm btn-outline-danger float-right">Open</span>
            @endif

            <span class="float-right">  &nbsp;  </span>

            @if (Auth::id() == $discussion->user->id)
                @if (!$discussion->hasBestAnswer())
                    <a href="{{ route('discussions.edit', $discussion->slug) }}" class="btn btn-sm btn-outline-success float-right">Edit</a>
                @endif
            @endif

            <span class="float-right">  &nbsp;  </span>

            @if ($discussion->is_being_watched_by_auth_user())
                <a href="{{ route('discussion.unwatch', $discussion->id) }}" class="btn btn-sm btn-outline-dark float-right">Unwatch</a>
            @else
                <a href="{{ route('discussion.watch', $discussion->id) }}" class="btn btn-sm btn-outline-dark float-right">Watch</a>
            @endif

        </div>

        <div class="card-body">

            <h4 class="text-center">
                {{ $discussion->title }}
            </h4>

            <hr>

            <p class="text-center">
                {!! Markdown::convertToHtml($discussion->content) !!}
            </p>

            <hr>

            @if ($best_answer)
                <div style="padding: 20px">
                    <h3 class="text-center" style="color: red">Best Answer</h3>
                    <div class="card text-white bg-danger ">
                        <div class="card-header text-center">
                            <img src="{{ $best_answer->user->avatar }}" alt="" width="30px" height="30px" style="border-radius: 50px">
                            &nbsp; <span>{{ $best_answer->user->name  }}</span> <abbr>({{ $best_answer->user->points }})</abbr>
                        </div>
                        <div class="card-body text-justify">
                            {!! Markdown::convertToHtml($best_answer->content) !!}
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div class="card-footer">
            <span>
                {{ $discussion->replies->count() }} Replies
            </span>
            <a class="btn btn-sm btn-outline-success float-right" href="{{ route('channel', $discussion->channel->slug) }}">{{ $discussion->channel->title }}</a>
        </div>
    </div> <br>


    @foreach ($discussion->replies as $reply)
        <div class="card">
            <div class="card-header">
                <img src="{{ $reply->user->avatar }}" alt="" width="30px" height="30px" style="border-radius: 50px">
                &nbsp; <span>{{ $reply->user->name  }} <b>({{ $reply->user->points }})</b> - <code>{{ $reply->created_at->diffForHumans() }}</code></span>

                @if (!$best_answer)
                    @if (Auth::id() == $discussion->user->id)
                        <span class="btn btn-sm btn-outline-primary float-right"><a href="{!! route('discussion.best.answer', $reply->id) !!}" style="text-decoration: none;">Mark as best answer</a></span>
                    @endif
                @endif

                <span class="float-right">  &nbsp;  </span>

                @if (Auth::id() == $reply->user->id)
                    @if (!$reply->best_answer)
                        <span class="btn btn-sm btn-secondary float-right"><a href="{!! route('reply.edit', $reply->id) !!}" style="text-decoration: none; color: white">Edit</a></span>
                    @endif
                @endif

            </div>

            <div class="card-body">

                <p class="text-center">
                    {!! Markdown::convertToHtml($reply->content) !!}
                </p>

            </div>

            <div class="card-footer">
                @if ($reply->is_liked_by_auth_user())
                    <a href="{{ route('reply.unlike', $reply->id) }}" class="btn btn-sm btn-danger">Unlike <span class="badge">{{ $reply->likes->count() }}</span></a>
                @else
                    <a href="{{ route('reply.like', $reply->id) }}" class="btn btn-sm btn-success">Like <span class="badge">{{ $reply->likes->count() }}</span></a>
                @endif
            </div>
        </div> <br>
    @endforeach


    <div class="card">
        <div class="card-body">
            @if (Auth::check())
                <form action="{{ route('discussion.reply', $discussion->id) }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="" class="form-label">
                            <h5>Leave a reply...</h5>
                        </label>
                        <textarea class="form-control" name="reply" rows="8" cols="80">{{ $discussion->reply }}</textarea>
                    </div>
                    <div class="form-group" style="padding-left: 300px">
                        <button type="submit" class="btn btn-sm btn-primary">Leave a reply</button>
                    </div>
                </form>
            @else
                <div class="text-center">
                    <h2>Sign in to leave a reply</h2>
                </div>
            @endif
        </div>
    </div>
    <br>

@endsection
