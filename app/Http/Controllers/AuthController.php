<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use App\Http\Requests\ResisterRequest;
use App\Http\Requests\MailRequest;
use App\Http\Requests\ShopCreateRequest;
use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Jobtest;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * コンストラクター
     * クラスからインスタンスを生成するとき（オブジェクトがnewによって作成されるとき）に自動的に呼び出されるメソッド
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['regist', 'login', 'reminder']]);
    }

    /**
     * レスポンス作成
     *
     * @param string $status httpStatus Number
     * @param string $statusText
     * @param array $data
     * @param string $request
     * @return array
     */
    protected function buildResponse($status, $statusText, $data, $request)
    {
        $response = [
            'status' => $status,
            'statusText' => $statusText,
            'data' => $data,
            'request' => $request
        ];

        return $response;
    }
    /**
     * ユーザー登録（レジスト）
     * name,email,passwordをリクエストパラメータで受け取る必要がある。
     *
     * @param ResisterRequest $request
     * @return json
     */

    public function register(ResisterRequest $request)
    {
        Log::info($request);
        $user = new User;
        $user->fill($request->all());
        $user->shop_id = null;
        $user->authority = 3;
        // 仮登録用Emailへ移動
        $user->verify_email_address = $request->email; //有効確認用のemail
        // 仮のEmail。EmailはUniqueのためダブらないようにランダムで作る。
        $user->email = Str::random(32) . "@temp.tmp";
        // パスワード暗号化(厳密にはハッシュ化)
        $user->password = Hash::make($request->password);

        // 仮登録確認用設定
        $user->verify_email = false; //Emailを承認したかどうか
        $user->verify_token = Str::random(32); //32文字のランダム文字列をtokenとする
        //Carbonによって、日付や時間をよりシンプルに作れる。toDateTimeStringでymdhms取得。
        $user->verify_date = Carbon::now()->toDateTimeString(); //仮登録の日付

        // ユーザー情報をモデルによりusersテーブルへ保存
        //createはインスタンス作成→データ保存→作成したインスタンスをreturnする。
        //fill()→save()はインスタンスは作成したいが、DB への保存は処理を分けたいなどの際に使用する。
        // User::create([
        //     "name" => $request->name,
        //     "email" => $request->email,
        //     "password" => Hash::make($request->password)
        // ]);
        $user->save();
        // TODO:ここにメール送信処理を追加
        $this->sendVerifyMail("regist", $user->verify_email_address, $user->verify_token);
        // レスポンス作成
        $response = $this->buildResponse(200, "OK", '', "register");

        //ログの関数は、第一引数にログメッセージ(第二引数にコンテキストを指定['foo' => 'bar'])
        Log::info('Verify Regist User:' . $user);

        // response()->json($response, $response['status']);
        return response()->json($response, $response['status']);
    }

    /**
     * WEBアクセス Email認証用メソッド
     *
     * Emailで届いたトークンを承認する
     *
     * @param string $token
     * @return view
     */
    public function verify($token)
    {
        //Log表示用に初期値をここで作っておく
        // $params['result'] = "error";

        // トークンの有効期限を30分とするため有効な時間を算出
        // 現在時間 -30分(ここでいう現在時間はURLをクリックした時の時間)
        $verify_limit = Carbon::now()->subMinute(30)->toDateTimeString();

        $user = User::where('verify_token', $token)->where('verify_date', '>', $verify_limit)->first();

        //token columnにあるtokenと受信tokenが一致かつ30分以内のURLアクセスであれば
        if ($user) {
            // もし登録しようとしているemailが既にusersテーブルに存在していれば
            if (User::where("email", $user->verify_email_address)->first()) {
                // $params['result'] = "exist";
                Log::info('Verify Exist: ' . $user->verify_email_address);
                return redirect()->away('http://3.112.48.148/registalready');
                //実際は登録の時点でemailのuniqueを確認しているので、このエラーは起きないはず
            } else {
                // 仮メールアドレスを本メールに移動
                $user->email = $user->verify_email_address;
                // 仮メールアドレスを削除
                $user->verify_email_address = null;
                // 有効なユーザーにする
                $user->verify_email = true;
                // その他クリーニング
                $user->verify_token = null;
                $user->verify_date = null;
                // 承認日登録
                $user->email_verified_at = Carbon::now()->toDateTimeString();
                // 権限付与
                $user->authority = 2;

                // テーブル保存
                $user->save();
                // $params['result'] = "success";
                Log::info('Verify Success: ' . $user);
                return redirect()->away('http://3.112.48.148/thanks');
            }
        } else {
            Log::info('Verify Not Found: token=' . $token);
            return redirect()->away('http://3.112.48.148/registerror');
        }
    }

    /**
     * 認証メールを送信する
     *
     * @param [type] $type
     * @param [type] $email
     * @param [type] $token
     * @return void
     */
    public function sendVerifyMail($type, $email, $token)
    {
        $data = ['token' => $token];

        if ($type == 'regist') {
            //第1引数に、テンプレートファイルのパス
            //第2引数に、テンプレートファイルで使うデータ
            //第3引数にはコールバック関数を指定し、その中で、送信先やタイトルの指定
            Mail::send('jwt.emails.user_register', $data, function ($message) use ($email) {
                $message
                    ->to($email)
                    //configフォルダのmail.phpの中のfromのaddress。
                    ->from(config('mail.from.address'))
                    ->subject('【Rese】ユーザー登録の確認メール');
            });
        }
        if ($type == 'reminder') {
            Mail::send('jwt.emails.user_reminder', $data, function ($message) use ($email) {
                $message
                    ->to($email)
                    ->from(config('mail.from.address'))
                    ->subject('【Rese】パスワード変更確認メール');
            });
        }
    }

    /**
     * ログイン
     * email,passwordをリクエストパラメータで受け取る必要がある。
     *
     * @param Request $request
     * @return json
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * パスワードリマインダー
     *
     * @param Request $request
     * @return json
     */
    public function reminder(Request $request)
    {
        // ユーザーが存在するか
        if ($request->email) {
            $user = User::where('email', $request->email)->first();
            Log::info($user);
        }

        if ($user == null) {
            $response = $this->buildResponse(200, 'User Not Found', '', 'reminder');
            Log::info('Reminder User:' . 'User Not Found');
            return response()->json($response, $response['status']);
            // return redirect()->away('http://localhost:3000/notexist');

        } else if ($user != null) {

        // verify_token作成し保存
        $user->verify_token = Str::random(32);
        $user->verify_date = Carbon::now()->toDateTimeString();
        $user->save();

        // メール送信
        $this->sendVerifyMail("reminder", $user->email, $user->verify_token);

        // レスポンス作成
        $response = $this->buildResponse(200, 'OK', '', 'reminder');
        Log::info('Reminder User:' . $user);
        return response()->json($response, $response['status']);
        // return redirect()->away('http://localhost:3000/exist');
        }
    }

    /**
     * WEBリクエスト パスワード設定画面
     *
     * @param Request $request
     * @return view
     */
    public function input_password(Request $request)
    {

        $token = $request->id;

        $verify_limit = Carbon::now()->subMinute(30)->toDateTimeString();
        // tokenが一致し、30分以内かどうかの確認
        $user = User::where('verify_token', $token)->where('verify_date', '>', $verify_limit)->first();

        if ($user) {
            return redirect()->away('http://3.112.48.148/input_password/?id='.$token);
        } else {
            return redirect()->away('http://3.112.48.148/registerror');
        }
    }

    /**
     * WEBリクエスト パスワードリマインダー
     *
     * @param Request $request
     * @return View
     */
    public function password_change(Request $request)
    {

        // $params['result'] = "error";

        // 入力情報のバリデーション
        // $validator = Validator::make($request->all(), [
        //     'password' => 'required|string|min:6|confirmed',
        //     'token' => 'required',
        // ]);

        $token = $request->token;

        // バリデーションエラーの場合レスポンス
        // if ($validator->fails()) {
        //     $params['message'] = $validator->errors();
        //     Log::info('Reminder Error: ' . $validator->errors());
        //     return redirect('/reminder/' . $token)
        //         ->withErrors($validator)
        //         ->withInput();
        // } else {

            // トークンの有効期限を30分とするため有効な時間を算出
            // 現在時間 -30分
            $verify_limit = Carbon::now()->subMinute(30)->toDateTimeString();
            // tokenが一致し、30分以内かどうかの確認
            $user = User::where('verify_token', $token)->where('verify_date', '>', $verify_limit)->first();

            if ($user !== null) {

                // パスワードを変更する
                $user->password = Hash::make($request->password);
                // その他クリーニング
                $user->verify_token = null;
                $user->verify_date = null;
                // 承認日登録
                $user->email_verified_at = Carbon::now()->toDateTimeString();

                // テーブル保存
                $user->save();
                $response = $this->buildResponse(200, 'Success', '', 'password_change');
                Log::info('Reminder Success: ' . $user);
                return response()->json($response, $response['status']);
                // $params = ['result' => 'success'];
                // return redirect()->away('http://localhost:3000/successchange');
            } else if ($user === null) {
                $response = $this->buildResponse(200, 'Notfound User', '', 'password_change');
                Log::info('Reminder Error: Notfound User');
                return response()->json($response, $response['status']);
                // $params = ['result' => 'error'];
                // return redirect()->away('http://localhost:3000/registerror');
            }
        // }
        // return view('jwt.reminder', $params);
    }

    public function email(MailRequest $request)
    {
        $TO = $request->TO;
        $CC = $request->CC;
        $BCC = $request->BCC;
        $subject = $request->subject;
        $data = ['text' => $request->text];

        if($TO !== []) {

        Mail::send(['text' => 'jwt.emails.user_text'], $data, function ($message) use ($TO, $CC, $BCC, $subject) {
        // Mail::send(['jwt.emails.user_text'], $data, function ($message) use ($TO, $CC, $BCC, $subject) {
            $message
                ->to($TO)
                ->cc($CC)
                ->bcc($BCC)
                ->from(config('mail.from.address'))
                ->subject($subject);
        });
        $response = $this->buildResponse(200, 'Success', '', 'send_email');
        return response()->json($response, $response['status']);
       } else {
            $response = $this->buildResponse(404, 'Notfound TO', '', 'not_send_email');
            Log::info('Sending email Error: Notfound TO');
            return response()->json($response, $response['status']);
       }
    }
    public function users()
    {
        $items = User::all();
        return response()->json([
            'data' => $items
        ], 200);
    }
    public function add_manager(ResisterRequest $request)
    {
        $hash = Hash::make($request->password);
        $item_content = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $hash,
        ];
        $item = User::create($item_content);
        return response()->json([
            'data' => $item
        ], 201);
    }
    public function change_authority(Request $request)
    {
        $update = [
            'authority' => 1,
            'verify_email' => 1
        ];
        $TO = $request->email;
        $data = [
            'name' => $request->name,
            'password' => $request->password
        ];
        $item = User::where('id', $request->id)->update($update);
        if ($item) {
            Mail::send('jwt.emails.manager_created', $data, function ($message) use ($TO) {
                $message
                    ->to($TO)
                    ->from(config('mail.from.address'))
                    ->subject("店舗代表者が作成されました");
            });
            $response = $this->buildResponse(200, 'Success', '', 'change_user_authority');
            return response()->json($response, $response['status']);
        } else {
            $response = $this->buildResponse(404, 'Notfound user', '', 'not_send_email');
            Log::info('Sending email Error: Notfound user');
            return response()->json($response, $response['status']);
        }
    }

    public function add_shop(ShopCreateRequest $request)
    {
        $picture = $request->file('pic_path');
        $picture_name = $picture->getClientOriginalName();
        // $picture->storeAs('public', $picture_name);
        Storage::disk('s3')->putFileAs('/', $picture, $picture_name);
        $pic_path = "https://advance-pic-backet.s3.ap-northeast-1.amazonaws.com/" . $picture_name;

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

    public function add_shop_data(Request $request)
    {
        $update = [
            'shop_id' => Shop::pluck('id')->last(),
        ];
        
        $item = User::where('id', $request->user_id)->update($update);
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

    public function testData()
    {
        $item = Jobtest::all();
        return response()->json([
            'data' => $item,
        ], 200);
    }

    public function test(Request $request)
    {
        $update = [
            'test' => $request->test,
        ];

        $item = Jobtest::where('id', 1)->update($update);
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
