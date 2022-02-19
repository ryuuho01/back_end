<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use App\Models\User;
use App\Models\Shop;
use App\Models\Job;
use App\Models\Evaluation;
use App\Models\Jobtest;
use App\Jobs\ReminderJob;
use App\Jobs\EvaluationJob;
use App\Jobs\EvaluationUpdateJob;
use App\Jobs\DeleteJob;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Reservation::all();
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
    public function store(ReservationRequest $request)
    {
        $reservation_date = $request->reservation_date." ". $request->reservation_time;

        $now_date = Carbon::now()->toDateTimeString();
        $resquest_date = new Carbon($reservation_date);
        if ($resquest_date < $now_date) {
            return response()->json([
                'message' => 'Not found',
            ], 200);
        }

        $num_members = str_replace("名様", "", $request->num_members);
        $item_content = [
            'user_id' => $request->user_id,
            'shop_id' => $request->shop_id,
            'reservation_date' => $reservation_date,
            'num_members' => $num_members,
        ];
        Reservation::create($item_content);

        // 3JOB_DISPATCH
        $user_name = User::find($request->user_id)->name;
        $email = User::find($request->user_id)->email;
        $shop_name = Shop::find($request->shop_id)->shop_name;
        $data = [
            'user_id' => $request->user_id,
            'shop_id' => $request->shop_id,
            'user_name' => $user_name,
            'email' => $email,
            'shop_name' => $shop_name,
            'reservation_time' => $request->reservation_time,
            'reservation_date' => $reservation_date
        ];
        $date = new Carbon($request->reservation_date . " " . "07:00:00");
        $date2 = new Carbon($request->reservation_date . " " . "00:00:00");

        if( Jobtest::find(1)->test === "0") {
            ReminderJob::dispatch($data)->delay($date);
            $check_exist = Evaluation::where('user_id', $request->user_id)->where('shop_id', $request->shop_id)->first();
            if ($check_exist) {
                EvaluationUpdateJob::dispatch($data)->delay($date->addDays(1));
            } else {
                EvaluationJob::dispatch($data)->delay($date->addDays(1));
            }
            DeleteJob::dispatch($data)->delay($date2->addDays(1));
        } else {
            ReminderJob::dispatch($data);
            $check_exist = Evaluation::where('user_id', $request->user_id)->where('shop_id', $request->shop_id)->first();
            if ($check_exist) {
                EvaluationUpdateJob::dispatch($data);
            } else {
                EvaluationJob::dispatch($data);
            }
            DeleteJob::dispatch($data);
        }

        // RETURN
        return response()->json([
            'data' => $item_content
        ], 201);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        $item = Reservation::find($reservation);
        foreach ($item as $item) {
            $item->user_name = $item->user->name;
        }
        if ($item) {
            return response()->json([
                'data' => $item
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
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(ReservationRequest $request, Reservation $reservation)
    {
        if(!($request->job_id)) {
            $reservation_date = $request->reservation_date . " " . $request->reservation_time;
            $now_date = Carbon::now()->toDateTimeString();
            $resquest_date = new Carbon($reservation_date);
            if ($resquest_date < $now_date) {
                return response()->json([
                    'message' => 'Not found',
                ], 200);
            }
            $num_members = str_replace("名様", "", $request->num_members);

            // JOB_DISPATCH DELETE
            $old_reservation_date = (new Carbon($reservation->reservation_date))->toDateString();
            $new_reservation_date = (new Carbon($reservation_date))->toDateString();
            $old_alert = new Carbon((new Carbon($reservation->reservation_date))->toDateString() . " " . "07:00:00");
            if (($old_alert < $now_date && $old_reservation_date < $new_reservation_date) || ($old_alert < $now_date && $old_reservation_date === $new_reservation_date)) {
                $item = Job::where('id', $reservation->job_id)->delete();
                $item = Job::where('id', ($reservation->job_id) - 1)->delete();
            } else {
                $item = Job::where('id', $reservation->job_id)->delete();
                $item = Job::where('id', ($reservation->job_id) - 1)->delete();
                $item = Job::where('id', ($reservation->job_id) - 2)->delete();
            }
            // UPDATE
            $update = [
                'reservation_date' => $reservation_date,
                'num_members' => $num_members
            ];
            $item = Reservation::where('id', $reservation->id)->update($update);

            // 3JOB_DISPATCH
            $data = [
                'user_id' => $request->user_id,
                'shop_id' => $request->shop_id,
                'user_name' => $request->user_name,
                'email' => $request->user_email,
                'shop_name' => $request->shop_name,
                'reservation_time' => $request->reservation_time,
                'reservation_date' => $reservation_date
            ];
            $date = new Carbon($request->reservation_date . " " . "07:00:00");
            $date2 = new Carbon($request->reservation_date . " " . "00:00:00");
            
            ReminderJob::dispatch($data)->delay($date);
            $check_exist = Evaluation::where('user_id', $request->user_id)->where('shop_id', $request->shop_id)->first();
            if ($check_exist) {
                EvaluationUpdateJob::dispatch($data)->delay($date->addDays(1));
            } else {
                EvaluationJob::dispatch($data)->delay($date->addDays(1));
            }
            DeleteJob::dispatch($data)->delay($date2->addDays(1));

            // RETURN
            if ($item) {
                return response()->json([
                    'message' => 'Updated successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Not found',
                ], 404);
            }
        } else {

            // JOB_ID UPDATE
            $update = [
                'job_id' => $request->job_id
            ];
            $item = Reservation::where('id', $reservation->id)->update($update);

            // RETURN
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
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        // DELETE
        $item = Reservation::where('id', $reservation->id)->delete();

        // JOB_DISPATCH DELETE
        $now_date = Carbon::now()->toDateTimeString();
        $alert_time = new Carbon((new Carbon($reservation->reservation_date))->toDateString() . " " . "07:00:00");
        if ($alert_time < $now_date) {
            $item = Job::where('id', $reservation->job_id)->delete();
            $item = Job::where('id', ($reservation->job_id) - 1)->delete();
        } else {
            $item = Job::where('id', $reservation->job_id)->delete();
            $item = Job::where('id', ($reservation->job_id) - 1)->delete();
            $item = Job::where('id', ($reservation->job_id) - 2)->delete();
        }
        
        // RETURN
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