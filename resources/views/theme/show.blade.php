<x-layout>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

    <div class="theme_Body">

    <a href="{{ url()->previous() }}" class="back">Nazad</a>

        <div class="theme_Container">
            <div class="theme_Image">
                <img src="{{ asset('storage/' . $theme->image) }}" alt="none" style="color: white; text-align:center; border-radius:2px; height:200px">
            </div>


            <div class="theme_Info">
                <span class="theme_Title">{{ $theme->title }}</span>

                <span class="theme_Description"><span style="font-style:italic">Opis: </span> {{ $theme->description }}</span>

                <div class="theme_Details">
                    <p><strong>Moderator:</strong><span> {{ $theme->user->name }}</span></p>
                </div>



                <div class="follow">
                        <a href="/themes/addComment?themeTitle={{ $theme->title }}&themeId={{ $theme->id }}" class="theme_button_comment">
                            <i class="fas fa-reply" style="color: green; margin-right:5px"></i>Odgovori
                        </a>


                        @auth
                            @if (auth()->check() && auth()->user()->role === 'korisnik')
                                @php
                                    $followedThemes = auth()->user()->followedThemes;
                                    if ($followedThemes) {
                                        $followedThemesIds = $followedThemes->pluck('id')->toArray();
                                        $isFollowing = in_array($theme->id, $followedThemesIds);
                                    } else {
                                        $isFollowing = false;
                                    }
                                @endphp

                                @if ($isFollowing)
                                    <form method="POST" action="{{ route('themes.unfollow', $theme) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-minus-circle" style="padding-right: 5px"></i>Otprati</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('themes.follow', $theme) }}">
                                        @csrf
                                        <button type="submit" class="follow_button"> <i class="fas fa-plus-circle" style="padding-right: 5px"></i>Zaprati</button>
                                    </form>
                                @endif

                            @endif


                            @if(auth()->user()->role === 'moderator' && auth()->user()->id === $theme->user_id)
                            <a href="{{ route('followed-themes.followers', ['themeId' => $theme->id]) }}" class="followers-button">
                                <i class="fas fa-users" style="padding-right: 5px;"></i>Pratioci
                            </a>

                            @endif
                        @endauth
                </div>
            </div>
        </div>


        <div class="anketa">
            <span class="comment_text">Ankete</span>
            <div style="width:100%; background-color:grey;height:1px; margin-top:20px; margin-bottom:30px"></div>

            <div class="anketa_container">
                @if ($polls->isNotEmpty())
                    <ul>

                        @foreach ($polls as $poll)
                            <li><a href="{{ route('theme.details', $poll->id) }}">{{ $poll->question }}</a></li>
                        @endforeach


                    </ul>
                @else
                    <p>Trenutno nema dostupnih anketa na ovu temu.</p>
                @endif

                @auth
                    @if(auth()->user()->role === 'moderator' && auth()->user()->id === $theme->user_id)
                        <a href="{{ route('themes.create-poll', $theme) }}" class="new-poll">
                            <i class="fas fa-plus-circle" style="padding-right: 5px; color: green"></i>Zapocni novu anketu
                        </a>
                    @endif
                @endauth
            </div>


        </div>


        <div class="comments">
            <span class="comment_text">Komentari</span>

            <div style="width:100%; background-color:grey;height:1px; margin-top:20px; margin-bottom:30px"></div>



            @foreach ($comments as $comment)
                <div class="comment_card">

                    <div class="comment_left">
                        @if ($comment->user)
                            <span class="user">
                                @if ($comment->user->picture != "null")
                                    <img src="{{ asset('storage/' . $comment->user->picture) }}" alt="{{ $comment->user->name }}" style="width:100px; height:100px; border-radius:80px; margin-right:10px">
                                @else
                                    <i class="fa-solid fa-user" style="margin-right:5px;"></i>
                                @endif
                            </span>
                            <p style="margin-top: 5px;">{{ $comment->user->name }}</p>
                        @endif
                    </div>

                    <span class="line"></span>

                    <div class="comment_right">
                        <p style=" padding-bottom:15px">
                            <span style="color: black; font-size:13px; font-weight: bold;">Odg: {{ $theme->title }}</span>
                            <span style="float: right; margin-right: 0; color: grey">{{ $theme->created_at->format('Y-m-d') }}</span>
                        </p>
                        <p style="font-size: 16px">{{ $comment->content }}</p>

                        @auth
                            @if (auth()->user()->role === 'admin' || auth()->user()->id === $theme->user_id)
                                <form action="/delete-comment/{{ $comment->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="float: right; margin-top: 10px;">Obriši komentar</button>
                                </form>
                            @endif
                        @endauth


                    </div>
                </div>
            @endforeach



            <div class="p-6">
                {{$comments->links()}}
            </div>
        </div>
</div>
</x-layout>
