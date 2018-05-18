<?php

namespace App\Domains\Api\Jobs;

use App\Data\Models\User;
use DateTime;
use GuzzleHttp\Psr7\Response;
use Illuminate\Events\Dispatcher;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\Client;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;
use Lucid\Foundation\Job;

class GenerateAccessTokenByUser extends Job
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle()
    {
        return $this->getBearerTokenByUser($this->user);
    }

    /**
     * Generate a new unique identifier.
     *
     * @param int $length
     *
     * @throws OAuthServerException
     *
     * @return string
     */
    private function generateUniqueIdentifier($length = 40)
    {
        try {
            return bin2hex(random_bytes($length));
            // @codeCoverageIgnoreStart
        } catch (\TypeError $e) {
            throw OAuthServerException::serverError(_('An unexpected error has occurred'));
        } catch (\Error $e) {
            throw OAuthServerException::serverError(_('An unexpected error has occurred'));
        } catch (\Exception $e) {
            // If you get this message, the CSPRNG failed hard.
            throw OAuthServerException::serverError(_('Could not generate a random string'));
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param AccessTokenEntityInterface $accessToken
     * @return mixed
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    private function issueRefreshToken(AccessTokenEntityInterface $accessToken)
    {
        $maxGenerationAttempts = 10;
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        $refreshToken = $refreshTokenRepository->getNewRefreshToken();
        $refreshToken->setExpiryDateTime((new \DateTime())->add(Passport::refreshTokensExpireIn()));
        $refreshToken->setAccessToken($accessToken);

        while ($maxGenerationAttempts-- > 0) {
            $refreshToken->setIdentifier($this->generateUniqueIdentifier());
            try {
                $refreshTokenRepository->persistNewRefreshToken($refreshToken);

                return $refreshToken;
            } catch (UniqueTokenIdentifierConstraintViolationException $e) {
                if ($maxGenerationAttempts === 0) {
                    throw $e;
                }
            }
        }
    }

    /**
     * @param User $user
     * @param $clientId
     * @return array
     */
    protected function createPassportTokenByUser(User $user, $clientId)
    {
        $accessToken = new AccessToken($user->id);
        $accessToken->setIdentifier($this->generateUniqueIdentifier());
        $accessToken->setClient(new Client($clientId, null, null));
        $accessToken->setExpiryDateTime((new DateTime())->add(Passport::tokensExpireIn()));

        $accessTokenRepository = new AccessTokenRepository(new TokenRepository(), new Dispatcher());
        $accessTokenRepository->persistNewAccessToken($accessToken);
        $refreshToken = $this->issueRefreshToken($accessToken);

        return [
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }

    /**
     * @param $accessToken
     * @param $refreshToken
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    protected function sendBearerTokenResponse($accessToken, $refreshToken)
    {
        $response = new BearerTokenResponse();
        $response->setAccessToken($accessToken);
        $response->setRefreshToken($refreshToken);

        $privateKey = new CryptKey('file://' . Passport::keyPath('oauth-private.key'), null, false);

        $response->setPrivateKey($privateKey);
        $response->setEncryptionKey(app('encrypter')->getKey());

        return $response->generateHttpResponse(new Response);
    }

    /**
     * @param User $user
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getBearerTokenByUser(User $user)
    {
        $passportToken = $this->createPassportTokenByUser($user, $this->getClientId());
        $bearerToken = $this->sendBearerTokenResponse($passportToken['access_token'], $passportToken['refresh_token']);

        return $bearerToken;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getClientId()
    {
        $client = \Laravel\Passport\Client::query()->where('name', 'login')->first();
        if (!$client) {
            throw OAuthServerException::serverError(_('Can`t find oauth_client, try to run oauth seeder'));
        }
        return $client->id;
    }
}
