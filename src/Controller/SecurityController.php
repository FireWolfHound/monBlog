<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, Security $security)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        // Récupération de l'utilisateur connecté
        $currentUser =  $security->getUser();
        dump($currentUser);

        if($form->isSubmitted() && $form->isValid()) {

            // Hash du mot de passe
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);


            // On transforme le role du type string en array
            $userRole = $user->getRoles();
            $userRole = explode(' ', $userRole);

            $user->setRoles($userRole);

            //Définition du rôle utilisateur par défauts si pas connecté en Admin
            if ($currentUser == null || $currentUser->getRoles()[0] != "ROLE_ADMIN"){
                    $user->setRoles(["ROLE_USER"]);
            }
            
            $manager->persist($user);
            $manager->flush();
        }
        
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

        /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error
        ]);

    }
}
