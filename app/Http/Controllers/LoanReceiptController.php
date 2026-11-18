<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanReceiptController extends Controller
{
    public function show(Loan $loan)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        abort_unless($loan->payment_status === 'paid' && (float) $loan->fine > 0, 404);

        $loan->load(['user', 'book']);
        $logoPath = public_path('images/logo_smk.jpg');
        $logoData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));

        $pdf = Pdf::loadView('receipts.fine-payment', compact('loan', 'logoData'))
            ->setPaper('A5', 'portrait');

        return $pdf->stream('Bukti_Pembayaran_Denda_TRX-' . str_pad($loan->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}
