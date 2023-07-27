<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


#[Route('/produits')]
class ProduitsController extends AbstractController
{
    #[Route('/', name: 'app_produits_index', methods: ['GET'])]
    public function index(ProduitsRepository $produitsRepository): Response //ProduitsRepository c'est un reposotorie qui permet d'executer les requettes de select
    {
        return $this->render('produits/index.html.twig', [
            'produits' => $produitsRepository->findAll(), //Produits c'est la clé qu'on mis dans la vue
        ]);
    }

    #[Route('/new', name: 'app_produits_new', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_CLIENT')")]
    public function new(Request $request, EntityManagerInterface $entityManager, sluggerInterface $slugger): Response
    {
        // instancie un objet produit vide 
        $produit = new Produits();
        //créer un formulaire avec createForm(classe formulaire ,objet dans lequel on va stocker les donnes  saisies par l'utilisateur)
        //le formulaire   dans symfony depends d'un objet 
        $form = $this->createForm(ProduitsType::class, $produit);
        //preparer les donnes saisies dans la requette
        $form->handleRequest($request);
        // Traitement apres clique sur le bouton save
        if ($form->isSubmitted() && $form->isValid()) {
            //traiter le cas d'un fichier
            //recuperer le fichier via le formulaire 'form'
            $fichier = $form->get('fichier')->getData();
            //on a ajouter une condition if si le fichier existe on ajoute le fichier sinon on ajout le vproduit directement 
            if ($fichier) {
                // recuperer nom de fichier
                $origine_nom = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                //recuperer le fichier avec son extension "gif,jpeg png, jpg"
                $nomfichierformater = $slugger->slug($origine_nom);
                $nomfichier = $nomfichierformater . '.' . $fichier->guessExtension();
                $fichier->move($this->getParameter('fileDirectory'), $nomfichier);
                //ajouter le nom du fichier avec l'extension dans l'objet produit
                $produit->setFichier($nomfichier);
            }
            $entityManager->persist($produit); //persist une fonction qui prepare la requette de l'insertion 
            $entityManager->flush(); //flush permet d'enregistre les donnes dans BD

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produits_show', methods: ['GET'])]
    #[Security("is_granted('ROLE_CLIENT')")]
    public function show(Produits $produit): Response
    {
        return $this->render('produits/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produits_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function edit(Request $request, Produits $produit, EntityManagerInterface $entityManager, sluggerInterface $slugger): Response
    {
        $anciennomfichier = $produit->getFichier();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //recuperer le fichier via le formulaire 'form'
            $fichier = $form->get('fichier')->getData();
            //on a ajouter une condition if si le fichier existe on ajoute le fichier sinon on ajout le vproduit directement 
            if ($fichier) {
                // recuperer nom de fichier
                $origine_nom = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                //recuperer le fichier avec son extension "gif,jpeg png, jpg"
                $nomfichierformater = $slugger->slug($origine_nom);
                $nomfichier = $nomfichierformater . '.' . $fichier->guessExtension();
                $fichier->move($this->getParameter('fileDirectory'), $nomfichier);
                //ajouter le nom du fichier avec l'extension dans l'objet produit
                $produit->setFichier($nomfichier);
            } else {
                $produit->setFichier($anciennomfichier);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produits/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produits_delete', methods: ['POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function delete(Request $request, Produits $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            if ($produit->getFichier() != null) {
                $entityManager->remove($produit);
                unlink($this->getParameter('fileDirectory') . $produit->getFichier());
            } else {
                $entityManager->remove($produit);
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
    }
}
