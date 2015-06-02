<?php

namespace SocialTracker\Bundle\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SocialTracker\Bundle\ApplicationBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SocialTracker\Bundle\ApplicationBundle\Form\ChangePasswordType;

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
}
