<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    /**
     * @Route("/Produit/liste", name="produit_liste")
     */
    public function index(ProduitRepository $repository)
    {

        //$produit = $this->getDoctrine()->getRepository(Produit::class);
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class,$produit,
                array('action'=>$this->generateUrl('produit_add'))
            );
        $data['form'] = $form->createView();
        $data['produits'] = $repository->findAll();
        return $this->render('produit/liste.html.twig',$data);
    }
    /**
     * @Route("/Produit/get/{id}", name="produit_get")
     */
    public function getProduit(Produit $produit)
    {
        //$repos = $this->getDoctrine()->getRepository(Produit::class);
        //$produits = $repos->find($id);
        return $this->render('produit/edit.html.twig',['produit'=>$produit]);
    }
    /**
     * @Route("/Produit/add", name="produit_add")
     */
    public function add(Request $request)
    {
        $p = new Produit();
        $form = $this->createForm(ProduitType::class, $p);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $p = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
        }
        return $this->redirectToRoute('produit_liste');
    }
    /**
     * @Route("/update_produit/{id}", name="update_produit")
     */
    public function update(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $produits = $this->getDoctrine()->getRepository(Produit::class);
        //$data['produits'] = $produits->findAll();

        $produit = $entityManager->getRepository(Produit::class)->find($id);
        
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        $data = array(
            "form_title" => "Modifier un produit",
            "form" => $form->createView(),
            "produits"=>$produits->findAll()
        );
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
        }
        return $this->render("produit/liste.html.twig",$data);
    }
    /**
     * @Route("/delete_produit/{id}", name="delete_produit")
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute("produit_liste");
    }
}