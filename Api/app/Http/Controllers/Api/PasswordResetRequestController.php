<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordsRequest;
use App\Mail\forgotPassMail;
use App\Models\User;
use App\Traits\TraitResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetRequestController extends Controller
{
    use TraitResponse;
    public function sendPasswordResetEmail(Request $request)
    {
        // If email does not exist
        if (!$this->validEmail($request->email)) {
            return $this->responseApi('', 400, 'Email không tồn tại.');
        } else {
            // If email exists
            $this->sendMail($request->email);

            return $this->responseApi('', 200, 'Kiểm tra hộp thư đến của bạn, chúng tôi đã gửi một mã để đặt lại email');
        }
    }
    public function sendMail($email)
    {
        $token = $this->generateToken($email);
        //dd([$token,$email]);
        Mail::to($email)->send(new forgotPassMail([$token, $email]));
    }

    public function validEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    public function generateToken($email)
    {
        $isOtherToken = DB::table('users')->where('email', $email)->first();

        if (!empty($isOtherToken->remember_token) || $isOtherToken->remember_token !== null) {

            return $isOtherToken->remember_token;
        }
        // dd($isOtherToken->remember_token);
        $token = Str::random(10);
        $this->storeToken($token, $email);
        return $token;
    }

    public function storeToken($token, $email)
    {
        User::where('email', $email)->update([
            'remember_token' => $token,
            'email_verified_at' => Carbon::now(),
        ]);
    }
    public function changePassWords(ChangePasswordsRequest $request)
    {

        $users = User::where('email', $request->email)->first();
       
        if (isset($users) && !empty($users)) {
          
              if($users->remember_token===$request->code){
                //  dd($users->remember_token ,$request->code,$email);
                $users->update(
                [
                    'password' => bcrypt($request->password),
                    'remember_token' => null,
                ]
            );
            return $this->responseApi("", 200, "Cập nhật mật khẩu thành công");

              }
              return $this->responseApi("", 400, "Mã xác nhận không đúng");
            // $user = User::where('id', auth()->user()->id)->update(
            //     ['password' => bcrypt($request->new_password)]
            // );
        }
        return $this->responseApi("", 400, "Email không tồn tại");
    }

}
