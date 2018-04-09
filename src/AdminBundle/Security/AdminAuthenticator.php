<?php
namespace AdminBundle\Security;

use RestBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;
use Doctrine\ORM\EntityManager;


/**
 * Class AdminAuthenticator
 * @package AdminBundle\Security
 */
class AdminAuthenticator implements SimpleFormAuthenticatorInterface
{
    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @var RequestStack $requestStack
     */
    private $requestStack;

    /**
     * AdminAuthenticator constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManager $em, RequestStack $requestStack)
    {
        $this->encoder = $encoder;
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return UsernamePasswordToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            /** @var User $admin */
            $admin = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid username or password');
        }

        $valid = $this->encoder->isPasswordValid($admin, $token->getCredentials());

        if ($this->requestStack->getCurrentRequest()->get('timezone') && !$admin->getTimezone()) {
            $admin->setTimezone($this->requestStack->getCurrentRequest()->get('timezone'));
            $this->em->flush();
        }

        if ($valid) {
            return new UsernamePasswordToken(
                $admin,
                $admin->getPassword(),
                $providerKey,
                [$admin->getRole()]
            );
        }
        
        throw new CustomUserMessageAuthenticationException('Invalid username or password');
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param Request $request
     * @param $username
     * @param $password
     * @param $providerKey
     * @return UsernamePasswordToken
     */
    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}