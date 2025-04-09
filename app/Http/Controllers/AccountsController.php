<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\transactions;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($filter)
    {
        $accounts = accounts::where('type', $filter)->orderBy('id', 'asc')->get();

        return view('Finance.accounts.index', compact('accounts', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Finance.accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|unique:accounts,title'
            ],
            [
                'title.required' => "Please Enter Account Title",
                'title.unique'  => "Account with this title already exists"
            ]
        );

        try
        {
            DB::beginTransaction();

                $ref = getRef();
                if($request->type == "Customer")
                {
                    $account = accounts::create(
                        [
                            'title' => $request->title,
                            'type' => $request->type,
                            'category' => $request->category,
                            'contact' => $request->contact,
                            'address' => $request->address,
                        ]
                    );
                }
                else
                {
                    $account = accounts::create(
                        [
                            'title' => $request->title,
                            'type' => $request->type,
                            'category' => $request->category
                        ]
                    );
                }

                if($request->initial > 0)
                {
                    if($request->initialType == '0')
                    {
                        createTransaction($account->id,now(), $request->initial,0, "Initial Amount", $ref);
                    }
                    else
                    {
                        createTransaction($account->id,now(), 0, $request->initial, "Initial Amount", $ref);
                    }
                }
           DB::commit();
           return back()->with('success', "Account Created Successfully");
        }
        catch(\Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, $from, $to)
    {
        $account = accounts::find($id);

        $transactions = transactions::where('accountID', $id)->whereBetween('date', [$from, $to])->get();

        $pre_cr = transactions::where('accountID', $id)->whereDate('date', '<', $from)->sum('cr');
        $pre_db = transactions::where('accountID', $id)->whereDate('date', '<', $from)->sum('db');
        $pre_balance = $pre_cr - $pre_db;

        $cur_cr = transactions::where('accountID', $id)->sum('cr');
        $cur_db = transactions::where('accountID', $id)->sum('db');

        $cur_balance = $cur_cr - $cur_db;

        return view('Finance.accounts.statment', compact('account', 'transactions', 'pre_balance', 'cur_balance', 'from', 'to'));
    }

    public function pdf($id, $from, $to)
    {
        $account = accounts::find($id);

        $transactions = transactions::where('accountID', $id)->whereBetween('date', [$from, $to])->get();

        $pre_cr = transactions::where('accountID', $id)->whereDate('date', '<', $from)->sum('cr');
        $pre_db = transactions::where('accountID', $id)->whereDate('date', '<', $from)->sum('db');
        $pre_balance = $pre_cr - $pre_db;

        $cur_cr = transactions::where('accountID', $id)->sum('cr');
        $cur_db = transactions::where('accountID', $id)->sum('db');

        $cur_balance = $cur_cr - $cur_db;
        $pdf = Pdf::loadview('Finance.accounts.pdf', compact('account', 'transactions', 'pre_balance', 'cur_balance', 'from', 'to'));
    return $pdf->download("Account Statement - $account->id.pdf");
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(accounts $account)
    {
        return view('Finance.accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, accounts $account)
    {
        $request->validate(
            [
                'title' => "required|unique:accounts,title,". $request->accountID,
            ],
            [
                'title.required' => "Please Enter Account Title",
                'title.unique'  => "Account with this title already exists"
            ]
        );
        $account = accounts::find($request->accountID)->update(
            [
                'title' => $request->title,
                'category' => $request->category,
                'contact' => $request->contact ?? null,
                'address' => $request->address ?? null,
            ]
        );

        return redirect()->route('accountsList', $request->type)->with('success', "Account Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(accounts $accounts)
    {
        //
    }
}
