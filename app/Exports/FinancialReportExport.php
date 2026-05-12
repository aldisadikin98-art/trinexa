<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class FinancialReportExport implements WithMultipleSheets
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate ?? Carbon::now()->startOfMonth();
        $this->endDate = $endDate ?? Carbon::now()->endOfMonth();
    }

    public function sheets(): array
    {
        return [
            new SummarySheet($this->startDate, $this->endDate),
            new IncomesSheet($this->startDate, $this->endDate),
            new ExpensesSheet($this->startDate, $this->endDate),
        ];
    }
}

class SummarySheet implements WithTitle, WithHeadings, WithStyles, FromCollection
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function headings(): array
    {
        return [
            ['LAPORAN KEUANGAN TRINEXA'],
            ['Periode:', $this->startDate->format('d M Y') . ' - ' . $this->endDate->format('d M Y')],
            [],
            ['Kategori', 'Total (Rp)']
        ];
    }

    public function collection()
    {
        $incomes = Transaction::whereIn('status', ['paid', 'diproses', 'dikirim', 'selesai'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->sum('total_amount');

        $expenses = Expense::whereBetween('date', [$this->startDate, $this->endDate])
            ->sum('amount');

        return collect([
            ['Total Pemasukan', $incomes],
            ['Total Pengeluaran', $expenses],
            ['Laba Bersih', $incomes - $expenses],
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            4 => ['font' => ['bold' => true]],
        ];
    }
}

class IncomesSheet implements WithTitle, WithHeadings, WithMapping, FromCollection
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Detail Pemasukan';
    }

    public function headings(): array
    {
        return ['ID Transaksi', 'Tanggal', 'Nama Pembeli', 'Metode Pembayaran', 'Total Amount'];
    }

    public function collection()
    {
        return Transaction::with('user')
            ->whereIn('status', ['paid', 'diproses', 'dikirim', 'selesai'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();
    }

    public function map($transaction): array
    {
        return [
            $transaction->receipt_number,
            $transaction->created_at->format('d M Y H:i'),
            $transaction->user->name ?? 'Guest',
            $transaction->payment_method,
            $transaction->total_amount,
        ];
    }
}

class ExpensesSheet implements WithTitle, WithHeadings, WithMapping, FromCollection
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Detail Pengeluaran';
    }

    public function headings(): array
    {
        return ['Tanggal', 'Kategori', 'Keterangan', 'Admin', 'Amount'];
    }

    public function collection()
    {
        return Expense::with('admin')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->get();
    }

    public function map($expense): array
    {
        return [
            $expense->date->format('d M Y'),
            ucfirst($expense->category),
            $expense->description,
            $expense->admin->name ?? 'N/A',
            $expense->amount,
        ];
    }
}
