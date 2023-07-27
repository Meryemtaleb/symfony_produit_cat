<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;


class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(Request $req ,UserPasswordHasherInterface $passwordEncoder, ManagerRegistry $entityManager): Response
    {   //instancie un objet user vide 
        $user = new Utilisateur();
        //créer le formulaire
        $form=$this->createForm(InscriptionType::class,$user);
        $form->handleRequest($req);
        if($form->isSubmitted()&& $form->isValid()){
            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());
            $user->setEmail($form->get('email')->getData());
            $user->setRoles($form->get('roles')->getData());
            $user->setPassword($passwordEncoder->hashPassword($user,$form->get('password')->getData()));
            $entityManager->getManager()->persist($user); 
            $entityManager->getManager()->flush();
            //Ajouter après une redirection vers lapage login
            return $this-> redirectToRoute('app_login');
        }
        return $this->render('inscription/index.html.twig', [
            'form' =>$form->createView(),
        ]);
    }
}
