<?php

namespace App\Controller;
use App\Entity\Sortie;
use App\Entity\Produit;
use App\Form\SortieType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SortieController extends AbstractController
{
    /**
     * @Route("/Sortie/liste", name="sortie_liste")
     */
    public function index()
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class,$sortie,
                array('action'=>$this->generateUrl('sortie_add'))
            );
        $data['form'] = $form->createView();

        $sortie = $this->getDoctrine()->getRepository(Sortie::class);
        $data['sorties'] =  $sortie->findAll();
        return $this->render('sortie/liste.html.twig',$data);
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
        $s = new Sortie();
        $p = new Produit();
        $form = $this->createForm(SortieType::class, $s);
        $form->handleRequest($request);
        $data=[];
        if ($form->isSubmitted() && $form->isValid()) {
            $s = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($s);
            $em->flush();
            //Mise à jour du produit
            $p = $em->getRepository(Produit::class)->find($s->getProduit()->getId());
            if($s->getQteS() <= $p->getQtStock()){
                $stock = $p->getQtStock() - $s->getQteS();
                $p->setQtStock($stock);
                $em->flush();
                $data['message_success'] = "Message succès";
            }
            else{
                $data['message_error'] = "Stock insufisant";
            }
        }
        return $this->redirectToRoute('sortie_liste',$data);
    }
    /**
     * @Route("/update_sortie/{id}", name="update_sortie")
     */
    public function update(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $sorties = $this->getDoctrine()->getRepository(Sortie::class);

        $sortie = $entityManager->getRepository(Sortie::class)->find($id);
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);
        $data = array(
            "form_title" => "Modifier un produit",
            "form" => $form->createView(),
            "sorties"=>$sorties->findAll()
        );
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
        }
        return $this->render("sortie/liste.html.twig",$data);
    }
    /**
     * @Route("/delete_sortie/{id}", name="delete_sortie")
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $sortie = $entityManager->getRepository(Sortie::class)->find($id);
        $entityManager->remove($sortie);
        $entityManager->flush();

        return $this->redirectToRoute("sortie_liste");
    }
}