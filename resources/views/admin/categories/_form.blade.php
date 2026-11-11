<div class="admin-form">
    <form action="{{ $action }}" method="POST">
        @csrf
        @if (isset($category))
        @method('PUT')
        @endif

        <label for="name">Tên danh mục</label>
        <input type="text" id="name" name="name" value="{{ old('name', $category->name ?? '') }}" maxlength="50">
        @error('name')
        <p class="error">{{ $message }}</p>
        @enderror
        @error('slug')
        <p class="error">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn">{{ $button }}</button>
        <a class="btn btn-clear" href="{{ route('admin.categories.index') }}">Hủy</a>
    </form>
</div>