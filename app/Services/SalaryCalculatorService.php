<?php

namespace App\Services;

use App\Models\User;
use App\Models\Salary;
use App\Models\SalarySlip;
use App\Models\SalaryComponent;
use App\Models\UserSalaryComponent;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalaryCalculatorService
{
    /**
     * Generate slip gaji untuk user pada periode tertentu
     */
    public function generateSlip(User $user, int $tahun, int $bulan): SalarySlip
    {
        // Cek apakah sudah ada slip untuk periode ini
        $existingSlip = SalarySlip::forUser($user->id)
            ->forPeriode($tahun, $bulan)
            ->first();

        if ($existingSlip && !$existingSlip->isDraft()) {
            throw new \Exception('Slip gaji untuk periode ini sudah ada dan tidak dapat diubah.');
        }

        // Ambil data gaji pokok
        $salary = Salary::forUser($user->id)
            ->active()
            ->current()
            ->first();

        if (!$salary) {
            throw new \Exception('Data gaji pokok tidak ditemukan untuk user ini.');
        }

        // Hitung periode
        $periodeStart = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $periodeEnd = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        // Hitung kehadiran dari tabel absensi
        $attendance = $this->calculateAttendance($user->id, $periodeStart, $periodeEnd);

        // Hitung tunjangan
        $tunjanganData = $this->calculateTunjangan($user, $salary);

        // Hitung potongan
        $potonganData = $this->calculatePotongan($user, $salary, $attendance);

        // Hitung lembur
        $lemburData = $this->calculateLembur($user->id, $periodeStart, $periodeEnd);

        // Total perhitungan
        $gajiPokok = (float) $salary->gaji_pokok;
        $totalTunjangan = $tunjanganData['total'] + $lemburData['upah'];
        $totalPotongan = $potonganData['total'];
        $gajiKotor = $gajiPokok + $totalTunjangan;
        $gajiBersih = $gajiKotor - $totalPotongan;

        // Buat atau update slip
        $slipData = [
            'user_id' => $user->id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'periode_start' => $periodeStart,
            'periode_end' => $periodeEnd,
            'gaji_pokok' => $gajiPokok,
            'total_tunjangan' => $totalTunjangan,
            'total_potongan' => $totalPotongan,
            'gaji_kotor' => $gajiKotor,
            'gaji_bersih' => $gajiBersih,
            'hari_kerja' => $attendance['hari_kerja'],
            'hari_hadir' => $attendance['hari_hadir'],
            'hari_izin' => $attendance['hari_izin'],
            'hari_sakit' => $attendance['hari_sakit'],
            'hari_alpha' => $attendance['hari_alpha'],
            'jam_lembur' => $lemburData['jam'],
            'upah_lembur' => $lemburData['upah'],
            'tunjangan_detail' => $tunjanganData['detail'],
            'potongan_detail' => $potonganData['detail'],
            'status' => 'draft',
        ];

        if ($existingSlip) {
            $existingSlip->update($slipData);
            return $existingSlip->fresh();
        }

        return SalarySlip::create($slipData);
    }

    /**
     * Hitung data kehadiran dari tabel absensi
     */
    private function calculateAttendance(int $userId, Carbon $start, Carbon $end): array
    {
        $absensi = DB::table('absensi')
            ->where('user_id', $userId)
            ->whereBetween('tanggal', [$start, $end])
            ->get();

        $hariKerja = $this->getWorkingDays($start, $end);
        $hariHadir = $absensi->whereNotNull('jam_masuk')->count();
        
        // TODO: Sesuaikan dengan struktur tabel absensi Anda
        // Asumsi ada kolom 'status' dengan nilai: hadir, izin, sakit, alpha
        $hariIzin = 0;  // Hitung dari status izin
        $hariSakit = 0; // Hitung dari status sakit
        $hariAlpha = $hariKerja - ($hariHadir + $hariIzin + $hariSakit);

        return [
            'hari_kerja' => $hariKerja,
            'hari_hadir' => $hariHadir,
            'hari_izin' => $hariIzin,
            'hari_sakit' => $hariSakit,
            'hari_alpha' => max(0, $hariAlpha),
        ];
    }

    /**
     * Hitung jumlah hari kerja (exclude weekend)
     */
    private function getWorkingDays(Carbon $start, Carbon $end): int
    {
        $workingDays = 0;
        $current = $start->copy();

        while ($current->lte($end)) {
            // Skip Sabtu (6) dan Minggu (0)
            if (!in_array($current->dayOfWeek, [0, 6])) {
                $workingDays++;
            }
            $current->addDay();
        }

        return $workingDays;
    }

    /**
     * Hitung tunjangan
     */
    private function calculateTunjangan(User $user, Salary $salary): array
    {
        $gajiPokok = (float) $salary->gaji_pokok;
        $total = 0;
        $detail = [];

        // Tunjangan tetap dari master gaji
        $tunjanganTetap = [
            'Tunjangan Jabatan' => $salary->tunjangan_jabatan,
            'Tunjangan Transport' => $salary->tunjangan_transport,
            'Tunjangan Makan' => $salary->tunjangan_makan,
            'Tunjangan Kehadiran' => $salary->tunjangan_kehadiran,
            'Tunjangan Kesehatan' => $salary->tunjangan_kesehatan,
            'Tunjangan Lainnya' => $salary->tunjangan_lainnya,
        ];

        foreach ($tunjanganTetap as $nama => $nilai) {
            if ($nilai > 0) {
                $detail[] = [
                    'nama' => $nama,
                    'nilai' => (float) $nilai,
                    'tipe' => 'nominal',
                ];
                $total += (float) $nilai;
            }
        }

        // Tunjangan dinamis dari komponen
        $komponenTunjangan = UserSalaryComponent::forUser($user->id)
            ->active()
            ->with('salaryComponent')
            ->get()
            ->filter(function ($item) {
                return $item->salaryComponent->isTunjangan();
            });

        foreach ($komponenTunjangan as $userComponent) {
            $component = $userComponent->salaryComponent;
            $nilai = $userComponent->getValue($gajiPokok);

            $detail[] = [
                'nama' => $component->nama_komponen,
                'nilai' => $nilai,
                'tipe' => $component->tipe_perhitungan,
                'is_taxable' => $component->is_taxable,
            ];
            $total += $nilai;
        }

        return [
            'total' => $total,
            'detail' => $detail,
        ];
    }

    /**
     * Hitung potongan
     */
    private function calculatePotongan(User $user, Salary $salary, array $attendance): array
    {
        $gajiPokok = (float) $salary->gaji_pokok;
        $total = 0;
        $detail = [];

        // Potongan dinamis dari komponen
        $komponenPotongan = UserSalaryComponent::forUser($user->id)
            ->active()
            ->with('salaryComponent')
            ->get()
            ->filter(function ($item) {
                return $item->salaryComponent->isPotongan();
            });

        foreach ($komponenPotongan as $userComponent) {
            $component = $userComponent->salaryComponent;
            $nilai = $userComponent->getValue($gajiPokok);

            $detail[] = [
                'nama' => $component->nama_komponen,
                'nilai' => $nilai,
                'tipe' => $component->tipe_perhitungan,
            ];
            $total += $nilai;
        }

        // Potongan alpha
        if ($attendance['hari_alpha'] > 0) {
            $potonganAlpha = $this->getPotonganAlphaPerHari() * $attendance['hari_alpha'];
            $detail[] = [
                'nama' => 'Potongan Alpha',
                'nilai' => $potonganAlpha,
                'tipe' => 'nominal',
                'keterangan' => $attendance['hari_alpha'] . ' hari',
            ];
            $total += $potonganAlpha;
        }

        return [
            'total' => $total,
            'detail' => $detail,
        ];
    }

    /**
     * Hitung lembur
     */
    private function calculateLembur(int $userId, Carbon $start, Carbon $end): array
    {
        // Hitung jam lembur dari absensi
        // Asumsi: lembur jika jam_keluar > 17:00
        $jamLembur = 0;
        $upahPerJam = $this->getUpahLemburPerJam();

        $absensi = DB::table('absensi')
            ->where('user_id', $userId)
            ->whereBetween('tanggal', [$start, $end])
            ->get();

        foreach ($absensi as $abs) {
            if ($abs->jam_keluar) {
                $jamKeluar = Carbon::parse($abs->jam_keluar);
                $jamNormal = Carbon::parse($abs->tanggal . ' 17:00:00');
                
                if ($jamKeluar->gt($jamNormal)) {
                    $selisih = $jamKeluar->diffInHours($jamNormal);
                    $jamLembur += $selisih;
                }
            }
        }

        return [
            'jam' => $jamLembur,
            'upah' => $jamLembur * $upahPerJam,
        ];
    }

    /**
     * Get setting values
     */
    private function getUpahLemburPerJam(): float
    {
        $setting = DB::table('salary_settings')
            ->where('key', 'upah_lembur_per_jam')
            ->value('value');
        return (float) ($setting ?? 50000);
    }

    private function getPotonganAlphaPerHari(): float
    {
        $component = SalaryComponent::where('nama_komponen', 'Potongan Alpha')
            ->first();
        return $component ? (float) $component->nilai : 100000;
    }

    /**
     * Generate slip untuk semua karyawan aktif
     */
    public function generateSlipForAllUsers(int $tahun, int $bulan): array
    {
        $users = User::where('status', 'aktif')->get();
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($users as $user) {
            try {
                $slip = $this->generateSlip($user, $tahun, $bulan);
                $results['success'][] = [
                    'user' => $user->name,
                    'slip_number' => $slip->slip_number,
                ];
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'user' => $user->name,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}