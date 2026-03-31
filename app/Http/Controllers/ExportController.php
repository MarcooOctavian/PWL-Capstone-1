<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new TransactionsExport, 'transactions_report.xlsx');
    }

    public function exportPdf()
    {
        $transactions = Transaction::with('user')->latest()->get();
        // Set paper to A4 portrait
        $pdf = Pdf::loadView('admin.exports.transactions_pdf', compact('transactions'))->setPaper('a4', 'portrait');
        
        return $pdf->download('transactions_report.pdf');
    }
}
