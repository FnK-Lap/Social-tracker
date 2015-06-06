<?php

namespace SocialTracker\Bundle\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SocialTracker\Bundle\ApplicationBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SocialTracker\Bundle\ApplicationBundle\Form\ChangePasswordType;
use Symfony\Component\Security\Core\SecurityContext;
use Otp\Otp;
use Otp\GoogleAuthenticator;
use Base32\Base32;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function updatePasswordAction(User $user, Request $request)
    {
        $form = $this->createForm(new ChangePasswordType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $encoder      = $this->get('security.encoder_factory')->getEncoder($user);
            // check if old password is valid
            if (!$encoder->isPasswordValid($user->getPassword(), $form->get('old_password')->getData(), $user->getSalt())) {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    'Votre ancien mot de passe n\'est pas correct'
                );
            } else {
                $user->setPlainPassword($form->get('password')->getData());
                $userManager = $this->get('fos_user.user_manager');
                $userManager->updateUser($user);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Votre mot de passe a été modifié'
                );
            }

            return $this->redirect($this->generateUrl('application_settings'));
        }
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('SocialTrackerApplicationBundle:Security:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }

    public function activateTotpAction(User $user, Request $request)
    {
        // Step 1 - Request QRcode
        if ($request->isXmlHttpRequest()) {

            if ($this->get('session')->get('GoogleTwoFactorSecret') !== null && $this->get('session')->get('GoogleTwoFactorSecret') !== true) {
                $secret = $this->get('session')->get('GoogleTwoFactorSecret');
            } else {
                $secret = GoogleAuthenticator::generateRandom();
            }

            $url = GoogleAuthenticator::getQrCodeUrl('totp', 'SocialTracker', $secret);

            $this->get('session')->set('GoogleTwoFactorSecret', $secret);

            return $this->render('SocialTrackerApplicationBundle:Application:ajax/activate_totp.html.twig', array(
                'url'    => $url
            ));

        } else {
            // Step 2 - Validate code
            $secret = $this->get('session')->get('GoogleTwoFactorSecret');
            if ($request->getMethod() == 'POST' && isset($secret)) {
                $otp = new Otp();
                if ($otp->checkTotp(Base32::decode($secret), $request->request->get('code'))) {
                    $this->get('session')->set('GoogleTwoFactorSecret', true);
                    $user->setGoogleAuthenticatorSecret($secret);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();

                    $this->get('session')->getFlashBag()->add(
                        'success',
                        'La sécurité Totp a été activée'
                    );

                    $this->get('session')->set('GoogleTwoFactorSecret', true);
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        'Le code est incorect'
                    );
                }

                return $this->redirect($this->generateUrl('application_settings'));
            }
        }
    }

    public function desactivateTotpAction(User $user, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $user->setGoogleAuthenticatorSecret(null);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(array(
                'status'  => 200,
                'message' => 'La sécurité Totp a bien été désactivée'
            ));
        }

        throw new AccessDeniedException("Only XmlHttpRequest was authorized");
    }
}
