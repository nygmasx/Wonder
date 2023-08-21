<?php

namespace App\Controller;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
       $questionRepo = $entityManager->getRepository(Question::class);
       $questions = $questionRepo->findAll();
        return $this->render('accueil/index.html.twig', [
            'questions' => $questions,
        ]);
    }
}
