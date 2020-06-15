<?php

namespace App\Controller;
use App\Entity\Entree;
use App\Entity\Produit;
use App\Form\EntreeType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntreeController extends AbstractController
{
    /**
     * @Route("/Entree/liste", name="entree_liste")
     */
    public function index()
    {
        $entree = new Entree();
        $form = $this->createForm(EntreeType::class,$entree,
                array('action'=>$this->generateUrl('entree_add'))
            );
        $data['form'] = $form->createView();


        $entree = $this->getDoctrine()->getRepository(Entree::class);
        $data['entrees'] =  $entree->findAll();
        return $this->render('entree/liste.html.twig',$data);
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
        $e = new Entree();
        $p = new Produit();
        $form = $this->createForm(EntreeType::class, $e);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $e = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($e);
            $em->flush();
            //Mise à jour du produit
            $p = $em->getRepository(Produit::class)->find($e->getProduit()->getId());
            $stock = $p->getQtStock() + $e->getQteE();
            $p->setQtStock($stock);
            $em->flush();
        }
        return $this->redirectToRoute('entree_liste');
    }
    /**
     * @Route("/update_entree/{id}", name="update_entree")
     */
    public function update(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entrees = $this->getDoctrine()->getRepository(Entree::class);

        $entree = $entityManager->getRepository(Entree::class)->find($id);

        
        $form = $this->createForm(EntreeType::class, $entree);
        $form->handleRequest($request);
        $data = array(
            "form_title" => "Modifier un produit",
            "form" => $form->createView(),
            "entrees"=>$entrees->findAll()
        );
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();

        }
        return $this->render("entree/liste.html.twig",$data);
    }
    /**
     * @Route("/delete_entree/{id}", name="delete_entree")
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entree = $entityManager->getRepository(Entree::class)->find($id);
        $entityManager->remove($entree);
        $entityManager->flush();

        return $this->redirectToRoute("entree_liste");
    }
}