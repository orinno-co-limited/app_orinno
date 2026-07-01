<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['pageTitle'] = __("User");
        $data['subUserActiveClass'] = 'active';
        $data['users'] = User::all();
        return view('owner.setting.user')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try{
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->contact_number = $request->contact_number;
            $user->status = $request->status;
            $user->save();
        }
        catch(\Exception $e){
            return  redirect()->back()->with('success', $e->getMessage());
        }

        return  redirect()->back()->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        try{
            $user = User::find($id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;
            $user->contact_number = $request->contact_number;
            $user->status = $request->status;
            $user->save();
        }
        catch(\Exception $e){
            return  redirect()->back()->with('success', $e->getMessage());
        }

        return  redirect()->back()->with('success', 'Created Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $user = User::findOrFail($id);
            if(auth()->id() != $user->id && User::whereRole(USER_ROLE_OWNER)->first()->id != $user->id ){
                $user->delete();
                return  redirect()->back()->with('success', __('Deleted Successfully'));
            }

            return redirect()->back()->with('info', __('You can\'t delete this data'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('info', $e->getMessage());
        }

    }
}
