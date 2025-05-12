<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function customerPage(Request $request)
    {
        $user_id = $request->header('id');
        $customers = Customer::where('user_id', $user_id)->get();
        return Inertia::render('CustomerPage', ['customers' => $customers]);
    }

    public function customerSavePage(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->query('id');
        $customer = Customer::where('id', $id)->where('user_id', $user_id)->first();
        return Inertia::render('CustomerSavePage', ['customer' => $customer]);
    }
    public function createCustomer(Request $request)
    {
        $user_id = $request->header('id');

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:customers,email',
            'mobile' => 'required',
        ]);

        Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'user_id' => $user_id
        ]);
        $data = ['message' => 'Customer created successfully', 'status' => true, 'error' => ''];
        return redirect('/CustomerPage')->with($data);
    }

    public function customerUpdate(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->input('id');
        Customer::where('id', $id)->where('user_id', $user_id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
        ]);
        $data = ['message' => 'Customer updated successfully', 'status' => true, 'error' => ''];
        return redirect('/CustomerPage')->with($data);
    } 

    public function customerDelete(Request $request, $id)
    {
        $user_id = $request->header('id');
        Customer::where('user_id', $user_id)->where('id', $id)->delete();
        $data = ['message' => 'Customer Deleted successfully', 'status' => true, 'error' => ''];
        return redirect('/CustomerPage')->with($data);
    }
    
}
