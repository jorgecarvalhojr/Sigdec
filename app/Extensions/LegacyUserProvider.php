<?PHP
namespace App\Extensions;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\UserProvider;

class LegacyUserProvider extends EloquentUserProvider implements UserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array                                       $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];
        $digest = $user->getAuthPassword();

        // Legacy authentication mode
        if (strlen($digest) == 32 && hash('md5', $plain) == $digest) {
            $this->updatePasswordDigest($user, $plain);

            return true;
        }

        // Fallback to the stronger bcrypt authentication
        return $this->hasher->check($plain, $digest);
    }

    /**
     * Update the password digest for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string                                      $digest
     * @return void
     */
    protected function updatePasswordDigest(UserContract $user, $plain)
    {
        $user->senha = $this->hasher->make($plain);
        // $timestamps = $user->timestamps;
        // $user->timestamps = false;
        $user->save();
        // $user->timestamps = $timestamps;
    }
}
?>