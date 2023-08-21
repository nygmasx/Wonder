<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Question;
use App\Form\CommentType;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/question/ask', name: 'question_form')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();

        $formQuestion = $this->createForm(QuestionType::class, $question);

        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()){
            $question->setRating(0);
            $question->setCreatedAt(new \DateTimeImmutable());
            $question->setNbrOfResponse(0);
            $entityManager->persist($question);
            $entityManager->flush();
            $this->addFlash('success', 'Votre question a été ajoutée');
            return $this->redirectToRoute('accueil');
        }
        return $this->render('question/index.html.twig', [
            'form' => $formQuestion->createView()
        ]);
    }

    #[Route('/question/{id}', name: 'question_show')]
    public function show(Question $question, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()){
            $comment->setRating(0);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setQuestion($question);
            $comment->setNbrOfResponse($question->getNbrOfResponse() + 1);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre réponse a bien été ajoutée');
            return $this->redirect($request->getUri());
        }

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'form' => $commentForm
        ]);
    }

}
