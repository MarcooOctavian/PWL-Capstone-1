<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    /**
     * Mengunduh data semua transaksi dalam format Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new TransactionsExport, 'transactions_report.xlsx');
    }

    /**
     * Mengunduh data semua transaksi dalam format PDF.
     */
    public function exportPdf()
    {
        $transactions = Transaction::with(['user', 'tickets.typeTicket.event'])->latest()->get();

        $pdf = Pdf::loadView('admin.exports.transactions_pdf', compact('transactions'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('transactions_report.pdf');
    }
}
