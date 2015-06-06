<?php

namespace SocialTracker\Bundle\ApplicationBundle\Security;
 
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Otp\Otp;
use Base32\Base32;
 
class RequestListener
{
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     */
    protected $securityContext;
    
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    protected $templating;
    
    /**
     * @param \Symfony\Component\Security\Core\SecurityContextInterface  $securityContext
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    public function __construct(Router $router, SecurityContextInterface $securityContext, EngineInterface $templating)
    {
        $this->router          = $router;
        $this->securityContext = $securityContext;
        $this->templating      = $templating;
    }
    
    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     * @return
     */
    public function onCoreRequest(GetResponseEvent $event)
    {
        $token = $this->securityContext->getToken();
        
        if (!$token)
        {
            return;
        }
        
        if (!$token instanceof UsernamePasswordToken)
        {
            return;
        }
        
        $request = $event->getRequest();
        $session = $event->getRequest()->getSession();
        $user = $this->securityContext->getToken()->getUser();
        
        //Check if user has to do two-factor authentication
        if (!$user->getGoogleAuthenticatorSecret()) {
            return;
        }

        if ($session->get('GoogleTwoFactorSecret') === true) {
            return;
        }
        
        if ($request->getMethod() == 'POST')
        {
            $otp = new Otp();

            //Check the authentication code
            if ($otp->checkTotp(Base32::decode($session->get('GoogleTwoFactorSecret')), $request->request->get('_auth_code')))
            {
                //Flag authentication complete
                $session->set('GoogleTwoFactorSecret', true);
 
                //Redirect to user's dashboard
                $redirect = new RedirectResponse($this->router->generate("application_home"));
                $event->setResponse($redirect);
                return;
            }
            else
            {
                $session->getFlashBag()->set("error", "Le code de verification n'est pas correct");
            }
        }
        
        //Force authentication code dialog
        $response = $this->templating->renderResponse('SocialTrackerApplicationBundle:Security:form.html.twig');
        $event->setResponse($response);
    }
    
}