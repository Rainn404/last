<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MahasiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $status;
    protected $isTemplate;

    public function __construct($status = null, $isTemplate = false)
    {
        $this->status = $status;
        $this->isTemplate = $isTemplate;
    }

    public function collection()
    {
        if ($this->isTemplate) {
            // Return empty collection for template
            return collect([]);
        }

        $query = Mahasiswa::query();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->orderBy('nim')->get();
    }

    public function headings(): array
    {
        if ($this->isTemplate) {
            return [
                'nim',
                'nama',
                'angkatan',
                'status',
                'ipk',
                'juara',
                'tingkatan',
                'keterangan'
            ];
        }

        return [
            'NIM',
            'NAMA MAHASISWA',
            'ANGKATAN',
            'STATUS',
            'IPK',
            'JUARA',
            'TINGKATAN',
            'KETERANGAN',
            'TANGGAL DIBUAT',
            'TANGGAL DIPERBARUI'
        ];
    }

    public function map($mahasiswa): array
    {
        if ($this->isTemplate) {
            // Return sample data for template
            return [
                '1234567890', // nim
                'Nama Mahasiswa', // nama
                '2021', // angkatan
                'Aktif', // status
                '3.5', // ipk
                '1', // juara
                '3', // tingkatan (1=Internal, 3=Kabupaten, 5=Provinsi, 7=Nasional, 9=Internasional)
                '1' // keterangan (1=Non-Akademik, 3=Akademik)
            ];
        }

        return [
            $mahasiswa->nim,
            $mahasiswa->nama,
            $mahasiswa->angkatan ?? '-',
            $mahasiswa->status ?? '-',
            $mahasiswa->ipk ?? '-',
            $mahasiswa->juara ?? '-',
            $mahasiswa->tingkatan ?? '-',
            $mahasiswa->keterangan ?? '-',
            $mahasiswa->created_at ? $mahasiswa->created_at->format('d/m/Y H:i') : '-',
            $mahasiswa->updated_at ? $mahasiswa->updated_at->format('d/m/Y H:i') : '-'
        ];
    }
            $mahasiswa->angkatan,
            $mahasiswa->status,
            $mahasiswa->created_at ? $mahasiswa->created_at->format('d/m/Y H:i') : '-',
            $mahasiswa->updated_at ? $mahasiswa->updated_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $columnRange = $this->isTemplate ? 'A:D' : 'A:F';

        return [
            // Style untuk header
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4e73df']
                ]
            ],

            // Style untuk seluruh tabel
            $columnRange => [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ]
            ],

            // Auto size columns
            'A' => ['width' => 15],
            'B' => ['width' => 30],
            'C' => ['width' => 10],
            'D' => ['width' => 12],
        ];
    }

    public function title(): string
    {
        return 'DATA_MAHASISWA';
    }
}