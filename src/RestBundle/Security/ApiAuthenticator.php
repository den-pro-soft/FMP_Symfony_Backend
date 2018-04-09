<?php
namespace RestBundle\Security;

use RestBundle\Entity\User;
use RestBundle\Exception\UserNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

/**
 * Class ApiAuthenticator
 * @package RestBundle\Security
 */
class ApiAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken
     * @throws BadCredentialsException
     */
    public function createToken(Request $request, $providerKey)
    {
        $apiKey = $request->headers->get('token');

        if (!$apiKey) {
            throw new BadCredentialsException('No API key found');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $apiKey,
            $providerKey
        );
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return PreAuthenticatedToken
     * @throws UserNotFoundException
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            /** @var User $user */
            $user = $userProvider->loadUserByUsername($token->getCredentials());
        } catch (UsernameNotFoundException $e) {
            throw new UserNotFoundException();
        }

        if ($user->isEnabled() && null !== $user->getProfile() && !$user->getIsRemoved()) {
            $user->addRole('ROLE_USER');

            return new PreAuthenticatedToken(
                $user,
                $user->getToken(),
                $providerKey,
                $user->getRoles()
            );
        }

        throw new UserNotFoundException();
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
}