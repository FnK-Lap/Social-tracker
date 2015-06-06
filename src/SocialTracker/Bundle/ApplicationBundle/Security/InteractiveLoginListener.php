<?php

namespace SocialTracker\Bundle\ApplicationBundle\Security;
 
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use SocialTracker\Bundle\ApplicationBundle\Entity\User;
 
class InteractiveLoginListener
{        
    /**
     * Listen for successful login events
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     * @return
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        if (!$event->getAuthenticationToken() instanceof UsernamePasswordToken)
        {
            return;
        }
        
        //Check if user can do two-factor authentication
        $ip = $event->getRequest()->getClientIp();
        $token = $event->getAuthenticationToken();
        $user = $token->getUser();
        if (!$user instanceof User)
        {
            return;
        }
        if (!$user->getGoogleAuthenticatorSecret())
        {
            return;
        }
        
        //Set flag in the session
        $event->getRequest()->getSession()->set('GoogleTwoFactorSecret', $user->getGoogleAuthenticatorSecret());
    }
    
}