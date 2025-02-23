<?php

namespace App\Services;

use App\Actions\GenerateOTP;
use App\Exceptions\ActionUnauthorizedException;
use App\Jobs\SendAccountDeletedEmailJob;
use App\Jobs\SendOneTimePasswordJob;
use App\Models\User;

class UserService
{
    public function __construct(private User $user)
    {
        if (request()->user()->id !== $user->id) {
            throw new ActionUnauthorizedException();
        }
    }
    public function update(array $data, User $user)
    {
        $user->update($data);
        $user->refresh();

        return [true, [$user], MCH_model_updated("User"), 200];
    }

    public function init_delete()
    {
        $token = (new GenerateOTP())->generate();
        $user = request()->user();
        $key = MCH_oneTimePasswordCacheKey($user->email);

        dispatch(new SendOneTimePasswordJob($token, $key, $user))->delay(now());

        return [true, [], MCH_ACCOUNT_DELETE_OTP_MESSAGE, 200];
    }

    public function delete(User $user)
    {
        $user->delete();

        dispatch(new SendAccountDeletedEmailJob($user))->delay(now());

        return [true, [], MCH_model_deleted('User Account'), 200];
    }
}
