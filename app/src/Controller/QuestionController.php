<?php
/**
 * Question controller.
 */

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\Type\AnswerType;
use App\Form\Type\QuestionType;
use App\Repository\AnswerRepository;
use App\Service\AnswerService;
use App\Service\QuestionService;
use App\Service\QuestionServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class QuestionController.
 */
#[Route('/question')]
class QuestionController extends AbstractController
{
    /**
     * Question service.
     */
    private QuestionService $questionService;

    private AnswerService $answerService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     * @param QuestionServiceInterface $questionService
     * @param AnswerService $answerService
     * @param TranslatorInterface $translator
     */
    public function __construct(QuestionServiceInterface $questionService, AnswerService $answerService, TranslatorInterface $translator)
    {
        $this->questionService = $questionService;
        $this->answerService = $answerService;
        $this->translator = $translator;
    }

    /**
     * Index questions.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(
        name: 'question_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->questionService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('question/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show question.
     *
     * @param Request $request
     * @param Question $question
     * @param AnswerRepository $answerRepository
     * @return Response
     */
    #[Route(
        '/{id}',
        name: 'question_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    public function show(Request $request, Question $question, AnswerRepository $answerRepository): Response
    {
        $answer = new Answer();
        $answer->setIsBest(false);

        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);
        $id = $question->getId();

//        $user = $this->getUser();
//        if ($user) {
//            $answer->setEmail($user->getUserIdentifier());
//        }

        if ($form->isSubmitted() && $form->isValid()) {
            $answer->setQuestion($question);
            $this->answerService->save($answer);
            $this->addFlash(
                'success',
                $this->translator->trans('message.added_answer')
            );

            return $this->redirectToRoute('question_show', ['id' => $id]);
        }

        return $this->render(
            'answer/index.html.twig',
            ['question' => $question,
                'form' => $form->createView()]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/create', name: 'question_create', methods: 'GET|POST', )]
    public function create(Request $request): Response
    {
        $question = new Question();
        $user = $this->getUser();
        if ($user) {
            $question->setEmail($user->getUserIdentifier());
        }

        $form = $this->createForm(QuestionType::class, $question, ['action' => $this->generateUrl('question_create')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->questionService->save($question);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Question $question
     *
     * @return Response HTTP response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'question_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question, [
            'method' => 'PUT',
            'action' => $this->generateUrl('question_edit', ['id' => $question->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->questionService->save($question);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/edit.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Question $question
     *
     * @return Response HTTP response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete', name: 'question_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Question $question): Response
    {
        if(!$this->questionService->canBeDeleted($question)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.question_contains_answers')
            );

            return $this->redirectToRoute('question_index');
        }

        $form = $this->createForm(FormType::class, $question, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('question_delete', ['id' => $question->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->questionService->delete($question);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/delete.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
        ]);
    }

}
