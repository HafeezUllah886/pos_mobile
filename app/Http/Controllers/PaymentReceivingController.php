<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\paymentReceiving;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentReceivingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receivings = paymentReceiving::orderBy('id', 'desc')->get();
        $froms = accounts::where('type', '!=', 'Business')->get();
        $accounts = accounts::Business()->get();

        return view('Finance.receiving.index', compact('receivings', 'froms', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $ref = getRef();
            paymentReceiving::create(
                [
                    'fromID' => $request->fromID,
                    'toID' => $request->accountID,
                    'userID' => auth()->user()->id,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            createTransaction($request->accountID, $request->date, $request->amount, 0, "Amount Received <br>" . $request->notes, $ref);
            createTransaction($request->fromID, $request->date, 0, $request->amount, "Amount Received <bt>" . $request->notes, $ref);

            DB::commit();
            return back()->with('success', 'Receipt Saved');
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $receiving = paymentReceiving::find($id);
        return view('Finance.receiving.receipt', compact('receiving'));
    }

    public function pdf($id)
    {
        $receiving = paymentReceiving::find($id);
        $pdf = Pdf::loadview('Finance.receiving.pdf', compact('receiving'));
        $pdf->set_paper('letter', 'landscape');
        return $pdf->download("Receiving - $receiving->refID.pdf");
    }

    public function edit(paymentReceiving $paymentReceiving)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, paymentReceiving $paymentReceiving)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(paymentReceiving $paymentReceiving)
    {
        //
    }
}
