<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\User;
use App\Models\Social;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class LoginSocialController extends Controller
{
    public function redirectToWechatProvider()
    {
        return Socialite::driver('weixin')->redirect();
    }

    public function handleWechatProviderCallback()
    {
        $socialUser = Socialite::driver('weixin')->user();

        return $this->bindOrlogin($socialUser, Social::TYPE_WECHAT);
    }

    // 如果绑定过，取出userID, 自动登录，否则 执行绑定过程
    public function bindOrlogin($socialUser, $type)
    {
        $loginedId = Auth::id();
        // \Log::error(__CLASS__, [__FUNCTION__, $type, $loginedId]);
        // \Log::error(__CLASS__, [__FUNCTION__, $socialUser]);
        if($loginedId){
            //用户已登录，执行绑定！
            Social::firstOrCreate([
                'social_id' => $socialUser->id,
                'user_id'   => $loginedId,
                'type'      => $type,
                'name'      => $socialUser->nickname ?: $socialUser->name,
                'avatar'      => $socialUser->avatar,
            ]);
        }else{ //未登录，执行登录！
            $social = Social::where('social_id', $socialUser->id)->first();
            if($social){ //已绑定，并定期更新头像
                if($social->updated_at->diffInDays(now()) > 1) {
                    // $social->name = $name;
                    $social->avatar = $socialUser->avatar;
                    if($social->isDirty()){
                        $social->save();
                    }
                }
            }else { //微信首次授权登录
                $socialId = $socialUser->id;
                $email = $socialId.'@wx.com';
                $token = Str::random(10);
                $password = Hash::make(Str::random(8));
                $name = $socialUser->nickname ?: $socialUser->name;
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => $password,
                    'remember_token' => $token,
                ]);
                Social::create([
                    'social_id' => $socialId,
                    'user_id'   => $user->id,
                    'type'      => $type,
                    'name'      => $name,
                    'avatar'      => $socialUser->avatar,
                ]);
            }
            //执行登录！
            $user = Auth::loginUsingId($social->user_id, true);//自动登入！
        }
        return redirect('home');
    }
}
