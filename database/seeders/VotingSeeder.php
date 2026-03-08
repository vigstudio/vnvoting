<?php

namespace Database\Seeders;

use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Database\Seeder;

class VotingSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Tạo vote counter user
        User::factory(3)->create([
            'role' => 'vote_counter',
        ]);

        // Tạo election mẫu với positions và candidates
        $election = Election::factory()->create([
            'title' => 'Bầu cử Đại hội Đại biểu Nông dân Việt Nam 2026',
            'description' => 'Kiểm đếm phiếu bầu cho các cấp chức vụ',
            'is_active' => true,
        ]);

        // Tạo positions với các màu phiếu khác nhau
        $positions = [
            [
                'title' => 'Bí thư Hội Nông dân',
                'ballot_color' => '#FF0000', // Đỏ
                'max_votes' => 1,
                'candidates' => ['Nguyễn Văn A', 'Trần Văn B', 'Lê Văn C'],
            ],
            [
                'title' => 'Chủ tịch Hội Nông dân',
                'ballot_color' => '#0000FF', // Xanh dương
                'max_votes' => 1,
                'candidates' => ['Phạm Văn D', 'Hoàng Văn E', 'Vũ Văn F'],
            ],
            [
                'title' => 'Phó Chủ tịch Hội Nông dân',
                'ballot_color' => '#00FF00', // Xanh lá
                'max_votes' => 2,
                'candidates' => ['Đặng Văn G', 'Vũ Văn H', 'Ngô Văn I', 'Lý Văn K'],
            ],
        ];

        foreach ($positions as $index => $positionData) {
            $position = Position::factory()->create([
                'election_id' => $election->id,
                'title' => $positionData['title'],
                'ballot_color' => $positionData['ballot_color'],
                'max_votes' => $positionData['max_votes'],
                'sort_order' => $index,
            ]);

            // Tạo candidates cho position này
            foreach ($positionData['candidates'] as $candidateIndex => $candidateName) {
                Candidate::factory()->create([
                    'position_id' => $position->id,
                    'name' => $candidateName,
                    'sort_order' => $candidateIndex,
                ]);
            }
        }

        $this->command->info('✅ Đã tạo dữ liệu mẫu:');
        $this->command->info('   - 1 Admin user (admin@example.com)');
        $this->command->info('   - 3 Vote counter users');
        $this->command->info('   - 1 Election với 3 Positions');
        $this->command->info('   - 11 Candidates');
    }
}
