<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Models\Social;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginSocialController extends Controller
{
    public function redirectToWechatProvider()
    {
        return Socialite::driver('weixin')->redirect();
    }

    public function handleWechatProviderCallback()
    {
        $socialUser = Socialite::driver('weixin')->user();

        return $this->bind($socialUser, Social::TYPE_WECHAT);
    }

    // 如果绑定过，取出userID, 自动登录，否则 执行绑定过程
    public function bindOrlogin($socialUser, $type)
    {
        \Log::error(__CLASS__, [__FUNCTION__, $type, $socialUser]);
        $loginedId = Auth::id();
        $social = Social::where('social_id', $socialUser->id)->first();
        //用户未登录，已绑定，执行自动登录, 并定期更新资料
        if(!$loginedId && $social) {
            $user = Auth::loginUsingId($social->user_id, true);//自动登入！
            // todo update social profile!!
            if($social->updated_at->diffInDays(now()) > 1) {
                $social->name = $socialUser->nickname ?: $socialUser->name;
                $social->avatar = $socialUser->avatar;
                if($social->isDirty()){
                    $social->save();
                }
            }
        }
        // 已登录且未绑定，执行绑定
        if($loginedId && !$social) {
            Social::firstOrCreate([
                'social_id' => $socialUser->id,
                'user_id'   => $loginedId,
                'type'      => $type,
                'name'      => $socialUser->nickname ?: $socialUser->name,
                'avatar'      => $socialUser->avatar,
            ]);
        }
        return redirect('home');
    }
}
