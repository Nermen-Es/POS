<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangeStatusInvoice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $invoices = Invoice::all();

       foreach($invoices as $invoice){

            if($invoice->total_amount == $invoice->paid_amount){
                $invoice->update([
                    'status' => 'Paid'
                ]);

                return $next($request);
            }//end if
            else{
                $invoice->update([
                    'status' => 'Partially'
                ]);
                return $next($request);
            }//end else
    }//end forech

    }
}
