<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Requests\ShopEditRequest;
use App\Http\Requests\ShopCreateRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
// use Strage;

class ShopController extends Controller
{
    /**v-if="item.answer === 0"
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Shop::all();
        foreach ($items as $item) {
            $item->area_name = $item->area->area_name;
            $item->genre_name = $item->genre->genre_name;
            $item->favorites = $item->getFavorites;
            $item->reservations = $item->getReservations;
            foreach ($item->getReservations as $item2)  {
                $item2->user_name = $item2->user->name;
            }

            $evaluated_result = 0;
            $count = 0;
            $comment_count = 0;
            // $comments = [];
            foreach ($item->getEvaluations as $evaluation) {
                if ($evaluation->answer === 1 && $evaluation->evaluation !== null) {
                    $evaluated_result = $evaluated_result + $evaluation->evaluation;
                    $count ++;
                    if ($evaluation->comment !== null){
                        $comment_count++;
                    }
                }
                // if ($evaluation->answer === 1 && $evaluation->comment !== null) {
                //     $comments.array_push($evaluation->comment);
                // }
            }
            if( $count !== 0){
            $result = $evaluated_result / (5 * $count);
            $item->evaluations = $result;
            } else {
                $item->evaluations = 0;
            }
            $item->comments = $comment_count;
        };
        return response()->json([
            'data' => $items,
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShopCreateRequest $request)
    {
        $picture = $request->file('pic_path');
        $picture_name = $picture->getClientOriginalName();
        // $picture->storeAs('public', $picture_name);
        Storage::disk('s3')->putFileAs('/', $picture, $picture_name);
        $pic_path = "https://advance-pic-backet.s3.ap-northeast-1.amazonaws.com/".$picture_name;

        $area_id = Area::where('area_name', $request->area_name)->first();
        $genre_id = Genre::where('genre_name', $request->genre_name)->first();
        $item_content = [
            'area_id' => $area_id->id,
            'genre_id' => $genre_id->id,
            'shop_name' => $request->shop_name,
            'description' => $request->description,
            'pic_path' => $pic_path,
        ];
        Shop::create($item_content);

        return response()->json([
            'data' => $item_content
        ], 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        $evaluated_result = 0;
        $count = 0;
        foreach ($shop->getEvaluations as $evaluation) {
            if ($evaluation->answer === 1 && $evaluation->evaluation !== null) {
                $evaluated_result = $evaluated_result + $evaluation->evaluation;
                $count++;
            }
        }
        if ($count !== 0) {
            $result = $evaluated_result / (5 * $count);
            $shop->evaluations = $result;
            $shop->count = $count;
        } else {
            $shop->evaluations = 0;
        }
        $item = [
            'shop_name' => $shop->shop_name,
            'description' => $shop->description,
            'pic_path' => $shop->pic_path,
            'area_name' => $shop->area->area_name,
            'genre_name' => $shop->genre->genre_name,
            'evaluations' => $shop->evaluations,
            'comments' => $shop->getEvaluations,
            'count' => $shop->count,
        ];
        return response()->json([
            'data' => $item,
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(ShopEditRequest $request, Shop $shop)
    {
        $area_name = Area::where('area_name', $request->area_name)->first();
        $genre_name = Genre::where('genre_name', $request->genre_name)->first();
        // Log::info($request->pic_path);
        if($request->pic_path === null) {
            $update = [
                'shop_name' => $request->shop_name,
                'description' => $request->description,
                'area_id' => $area_name->id,
                'genre_id' => $genre_name->id,
            ];
        } else {
            $picture = $request->file('pic_path');
            $picture_name = $picture->getClientOriginalName();
            // $picture->storeAs('public', $picture_name);
            // $pic_path = "http://127.0.0.1:8000/storage/".$picture_name;
            Storage::disk('s3')->putFileAs('/', $picture, $picture_name);
            $pic_path = "https://advance-pic-backet.s3.ap-northeast-1.amazonaws.com/" . $picture_name;
            $update = [
                'shop_name' => $request->shop_name,
                'description' => $request->description,
                'pic_path' => $pic_path,
                // 'pic_path' => "http://127.0.0.1:8000/storage/sushi.jpeg",
                // 'pic_path' => "http://127.0.0.1:8000/storage/maxresdefault.jpeg",
                'area_id' => $area_name->id,
                'genre_id' => $genre_name->id,
            ];
        }
        $item = Shop::where('id', $shop->id)->update($update);
        if ($item) {
            return response()->json([
                'message' => 'Updated successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop)
    {
        $item = Shop::where('id', $shop->id)->delete();
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
