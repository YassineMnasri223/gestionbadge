<?php

namespace App\Controller;

use App\Entity\Test;
use App\Form\TestType;
use App\Repository\TestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/test')]
class TestController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_test_index', methods: ['GET'])]
    public function index( TestRepository $testRepository): Response
    {
            $tests = $testRepository->findAll();
    
        return $this->render('test/index.html.twig', [
            'tests' => $tests,
        ]);
    }
    



    #[Route('/new', name: 'app_test_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TestRepository $testRepository): Response
    {
        $test = new Test();
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testRepository->save($test, true);

            return $this->redirectToRoute('app_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('test/new.html.twig', [
            'test' => $test,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_test_show', methods: ['GET'])]
    public function show(Test $test): Response
    {
        return $this->render('test/show.html.twig', [
            'test' => $test,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_test_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Test $test, TestRepository $testRepository): Response
    {
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testRepository->save($test, true);

            return $this->redirectToRoute('app_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('test/edit.html.twig', [
            'test' => $test,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_test_delete', methods: ['POST'])]
    public function delete(Request $request, Test $test, TestRepository $testRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$test->getId(), $request->request->get('_token'))) {
            $testRepository->remove($test, true);
        }

        return $this->redirectToRoute('app_test_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/search', name: 'app_test_search', methods: ['GET'])]
public function search(Request $request, TestRepository $testRepository): Response
{
    $searchTerm = $request->query->get('q');

    if (!$searchTerm) {
        return $this->redirectToRoute('app_test_index');
    }

    $tests = $testRepository->searchByName($searchTerm);

    return $this->render('test/index.html.twig', [
        'tests' => $tests,
        'searchTerm' => $searchTerm,
    ]);
}
#[Route('/recherche_ajax', name: 'recherche_ajax_formation')]
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
        return new JsonResponse(['success' => 'test test']);
    }

   

}
