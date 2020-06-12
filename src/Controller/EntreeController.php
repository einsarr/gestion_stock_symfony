<?php

namespace App\Controller;
use App\Entity\Entree;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
class EntreeController extends AbstractController
{
    /**
     * @Route("/Entree/liste", name="entree_liste")
     */
    public function index()
    {
        $entree = $this->getDoctrine()->getRepository(Entree::class);
        $entrees = $entree->findAll();
        return $this->render('entree/liste.html.twig',['entrees'=>$entrees]);
    }
    /**
     * @Route("/Entree/get/{id}", name="entree_get")
     */
    public function getEntree($id)
    {
        return $this->render('entree/liste.html.twig');
    }
    /**
     * @Route("/Entree/add", name="entree_add")
     */
    public function add(Request $request)
    {
        //var_dump($request);
        $msgE="";
        $produit = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $produit->findAll();
        if($request->request->count() > 0)
        {
            $entree = new Entree();
            $entree->setLibelle($request->request->get('libelle'))
                    ->setQtStock($request->request->get('qteStock'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entree);
            $em->flush();
            $msgS="Insertion réussie avec succès";
            return $this->render('entree/liste.html.twig',array('message_success'=>$msgS));
        }
        else{
            $msgE="Erreur d'insertion";
            return $this->render('entree/add.html.twig',array('message_error'=>$msgE,'produits'=>$produits));
        }
        return $this->render('entree/add.html.twig',['produits'=>$produits]);
        // $p = new Entree();
        // $p->setLibelle("Clavier");
        // $p->setQtStock(0.0);

        // $em = $this->getDoctrine()->getManager();
        // $em->persist($p);
        // $em->flush();
        // return $this->render('entree/liste.html.twig');
    }
}