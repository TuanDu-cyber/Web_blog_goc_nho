<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tài khoản quản trị (role không nằm trong $fillable nên tạo bằng factory)
        User::factory()->admin()->create([
            'name'     => 'Quản trị viên',
            'email'    => 'admin@gmail.com',
            'password' => '12345678',
        ]);
        $an = User::create([
            'name'     => 'Nguyễn Văn An',
            'email'    => 'an@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $binh = User::create([
            'name'     => 'Trần Thị Bình',
            'email'    => 'binh@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $chau = User::create([
            'name'     => 'Lê Minh Châu',
            'email'    => 'chau@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $dat = User::create([
            'name'     => 'Phạm Quốc Đạt',
            'email'    => 'dat@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $ha = User::create([
            'name'     => 'Hoàng Thu Hà',
            'email'    => 'ha@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $data = [
            // Du lịch
            [
                'category' => 'travel',
                'title'    => 'Hoàng hôn bên dòng sông, ngắm cầu lên đèn',
                'image'    => 'images/world5.jpg',
                'tags'     => ['Du lịch', 'Nhiếp ảnh'],
                'content'  => implode("\n\n", [
                    'Một buổi chiều cuối tuần, mình bỏ lại máy tính, mang theo chai nước và chiếc áo khoác mỏng ra bờ sông ngồi chờ hoàng hôn. Không có kế hoạch gì to tát, chỉ muốn ngồi yên một lúc xem trời đổi màu như thế nào.',
                    'Tuần đó mình khá mệt. Bài tập dồn lại, tối nào cũng ngủ muộn, đầu óc lúc nào cũng như có hàng chục tab đang mở. Mình nghĩ nếu cứ ngồi lì trong phòng thì chẳng làm thêm được gì, chi bằng ra ngoài hít thở một chút rồi quay về làm tiếp.',
                    'Mình đến bờ sông lúc bốn rưỡi, khi nắng vẫn còn gắt. Mình chọn một bậc thang có bóng cây, trải tạm cái áo khoác ngồi xuống. Xung quanh có vài nhóm bạn trẻ đang trò chuyện, một chú ngồi câu cá và mấy bạn nhỏ chạy xe đạp vòng quanh.',
                    'Điều đầu tiên mình nhận ra là ánh sáng đổi màu rất chậm. Mãi đến gần sáu giờ, mặt nước mới chuyển từ xanh xám sang vàng nhạt, rồi dần ngả cam. Nếu cứ nhìn chăm chăm thì chẳng thấy gì thay đổi, nhưng chỉ cần cúi xuống xem điện thoại một lát, ngẩng lên đã thấy bầu trời khác hẳn.',
                    'Khi mặt trời chạm tới mép những tòa nhà phía xa, cả dòng sông như được nhuộm một lớp màu ấm. Gió bắt đầu nổi, mát hơn hẳn lúc nãy. Có lúc mình quên mất mình đang ngồi chờ, chỉ thấy đầu óc nhẹ đi từng chút một.',
                    'Rồi những ngọn đèn trên cầu sáng lên, từng cụm một. Ban đầu chỉ là vài chấm vàng nhỏ, một lát sau cả nhịp cầu hiện ra rõ ràng, phản chiếu xuống mặt nước thành những vệt dài lung linh. Đúng là khoảnh khắc mình chờ từ đầu buổi.',
                    'Mình có chụp vài tấm ảnh, nhưng sau đó cất điện thoại vào túi. Ảnh chụp lại luôn kém hơn những gì mắt nhìn thấy, và mình muốn nhớ buổi chiều này bằng cảm giác thay vì bằng dung lượng bộ nhớ.',
                    'Nếu bạn định thử, mình có vài kinh nghiệm nhỏ. Nên đến sớm khoảng bốn mươi phút trước giờ mặt trời lặn để chọn chỗ ngồi đẹp. Mang theo áo khoác vì gió bờ sông buổi tối khá lạnh. Cuối tuần chỗ nào cũng đông, nên nếu có thể hãy đi vào một buổi chiều trong tuần.',
                    'Mình về phòng lúc trời đã tối hẳn. Lạ là tối hôm đó mình làm xong bài tập nhanh hơn dự tính. Có lẽ những lúc đầu óc đầy thì việc cần làm không phải là cố thêm, mà là bước ra ngoài, nhìn một thứ gì đó thật chậm.',
                ]),
            ],
            [
                'category' => 'travel',
                'title'    => 'Một ngày dạo quanh London bên dòng sông Thames',
                'image'    => 'images/world2.jpg',
                'tags'     => ['Du lịch'],
                'content'  => implode("\n\n", [
                    'Đứng bên bờ sông Thames nhìn tháp đồng hồ và tòa nhà Quốc hội ngay trước mặt, mình mới thấy những bức ảnh từng xem trên mạng vẫn chưa nói hết được vẻ đẹp của nơi này. Đây là ghi chép về một ngày đi bộ dọc dòng sông, kèm vài điều mình rút ra.',
                    'Mình bắt đầu từ khu Westminster vào buổi sáng, khi du khách chưa quá đông. Tháp đồng hồ mà mọi người hay gọi là Big Ben nhìn ngoài đời cao và sẫm màu hơn mình tưởng. Đứng ngửa cổ nhìn một lúc là chợt hiểu vì sao ai đến London cũng muốn chụp ảnh ở đây.',
                    'Từ đó mình đi bộ qua cầu Westminster sang bờ nam. Đi dọc con đường ven sông ở phía này, bạn sẽ thấy vòng quay London Eye, những quầy bán đồ ăn nhanh và rất nhiều nghệ sĩ đường phố. Không khí vừa náo nhiệt vừa thoải mái, chẳng ai có vẻ vội vàng.',
                    'Buổi trưa mình ghé một khu chợ thực phẩm gần cầu London. Mình gọi một chiếc bánh kẹp nóng và một cốc trà, ăn đứng cạnh quầy vì chỗ ngồi không còn. Giá không rẻ, nhưng mùi thức ăn và tiếng người nói cười khiến bữa trưa trở nên rất đáng nhớ.',
                    'Chiều đến, mình đi tiếp về phía đông. Dưới chân một cây cầu có khu bán sách cũ ngoài trời, mình đứng lật từng cuốn gần cả tiếng. Mình không mua gì vì sợ hành lý nặng, nhưng đây là góc mình thích nhất trong cả chuyến đi.',
                    'Cuối ngày, mình đến cầu Tháp để ngắm hoàng hôn. Đèn bắt đầu bật lên, ánh sáng phản chiếu trên mặt sông tạo nên khung cảnh mà trước giờ mình chỉ thấy trong phim. Cả ngày đi bộ khiến chân mình đau, nhưng nhìn cảnh này thì thấy xứng đáng.',
                    'Về chi phí, London khá đắt nên mình tính toán kỹ hơn. Nhiều bảo tàng lớn cho vào cửa miễn phí, đi bộ cũng không tốn tiền, nên phần lớn khoản chi của mình nằm ở đồ ăn và vé đi lại. Mình mua một thẻ đi lại dùng cả ngày để không phải tính lẻ từng chuyến.',
                    'Thời tiết ở đây khá thất thường. Sáng còn nắng, chiều có thể đổ mưa, nên một chiếc áo mưa gấp gọn trong túi là rất hữu ích. Giày thoải mái cũng quan trọng không kém, vì bạn sẽ đi bộ nhiều hơn mình tưởng.',
                    'Nếu chỉ có một ngày ở London, mình khuyên bạn đừng cố nhồi nhét quá nhiều điểm đến. Chọn một dòng sông, đi chậm dọc theo nó, dừng lại ở những chỗ mình thích. Đôi khi một ngày đi bộ không mục đích lại để lại nhiều kỷ niệm hơn một lịch trình dày đặc.',
                ]),
            ],

            [
                'category' => 'travel',
                'title'    => 'Thành phố về đêm bên cầu Brooklyn',
                'image'    => 'images/world10.jpg',
                'tags'     => ['Du lịch', 'Nhiếp ảnh'],
                'content'  => "Ánh đèn thành phố phản chiếu trên mặt nước tạo nên khung cảnh rất khó quên. Mình đứng khá lâu để chụp được bức ảnh này.\n\nNếu có dịp, bạn nên ghé vào buổi tối để cảm nhận không khí khác hẳn ban ngày.",
            ],

            // Học tập
            [
                'category' => 'study',
                'title'    => 'Những dòng code Blade đầu tiên của mình',
                'image'    => 'images/business4.jpg',
                'tags'     => ['Laravel', 'Blade', 'Học tập'],
                'content'  => "Hôm nay mình bắt đầu học Blade trong Laravel. Cảm giác đầu tiên là khá dễ chịu vì cú pháp ngắn gọn, dễ đọc.\n\nCác directive như extends, section, yield giúp mình tách layout rất gọn, không phải sao chép header và footer nhiều lần.",
            ],
            [
                'category' => 'study',
                'title'    => 'Góc học tập nhỏ gọn của mình',
                'image'    => 'images/business7.jpg',
                'tags'     => ['Học tập'],
                'content'  => "Mình thích một góc bàn gọn gàng với máy tính, vài chậu cây nhỏ và ánh sáng tự nhiên.\n\nKhông gian sạch sẽ giúp mình tập trung học lâu hơn mỗi ngày.",
            ],
            [
                'category' => 'study',
                'title'    => 'Buổi học nhóm cuối tuần cùng lớp',
                'image'    => 'images/business1.jpg',
                'tags'     => ['Học tập', 'Laravel'],
                'content'  => "Cuối tuần cả nhóm tụ tập để làm bài tập chung. Mỗi người một việc, vừa làm vừa trao đổi nên tiến độ nhanh hơn hẳn.\n\nMình học được nhiều mẹo hay từ các bạn.",
            ],
            [
                'category' => 'study',
                'title'    => 'Thói quen đọc tin mỗi sáng',
                'image'    => 'images/politics5.jpg',
                'tags'     => ['Học tập', 'Đời sống'],
                'content'  => "Mỗi sáng mình dành mười lăm phút để đọc tin tức. Thói quen nhỏ này giúp mình mở rộng hiểu biết và có thêm nhiều chủ đề để viết blog.\n\nBạn cũng có thể thử xem sao.",
            ],

            // Thể thao
            [
                'category' => 'sport',
                'title'    => 'Chạy bộ buổi sáng bên hồ',
                'image'    => 'images/sport2.jpg',
                'tags'     => ['Chạy bộ'],
                'content'  => "Sáng sớm không khí trong lành, đường vắng nên chạy rất thoải mái. Mình đặt mục tiêu chạy đều đặn vài buổi mỗi tuần.\n\nSau mỗi buổi chạy, đầu óc mình minh mẫn hơn hẳn.",
            ],
            [
                'category' => 'sport',
                'title'    => 'Đá bóng cuối tuần cùng bạn bè',
                'image'    => 'images/sport5.jpg',
                'tags'     => ['Bóng đá'],
                'content'  => "Không cần giỏi, chỉ cần vui là đủ. Trận bóng cuối tuần là cách mình xả stress sau những ngày học tập.\n\nCả nhóm hẹn nhau đều đặn mỗi tuần.",
            ],
            [
                'category' => 'sport',
                'title'    => 'Lần đầu xem bóng bầu dục',
                'image'    => 'images/sport6.jpg',
                'tags'     => ['Bóng đá'],
                'content'  => "Luật chơi khá phức tạp nhưng không khí trận đấu rất cuốn hút. Mình vừa xem vừa tra cứu luật chơi.\n\nHy vọng sẽ có dịp xem trực tiếp ở sân vận động.",
            ],
            [
                'category' => 'sport',
                'title'    => 'Ngắm siêu xe ở trường đua',
                'image'    => 'images/sport7.jpg',
                'tags'     => ['Nhiếp ảnh'],
                'content'  => "Tiếng động cơ và những đường nét của chiếc xe thể thao khiến mình mê mẩn. Mình chụp vài bức ảnh làm kỷ niệm.\n\nĐây là lần đầu mình đến gần một chiếc siêu xe như vậy.",
            ],

            // Đời sống
            [
                'category' => 'life',
                'title'    => 'Đêm nhạc sống đáng nhớ',
                'image'    => 'images/entertainment1.jpg',
                'tags'     => ['Đời sống'],
                'content'  => "Hàng nghìn người cùng hát vang dưới ánh đèn sân khấu, cảm giác thật khó tả.\n\nMình sẽ còn đi thêm nhiều đêm nhạc như vậy.",
            ],
            [
                'category' => 'life',
                'title'    => 'Học đàn guitar cùng bạn',
                'image'    => 'images/entertainment4.jpg',
                'tags'     => ['Guitar', 'Đời sống'],
                'content'  => "Mình và bạn hẹn nhau tập guitar mỗi cuối tuần. Ban đầu ngón tay đau và bấm hợp âm còn vụng.\n\nNhưng luyện đều mỗi ngày, mình đã chơi được vài bài đơn giản.",
            ],
            [
                'category' => 'life',
                'title'    => 'Đám cưới của người bạn thân',
                'image'    => 'images/entertainment6.jpg',
                'tags'     => ['Đời sống', 'Nhiếp ảnh'],
                'content'  => "Hôm nay mình đi dự đám cưới của người bạn thân. Nhìn bó hoa cưới trong tay cô dâu, mình thấy rất xúc động và vui cho bạn.\n\nChúc hai bạn hạnh phúc.",
            ],
            [
                'category' => 'life',
                'title'    => 'Chân dung bạn mình qua ống kính',
                'image'    => 'images/entertainment10.jpg',
                'tags'     => ['Nhiếp ảnh'],
                'content'  => "Mình thử chụp chân dung bằng ánh sáng ngược. Bức ảnh khá tự nhiên và có chiều sâu.\n\nMình đang học thêm về ánh sáng và bố cục.",
            ],

            // ---- Các bài mới viết thêm ----

            // Du lịch
            [
                'category' => 'travel',
                'title'    => 'Lần đầu đi tàu hỏa một mình, mình đã học được gì',
                'image'    => 'images/world5.jpg',
                'tags'     => ['Du lịch'],
                'content'  => "Trước giờ đi đâu mình cũng có bạn đi cùng, nên lần này tự mua vé tàu đi một mình thấy hơi run. Lên tàu rồi mới biết mọi thứ không đáng sợ như mình nghĩ, ai cũng thân thiện và hay hỏi chuyện.\n\nMình ngồi cạnh cửa sổ gần sáu tiếng, ngắm ruộng đồng lùi dần về phía sau. Lần sau mình sẽ mang theo sách, vì pin điện thoại hết sớm hơn dự tính.",
            ],
            [
                'category' => 'travel',
                'title'    => 'Ba ngày ở Đà Lạt với ngân sách sinh viên',
                'image'    => 'images/world2.jpg',
                'tags'     => ['Du lịch'],
                'content'  => "Cả nhóm bốn người thuê chung một phòng nhỏ, sáng ăn bánh mì, tối ăn đồ nướng ngoài chợ. Tính ra mỗi người tiêu không nhiều mà đi được khá nhiều chỗ.\n\nĐiều mình nhớ nhất không phải là cảnh đẹp mà là buổi tối ngồi uống sữa đậu nành nóng giữa trời lạnh, cả nhóm cười không ngớt.",
            ],
            [
                'category' => 'travel',
                'title'    => 'Dậy từ bốn giờ sáng để săn mây',
                'image'    => 'images/world10.jpg',
                'tags'     => ['Du lịch', 'Nhiếp ảnh'],
                'content'  => "Báo thức reo lúc bốn giờ, mình suýt nữa thì tắt đi ngủ tiếp. May mà bạn đi cùng lôi dậy được. Đường lên đồi còn tối và lạnh, mình mặc hai lớp áo vẫn run.\n\nĐến nơi khoảng nửa tiếng thì mây bắt đầu tràn lên, nắng đầu ngày nhuộm vàng cả thung lũng. Mình đứng chụp quên cả lạnh. Đáng để dậy sớm thật.",
            ],
            [
                'category' => 'travel',
                'title'    => 'Cách mình soạn balo trước mỗi chuyến đi',
                'image'    => 'images/world5.jpg',
                'tags'     => ['Du lịch'],
                'content'  => "Lần đầu đi chơi xa mình mang theo gần như cả tủ quần áo và phải kéo lê một cái vali rất nặng. Giờ mình học cách chỉ mang những thứ thật sự cần.\n\nMình hay lập danh sách trong điện thoại trước một ngày, đồ nào đã bỏ vào thì gạch đi. Cách này đơn giản nhưng giúp mình hầu như không quên gì nữa.",
            ],

            // Học tập
            [
                'category' => 'study',
                'title'    => 'Mình đã hiểu Eloquent Relationship như thế nào',
                'image'    => 'images/business4.jpg',
                'tags'     => ['Laravel', 'Học tập'],
                'content'  => "Lúc đầu mình hay nhầm giữa hasMany và belongsTo. Sau khi vẽ ra giấy là một người dùng có nhiều bài viết, còn mỗi bài viết thuộc về một người dùng, mình mới thấy rõ.\n\nMình cũng học được cách dùng with() để không bị truy vấn lặp lại nhiều lần. Nghe thì nhỏ nhưng trang web chạy nhanh hơn thấy rõ.",
            ],
            [
                'category' => 'study',
                'title'    => 'Lỗi 419 Page Expired làm mình mất một buổi tối',
                'image'    => 'images/business7.jpg',
                'tags'     => ['Laravel', 'Blade'],
                'content'  => "Form của mình cứ gửi đi là hiện 419. Mình kiểm tra route, controller, cả database nhưng không thấy gì sai. Cuối cùng mới nhận ra là quên thêm @csrf vào form.\n\nTừ giờ viết form nào mình cũng thêm @csrf đầu tiên, rồi mới tính đến chuyện khác.",
            ],
            [
                'category' => 'study',
                'title'    => 'Cách mình ghi chép khi học lập trình',
                'image'    => 'images/business1.jpg',
                'tags'     => ['Học tập'],
                'content'  => "Trước đây mình chép gần như nguyên văn bài giảng, sau đó không bao giờ đọc lại. Giờ mình chỉ ghi lại những chỗ mình từng làm sai và cách đã sửa.\n\nCuốn sổ mỏng đi nhiều nhưng mỗi lần mở ra đều có ích. Nó giống một cuốn nhật ký lỗi của chính mình.",
            ],
            [
                'category' => 'study',
                'title'    => 'Ôn thi cuối kỳ: lịch học mình đã thử',
                'image'    => 'images/politics5.jpg',
                'tags'     => ['Học tập', 'Đời sống'],
                'content'  => "Tuần thi mình chia ngày thành ba buổi, mỗi buổi học một môn và nghỉ giữa giờ mười phút. Ban đầu thấy hơi gò bó nhưng càng về sau càng đỡ rối.\n\nĐiều khó nhất là không cầm điện thoại. Mình phải để nó ở phòng khác mới tập trung được.",
            ],

            // Thể thao
            [
                'category' => 'sport',
                'title'    => 'Ba tháng tập chạy: từ một cây số đến năm cây số',
                'image'    => 'images/sport2.jpg',
                'tags'     => ['Chạy bộ'],
                'content'  => "Tuần đầu mình chạy chưa tới một cây đã thở dốc phải đi bộ. Mình chạy xen kẽ đi bộ, mỗi tuần tăng thêm một chút.\n\nĐến tháng thứ ba thì mình chạy liền năm cây không nghỉ. Không nhanh nhưng mình rất vui vì đã không bỏ cuộc.",
            ],
            [
                'category' => 'sport',
                'title'    => 'Giải bóng đá khoa: thua nhưng vui',
                'image'    => 'images/sport5.jpg',
                'tags'     => ['Bóng đá'],
                'content'  => "Đội mình thua 2-3 ở phút cuối, ai cũng tiếc. Nhưng sau trận cả đội kéo nhau đi ăn, kể lại từng pha bóng và cười suốt buổi tối.\n\nMình thấy thắng thua chỉ là một phần, quan trọng là có những người cùng chạy với mình trên sân.",
            ],
            [
                'category' => 'sport',
                'title'    => 'Những bài giãn cơ mình làm sau mỗi buổi tập',
                'image'    => 'images/sport6.jpg',
                'tags'     => ['Chạy bộ'],
                'content'  => "Có hôm mình chạy xong về là nằm luôn, sáng hôm sau chân đau không đi nổi. Từ khi dành năm phút giãn cơ cuối buổi thì đỡ hơn hẳn.\n\nMình chỉ làm vài động tác đơn giản: giãn bắp chân, đùi trước và đùi sau. Nhớ giữ mỗi động tác khoảng ba mươi giây.",
            ],

            // Đời sống
            [
                'category' => 'life',
                'title'    => 'Cuối tuần nấu cơm cùng phòng trọ',
                'image'    => 'images/entertainment4.jpg',
                'tags'     => ['Đời sống'],
                'content'  => "Bốn đứa góp tiền đi chợ, đứa nào biết nấu món gì thì nấu món đó. Kết quả là món canh hơi mặn và cá chiên hơi cháy, nhưng ăn vẫn hết sạch.\n\nMình thấy bữa cơm tự nấu ngon hơn đồ mua ngoài vì có cả tiếng cười kèm theo.",
            ],
            [
                'category' => 'life',
                'title'    => 'Lần đầu mình tự sửa xe đạp',
                'image'    => 'images/entertainment10.jpg',
                'tags'     => ['Đời sống'],
                'content'  => "Xe đạp bị tuột xích giữa đường, mình dắt về rồi xem video hướng dẫn. Mất gần một tiếng, tay lấm đầy dầu nhưng cuối cùng cũng lắp lại được.\n\nCảm giác tự làm được một việc nhỏ thật sự rất đã. Mình định học thêm cách vá xăm.",
            ],
            [
                'category' => 'life',
                'title'    => 'Một buổi chiều chỉ để đọc sách',
                'image'    => 'images/entertainment6.jpg',
                'tags'     => ['Đời sống'],
                'content'  => "Chiều chủ nhật mình tắt thông báo điện thoại, pha một cốc trà và đọc hết hơn nửa cuốn sách đang để dở. Lâu lắm rồi mình mới có một buổi chiều yên tĩnh như thế.\n\nMình tự hứa sẽ dành ra một buổi như vậy mỗi tuần.",
            ],
            [
                'category' => 'life',
                'title'    => 'Mình học cách nói không như thế nào',
                'image'    => 'images/entertainment1.jpg',
                'tags'     => ['Đời sống'],
                'content'  => "Trước đây ai nhờ gì mình cũng nhận, kể cả khi đang bận. Kết quả là việc nào cũng làm dở và mình thấy mệt.\n\nGần đây mình tập từ chối nhẹ nhàng, nói rõ lý do và hẹn lúc khác. Hóa ra mọi người hiểu hơn mình tưởng.",
            ],
        ];

        $categories = Category::pluck('id', 'slug');
        $tags = collect(['Laravel', 'Blade', 'Học tập', 'Du lịch', 'Nhiếp ảnh', 'Chạy bộ', 'Bóng đá', 'Guitar', 'Đời sống'])
            ->map(fn($name) => Tag::create(['name' => $name, 'slug' => Str::slug($name)]))
            ->keyBy('name');

        // các bài được chia lần lượt cho 5 thành viên
        $authors = [$an, $binh, $chau, $dat, $ha];

        foreach ($data as $i => $item) {
            $item['category_id'] = $categories[$item['category']];
            $tagNames = $item['tags'];
            unset($item['category'], $item['tags']);

            $post = Post::create($item + [
                'user_id'      => $authors[$i % count($authors)]->id,
                'published_at' => now()->subDays($i),
            ]);

            // gắn đúng các tag ghi trong từng bài
            $post->tags()->attach($tags->only($tagNames)->pluck('id')->all());
        }
        $first = Post::orderBy('id')->first();

        Comment::create([
            'post_id' => $first->id,
            'user_id' => $binh->id,
            'content' => 'Ảnh đẹp quá, cảm ơn bạn đã chia sẻ!',
        ]);

        Comment::create([
            'post_id' => $first->id,
            'user_id' => $an->id,
            'content' => 'Cảm ơn Bình, cuối tuần sau mình sẽ đi thêm.',
        ]);

        // 1 bài nháp và 1 bài hẹn giờ ĐÃ ĐẾN HẠN (để thử Command posts:publish)
        Post::factory()->draft()->create([
            'user_id'     => $an->id,
            'category_id' => $categories['study'],
            'title'       => 'Dàn ý bài về cách mình tổ chức project Laravel',
            'content'     => "Mục lục dự kiến: cấu trúc thư mục, cách đặt tên route, khi nào nên dùng Form Request.\n\nMình sẽ viết tiếp khi hoàn thành đồ án.",
        ]);

        Post::factory()->scheduled(now()->subMinute())->create([
            'user_id'     => $an->id,
            'category_id' => $categories['travel'],
            'title'       => 'Kế hoạch đi biển cuối tháng',
            'content'     => "Cả nhóm đã chốt ngày đi. Mình chuẩn bị kem chống nắng, mũ và một cuốn sách để đọc trên bãi cát.\n\nBài này mình hẹn giờ đăng sẵn để các bạn trong nhóm xem trước.",
        ]);

        // Bình luận: câu chung cho mọi bài và câu riêng theo từng chủ đề
        $commentPool = [
            'chung' => [
                'Bài viết hay quá, cảm ơn bạn đã chia sẻ!',
                'Đọc xong thấy đồng cảm ghê, mình cũng từng như vậy.',
                'Viết tự nhiên, dễ đọc. Mong bạn ra thêm bài mới.',
                'Mình lưu lại để đọc lại sau nhé.',
                'Cảm ơn bạn, đúng thứ mình đang tìm.',
                'Ảnh minh họa đẹp đó bạn.',
                'Hay quá, chờ phần tiếp theo của bạn.',
                'Mình chia sẻ cho bạn cùng lớp đọc nhé.',
                'Đọc mà thấy có thêm động lực luôn.',
                'Bài ngắn gọn nhưng đủ ý, thích cách bạn kể.',
            ],
            'travel' => [
                'Nhìn ảnh mà muốn xách balo đi ngay. Đi mùa nào là đẹp nhất vậy bạn?',
                'Chỗ này mình chưa đi bao giờ, cho mình xin thêm địa chỉ với.',
                'Chi phí cả chuyến khoảng bao nhiêu vậy bạn?',
                'Cuối năm mình cũng định đi, bạn có gợi ý lịch trình không?',
                'Đi một mình có thấy an toàn không bạn?',
                'Cảnh đẹp thật, bạn chụp bằng điện thoại hay máy ảnh?',
            ],
            'study' => [
                'Mình cũng đang học phần này, cảm ơn bạn đã giải thích dễ hiểu.',
                'Lỗi này mình cũng từng gặp, mất cả buổi mới tìm ra nguyên nhân.',
                'Bạn có thể chia sẻ thêm tài liệu bạn đang đọc không?',
                'Cách ghi chép này hay, mình sẽ thử áp dụng tuần này.',
                'Học nhóm đúng là hiệu quả hơn học một mình thật.',
                'Cảm ơn bạn, mình đã làm theo và chạy được rồi!',
            ],
            'sport' => [
                'Mình cũng muốn bắt đầu chạy bộ, bạn nên khởi động như thế nào vậy?',
                'Kiên trì được như bạn là giỏi lắm rồi, cố lên!',
                'Cuối tuần sau cho mình tham gia với nhé.',
                'Đọc xong mình thấy ngứa chân muốn ra sân luôn.',
                'Bạn có bị đau gối sau khi chạy không? Mình hay bị lắm.',
                'Trận đó tiếc thật, lần sau chắc chắn thắng.',
            ],
            'life' => [
                'Đọc mà thấy ấm lòng, cảm ơn bạn đã kể.',
                'Mình cũng từng trải qua cảm giác y như vậy.',
                'Những khoảnh khắc nhỏ thế này mới là điều đáng nhớ.',
                'Bạn viết chân thật quá, mình rất thích.',
                'Cuối tuần này mình cũng thử làm giống bạn xem sao.',
                'Chúc bạn luôn giữ được tinh thần như vậy nhé.',
            ],
        ];

        $commenters = [$an, $binh, $chau, $dat, $ha];
        $slugById = $categories->flip();

        // mỗi bài đã đăng nhận từ 0 đến 8 bình luận, không trùng câu trong cùng một bài
        foreach (Post::published()->get() as $post) {
            $texts = collect($commentPool['chung'])
                ->merge($commentPool[$slugById[$post->category_id]])
                ->shuffle()
                ->take(rand(0, 8));

            foreach ($texts as $text) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => collect($commenters)->random()->id,
                    'content' => $text,
                ]);
            }
        }
    }
}
