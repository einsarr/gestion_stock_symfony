<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
class ProduitController extends AbstractController
{
    /**
     * @Route("/Produit/liste", name="produit_liste")
     */
    public function index(ProduitRepository $repository)
    {
        //$produit = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $repository->findAll();
        return $this->render('produit/liste.html.twig',['produits'=>$produits]);
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
    }
}