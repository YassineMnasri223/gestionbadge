<?php

namespace App\Controller;

use App\Entity\Test;
use App\Repository\TestRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Dompdf\Dompdf;
use Dompdf\Options;


class ListOfTestsController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private Dompdf $dompdf;

    public function __construct(EntityManagerInterface $entityManager)
{
    $this->entityManager = $entityManager;
    $this->dompdf = new Dompdf();
}


#[Route('/tests', name: 'app_tests')]
public function index(TestRepository $testRepository): Response
{
    $tests = $testRepository->createQueryBuilder('t')
        ->orderBy('t.name', 'ASC')
        ->getQuery()
        ->getResult();
    
    return $this->render('list_of_tests/index.html.twig', [
        'controller_name' => 'ListOfTestsController',
        'tests' => $tests,
    ]);
}

    #[Route('/passtest/{id}',name:'app_passtest', methods: ['GET'])]
public function pass(Test $test, QuestionRepository $questionRepository):Response
{
    $questions = $questionRepository->findByTestId($test->getId());

    return $this->render('list_of_tests/pass.html.twig', [
        'test' => $test,
        'questions' => $questions,
    ]);
}
#[Route('/submit-answers/{id}', name: 'app_submit_answers', methods: ['POST'])]
public function submitAnswers(Request $request, Test $test, QuestionRepository $questionRepository): Response
{
    $userAnswers = $request->request->all();
    $questions = $questionRepository->findByTestId($test->getId());

    
    $score = 0;
    $correctAnswers = 0;

    
    foreach ($questions as $question) {
        $userAnswer = $userAnswers['answer_'.$question['id']];
        if ($userAnswer === $question['respone']) {
            $score++;
            $correctAnswers++;
        } else {
        }
    }

    $finalScore = round(($score / count($questions)) * 100);
    // Generate the PDF
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($options);

    $html = $this->renderView('list_of_tests/pdf_template.html.twig', [
        'test' => $test,
        'questions' => $questions,
        'userAnswers' => $userAnswers,
        'finalScore' => $finalScore,
        'correctAnswers' => $correctAnswers,
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $response = new Response($dompdf->output());
    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        'test_result.pdf'
    );
    $response->headers->set('Content-Disposition', $disposition);

    return $response;
    return $this->render('list_of_tests/results.html.twig', [
        'test' => $test,
        'questions' => $questions,
        'userAnswers' => $userAnswers,
        'finalScore' => $finalScore,
        'correctAnswers' => $correctAnswers,
    ]);
}
#[Route('/recherche_ajax', name: 'recherche_ajax_test')]
    public function rechercheAjax(Request $request): JsonResponse
    {
        $requestString = $request->query->get('searchValue');
        
        $resultats = $this->entityManager
        ->createQuery(
            'SELECT t
            FROM App\Entity\Test t
            WHERE t.name LIKE  :name')
        ->setParameter('name', '%'.$requestString.'%' )
        ->getArrayResult();
        return $this->json($resultats);
    }


}
