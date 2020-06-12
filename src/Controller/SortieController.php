<?php

namespace App\Controller;
use App\Entity\Sortie;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
class SortieController extends AbstractController
{
    /**
     * @Route("/Sortie/liste", name="sortie_liste")
     */
    public function index()
    {
        $sortie = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $sortie->findAll();
        return $this->render('sortie/liste.html.twig',['sorties'=>$sorties]);
    }
    /**
     * @Route("/Sortie/get/{id}", name="sortie_get")
     */
    public function getSortie($id)
    {
        return $this->render('sortie/edit.html.twig');
    }
    /**
     * @Route("/Sortie/add", name="sortie_add")
     */
    public function add(Request $request)
    {
        //var_dump($request);
        $msgE="";
        $produit = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $produit->findAll();
        if($request->request->count() > 0)
        {
            $sortie = new Sortie();
            $sortie->setLibelle($request->request->get('libelle'))
                    ->setQtStock($request->request->get('qteStock'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->flush();
            $msgS="Insertion réussie avec succès";
            return $this->render('sortie/liste.html.twig',array('message_success'=>$msgS));
        }
        else{
            $msgE="Erreur d'insertion";
            return $this->render('sortie/add.html.twig',array('message_error'=>$msgE,'produits'=>$produits));
        }
        return $this->render('sortie/add.html.twig',['produits'=>$produits]);
        // $p = new Sortie();
        // $p->setLibelle("Clavier");
        // $p->setQtStock(0.0);

        // $em = $this->getDoctrine()->getManager();
        // $em->persist($p);
        // $em->flush();
        // return $this->render('sortie/liste.html.twig');
    }
}