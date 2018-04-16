<?php

namespace App\Controller;

use App\Entity\Review;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReviewController extends Controller
{
    /**
     *
     * Matches /review/*
     *
     * @Route("/review/{id}", name="review")
     */
    public function index($id)
    {


        $request = new Request();

        $myReview = new Review();
        $myReview->setIdRecipe($id);
        $myReview->setPrice(null);
        $myReview->setDate("".time());
        $myReview->setDescription("");
        $myReview->setRating(null);
        $myReview->setIdAuthor(null);//current user


        $form = $this->createFormBuilder($myReview)
            ->add("description", TextType::class)
            ->add("price", IntegerType::class)
            ->add("rating", IntegerType::class)
            ->add("save", SubmitType::class, array('label'=>"Send review"))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $myReview = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($myReview);
            $entityManager->flush();

            $reviewId = $myReview->getId();
            $this->display($reviewId);
        }


        $reviewArray= $this->getDoctrine()->getManager()->find("Review",$id);
        return $this->render("review/index.html.twig/".$id,array("reviews"=>$reviewArray));
    }
}
