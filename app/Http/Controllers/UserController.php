<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ResisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = User::all();
        return response()->json([
            'data' => $items
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResisterRequest $request)
    {
        $hash = Hash::make($request->password);
        $item_content = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $hash,
        ];
        // $update = [
        //     'authority' => 1,
        //     'verify_email' => 1,
        // ];
        $item = User::create($item_content);
        
        // $id = User::where("email", $request->email)->first();
        // $item2 = User::where('id', $id)->update($update);
        // if ($item2) {
        //     return response()->json([
        //         'data' => $item
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'message' => 'Not found',
        //     ], 404);
        // }
        return response()->json([
            'data' => $item
        ], 201);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // $user->shop_name = $user->shop->name;
        $item = $user;
        // Log::info($user);
        if ($item) {
            return response()->json([
                'data' => $item,
                'shop_data' => $item->shop
            ], 200);
        } else {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, User $user)
    // {
    //     $update = [
    //         'authority' => 1,
    //         'verify_email' => 1
    //     ];
    //     $item = User::where('id', $user->id)->update($update);
    //     if ($item) {
    //         return response()->json([
    //             'message' => 'Updated successfully',
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'Not found',
    //         ], 404);
    //     }
    // }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $item = User::where('id', $user->id)->delete();
        if ($item) {
            return response()->json([
                'message' => 'Deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }
    }
}