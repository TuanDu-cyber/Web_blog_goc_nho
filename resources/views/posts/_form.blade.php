<div class="form-container">
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($post))
        @method('PUT')
        @endif

        <label for="title">Tiêu đề</label>
        <input type="text" id="title" name="title" value="{{ old('title', $post->title ?? '') }}" placeholder="Tiêu đề bài viết..">
        @error('title')
        <p class="error">{{ $message }}</p>
        @enderror

        <label for="category_id">Chuyên mục</label>
        <select id="category_id" name="category_id">
            <option value="">-- Chọn chuyên mục --</option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id')
        <p class="error">{{ $message }}</p>
        @enderror

        <label for="content">Nội dung</label>
        <textarea id="content" name="content" style="height:250px" placeholder="Nội dung bài viết..">{{ old('content', $post->content ?? '') }}</textarea>
        @error('content')
        <p class="error">{{ $message }}</p>
        @enderror
        <label for="status">Trạng thái</label>
        <select id="status" name="status">
            <option value="published" @selected(old('status', $post->status ?? 'published') === 'published')>Đăng ngay</option>
            <option value="draft" @selected(old('status', $post->status ?? 'published') === 'draft')>Lưu nháp</option>
            <option value="scheduled" @selected(old('status', $post->status ?? 'published') === 'scheduled')>Hẹn giờ đăng</option>
        </select>
        @error('status')
        <p class="error">{{ $message }}</p>
        @enderror

        <div id="scheduled-box">
            <label for="scheduled_at">Thời gian đăng</label>
            <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                value="{{ old('scheduled_at', isset($post) && $post->scheduled_at ? $post->scheduled_at->format('Y-m-d\TH:i') : '') }}">
            @error('scheduled_at')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <label>Tag</label>
        <div class="tag-list">
            @foreach ($tags as $tag)
            <label class="tag-item">
                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                    @checked(in_array($tag->id, old('tags', isset($post) ? $post->tags->pluck('id')->all() : [])))>
                {{ $tag->name }}
            </label>
            @endforeach
        </div>
        @error('tags')
        <p class="error">{{ $message }}</p>
        @enderror
        @error('tags.*')
        <p class="error">{{ $message }}</p>
        @enderror

        <label for="image_file">
            Ảnh bài viết <span class="hint">(jpg, png, webp, tối đa 2MB)</span>
        </label>
        @if (isset($post) && $post->image)
        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="preview">
        <span class="hint">Ảnh hiện tại. Chọn ảnh mới nếu muốn thay.</span>
        @endif
        <input type="file" id="image_file" name="image_file" accept="image/*">
        @error('image_file')
        <p class="error">{{ $message }}</p>
        @enderror

        <input type="submit" value="{{ $button }}">

        <script>
            (function() {
                var status = document.getElementById('status');
                var box = document.getElementById('scheduled-box');

                function toggle() {
                    box.style.display = status.value === 'scheduled' ? '' : 'none';
                }

                status.addEventListener('change', toggle);
                toggle();
            })();
        </script>
    </form>
</div>