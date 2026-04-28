<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::query()->delete();

        Product::query()->insert([
            [
                'slug' => 'solstice-floor-lamp',
                'name' => 'Đèn sàn Solstice',
                'category' => 'Ánh sáng',
                'tagline' => 'Thép ánh đồng, chụp vải linen ấm và quầng sáng hổ phách dài.',
                'description' => 'Mẫu đèn đọc sách cao dáng thanh, tạo vùng sáng dịu cho phòng khách, studio và góc thư giãn. Thân đèn giữ nét kiến trúc gọn gàng, còn chụp linen mang lại cảm giác ấm và ở lâu không chán.',
                'price' => 10900000.00,
                'compare_price' => 12400000.00,
                'rating' => 4.9,
                'reviews_count' => 38,
                'badge' => 'Bán chạy',
                'image_url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1200&q=80',
                ]),
                'highlights' => json_encode([
                    'Đế đá nặng cho cảm giác đứng vững và chắc tay',
                    'Công tắc kéo bằng đồng, tương thích bóng LED 2700K',
                    'Thân đèn sơn tĩnh điện với chi tiết ánh đồng chải xước',
                ]),
                'specs' => json_encode([
                    'Hoàn thiện' => 'Đồng chải xước và linen',
                    'Chiều cao' => '162 cm',
                    'Giao hàng' => 'Gửi trong 3 ngày làm việc',
                ]),
                'is_featured' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'vale-boucle-lounge-chair',
                'name' => 'Ghế lounge Vale Bouclé',
                'category' => 'Nội thất',
                'tagline' => 'Dáng ngồi thấp, phom uốn khối và khung sồi sẫm màu.',
                'description' => 'Vale được làm cho những buổi tối chậm rãi: đệm dày, tay cong mềm và độ ngả vừa đủ để thư giãn nhưng vẫn giữ tư thế đẹp. Mẫu ghế đem cảm giác gallery vào không gian ở hằng ngày.',
                'price' => 22800000.00,
                'compare_price' => 24900000.00,
                'rating' => 4.8,
                'reviews_count' => 24,
                'badge' => 'Studio chọn',
                'image_url' => 'https://images.unsplash.com/photo-1501045661006-fcebe0257c3f?auto=format&fit=crop&w=1200&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1501045661006-fcebe0257c3f?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                ]),
                'highlights' => json_encode([
                    'Vải bouclé bền cho sử dụng hằng ngày',
                    'Khung gỗ sồi sấy khô phủ dầu mờ',
                    'Độ sâu mặt ngồi tối ưu cho đọc sách và lounge',
                ]),
                'specs' => json_encode([
                    'Chất liệu' => 'Gỗ sồi, foam, bouclé',
                    'Bề ngang' => '84 cm',
                    'Dịch vụ' => 'Có hỗ trợ giao và lắp đặt tận nơi',
                ]),
                'is_featured' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'tidal-pour-over-set',
                'name' => 'Bộ pour-over Tidal',
                'category' => 'Gốm sứ',
                'tagline' => 'Phễu lọc và bình server stoneware phủ men muối biển mờ.',
                'description' => 'Bộ pha cà phê hai món dành cho những buổi sáng có nhịp chậm. Phễu lọc đặt gọn vào bình server, giúp nghi thức pha cà phê sạch sẽ, gọn và đủ đẹp để trưng ngay trên kệ bếp.',
                'price' => 2950000.00,
                'compare_price' => null,
                'rating' => 4.9,
                'reviews_count' => 67,
                'badge' => 'Mới',
                'image_url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1200&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1445116572660-236099ec97a0?auto=format&fit=crop&w=1200&q=80',
                ]),
                'highlights' => json_encode([
                    'Stoneware hoàn thiện thủ công với độ loang men nhẹ',
                    'Tương thích giấy lọc tiêu chuẩn',
                    'Tay cầm cân bằng, miệng rót chống nhỏ giọt',
                ]),
                'specs' => json_encode([
                    'Dung tích' => '700 ml',
                    'Bảo quản' => 'Dùng được với máy rửa chén',
                    'Xuất xứ' => 'Sản xuất tại Kyoto',
                ]),
                'is_featured' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'dune-linen-throw',
                'name' => 'Khăn choàng linen Dune',
                'category' => 'Vải vóc',
                'tagline' => 'Sợi lanh thoáng nhẹ với dải clay đã giặt mềm.',
                'description' => 'Khăn choàng khổ lớn dành cho khí hậu ấm và những không gian thích layering. Chất liệu nhẹ, thoáng và có cảm giác đã dùng quen ngay từ ngày đầu tiên.',
                'price' => 2300000.00,
                'compare_price' => 2750000.00,
                'rating' => 4.7,
                'reviews_count' => 44,
                'badge' => null,
                'image_url' => 'https://images.unsplash.com/photo-1517705008128-361805f42e86?auto=format&fit=crop&w=1200&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1517705008128-361805f42e86?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                ]),
                'highlights' => json_encode([
                    'Lanh châu Âu đã garment-wash',
                    'Mép tua mềm cho cảm giác thoải mái tự nhiên',
                    'Đủ nhẹ để dùng quanh năm',
                ]),
                'specs' => json_encode([
                    'Kích thước' => '140 x 200 cm',
                    'Màu sắc' => 'Cát nhạt và clay',
                    'Bảo quản' => 'Giặt máy nước lạnh',
                ]),
                'is_featured' => false,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'halo-travertine-side-table',
                'name' => 'Bàn phụ travertine Halo',
                'category' => 'Nội thất',
                'tagline' => 'Khối đá tròn tối giản cho sofa, giường ngủ và góc đọc sách.',
                'description' => 'Bàn phụ kích thước gọn với mặt travertine vân tự nhiên và chân trụ tạo bóng đổ đẹp. Những lỗ rỗ và chuyển sắc nhỏ giúp mỗi chiếc đều có cá tính riêng.',
                'price' => 14600000.00,
                'compare_price' => null,
                'rating' => 4.8,
                'reviews_count' => 19,
                'badge' => 'Giới hạn',
                'image_url' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                ]),
                'highlights' => json_encode([
                    'Mặt đá travertine honed',
                    'Đế có đệm cao su cho sàn cứng',
                    'Dùng đẹp như bàn phụ, bục trưng bày hoặc tab đầu giường',
                ]),
                'specs' => json_encode([
                    'Loại đá' => 'Travertine Roman',
                    'Chiều cao' => '48 cm',
                    'Khối lượng' => '21 kg',
                ]),
                'is_featured' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'ember-reed-diffuser',
                'name' => 'Tinh dầu que Ember',
                'category' => 'Hương thơm',
                'tagline' => 'Gỗ tuyết tùng cháy nhẹ, trà đen và cam bergamot dịu.',
                'description' => 'Mùi hương không gian thiên gỗ, khô và sâu, phù hợp cho lối vào, phòng ngủ hoặc studio. Vừa đủ sáng để căn phòng thoáng hơn nhưng vẫn giữ được chiều sâu.',
                'price' => 1680000.00,
                'compare_price' => null,
                'rating' => 4.8,
                'reviews_count' => 83,
                'badge' => 'Vừa về',
                'image_url' => 'https://images.unsplash.com/photo-1616628182509-6cbf9a4bc5c6?auto=format&fit=crop&w=1200&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1616628182509-6cbf9a4bc5c6?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1616628188459-fc8e5c714d55?auto=format&fit=crop&w=1200&q=80',
                ]),
                'highlights' => json_encode([
                    'Nền khuếch tán không cồn',
                    'Thời gian hương kéo dài khoảng 14 tuần',
                    'Thân chai thủy tinh khói với nắp mờ',
                ]),
                'specs' => json_encode([
                    'Tầng hương' => 'Tuyết tùng, trà đen, bergamot',
                    'Dung tích' => '180 ml',
                    'Cách dùng' => 'Khuếch tán liên tục',
                ]),
                'is_featured' => false,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'meridian-storage-basket',
                'name' => 'Giỏ lưu trữ Meridian',
                'category' => 'Lưu trữ',
                'tagline' => 'Cói đan tay với cổ da giữ phom chắc và gọn.',
                'description' => 'Một món lưu trữ đủ đẹp để đặt giữa không gian mở. Hợp để đựng khăn, tạp chí, đĩa than hoặc những vật dụng hằng ngày mà không khiến căn phòng trở nên quá công năng.',
                'price' => 1920000.00,
                'compare_price' => 2180000.00,
                'rating' => 4.6,
                'reviews_count' => 31,
                'badge' => null,
                'image_url' => 'https://images.unsplash.com/photo-1517705008128-361805f42e86?auto=format&fit=crop&w=1200&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1517705008128-361805f42e86?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                ]),
                'highlights' => json_encode([
                    'Thân giỏ đan bằng cói sông',
                    'Chi tiết da thuộc thực vật',
                    'Vòng thép ẩn giúp thành giỏ giữ phom đẹp',
                ]),
                'specs' => json_encode([
                    'Đường kính' => '42 cm',
                    'Chiều cao' => '38 cm',
                    'Sử dụng' => 'Trong nhà',
                ]),
                'is_featured' => false,
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'aster-stoneware-bowl-set',
                'name' => 'Bộ bát stoneware Aster',
                'category' => 'Gốm sứ',
                'tagline' => 'Bốn bát gốm tạo hình tay cho trái cây, mì và món dùng chung.',
                'description' => 'Bộ bát xếp gọn với lớp men ngà lấm tấm và chiều sâu vừa đủ cho bữa ăn hằng ngày. Cầm nhẹ tay nhưng vẫn chắc chắn cho nhịp dùng liên tục.',
                'price' => 2150000.00,
                'compare_price' => null,
                'rating' => 4.9,
                'reviews_count' => 52,
                'badge' => 'Quà tặng yêu thích',
                'image_url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1200&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1464306076886-da185f6a9d05?auto=format&fit=crop&w=1200&q=80',
                ]),
                'highlights' => json_encode([
                    'Set 4 bát xếp chồng gọn',
                    'Men ngà lấm tấm với chân gốm thô',
                    'Trọng lượng cân bằng cho dùng mỗi ngày',
                ]),
                'specs' => json_encode([
                    'Bộ gồm' => '4 bát',
                    'Đường kính' => '16 cm',
                    'Bảo quản' => 'Dùng được lò vi sóng và máy rửa chén',
                ]),
                'is_featured' => false,
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
