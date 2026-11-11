<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Thêm cột mới (cho phép null để còn chuyển dữ liệu cũ sang)
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->foreignId('category_id')->nullable()->after('slug')
                ->constrained()->nullOnDelete();
            $table->dateTime('scheduled_at')->nullable()->after('published_at');
        });

        //Tạo 4 danh mục cũ rồi gán category_id cho bài cũ
        $categories = [
            'travel' => 'Du lịch',
            'study'  => 'Học tập',
            'sport'  => 'Thể thao',
            'life'   => 'Đời sống',
        ];

        foreach ($categories as $slug => $name) {
            $id = DB::table('categories')->insertGetId([
                'name'       => $name,
                'slug'       => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('posts')->where('category', $slug)->update(['category_id' => $id]);
        }

        //Tạo slug cho bài cũ (gắn thêm id để không bao giờ trùng)
        foreach (DB::table('posts')->get() as $post) {
            DB::table('posts')->where('id', $post->id)->update([
                'slug' => Str::slug($post->title) . '-' . $post->id,
            ]);
        }

        //Bỏ cột category cũ
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('category')->default('')->after('title');
        });

        foreach (DB::table('categories')->get() as $category) {
            DB::table('posts')->where('category_id', $category->id)->update(['category' => $category->slug]);
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn(['slug', 'scheduled_at']);
        });
    }
};
