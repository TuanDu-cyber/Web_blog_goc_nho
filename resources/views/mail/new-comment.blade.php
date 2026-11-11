<p>Xin chào {{ $comment->post->user->name }},</p>
<p><strong>{{ $comment->user->name }}</strong> vừa bình luận vào bài viết <strong>{{ $comment->post->title }}</strong>:</p>
<blockquote>{{ $comment->content }}</blockquote>
<p><a href="{{ route('article.show', $comment->post) }}#comments">Xem bình luận</a></p>