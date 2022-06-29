<?php
/**
 * Answer controller.
 */

namespace App\Controller;

use App\Entity\Answer;
use App\Form\Type\AnswerType;
use App\Form\Type\BestAnswerType;
use App\Repository\AnswerRepository;
use App\Service\AnswerService;
use App\Service\AnswerServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AnswerController.
 */
#[Route('/answer')]
class AnswerController extends AbstractController
{
    /**
     * Answer service.
     */
    private AnswerService $answerService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param AnswerServiceInterface $answerService Service Interface
     * @param TranslatorInterface    $translator    Translator
     */
    public function __construct(AnswerServiceInterface $answerService, TranslatorInterface $translator)
    {
        $this->answerService = $answerService;
        $this->translator = $translator;
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Answer  $answer
     *
     * @return Response HTTP response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'answer_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Answer $answer): Response
    {
        $form = $this->createForm(AnswerType::class, $answer, [
            'method' => 'PUT',
            'action' => $this->generateUrl('answer_edit', ['id' => $answer->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->answerService->save($answer);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
        }

        return $this->render('answer/edit.html.twig', [
            'form' => $form->createView(),
            'answer' => $answer,
        ]);
    }

    /**
     * Checking answer as best.
     *
     * @param Request          $request
     * @param Answer           $answer
     * @param AnswerRepository $answerRepository
     *
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/best', name: 'answer_best', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function best(Request $request, Answer $answer, AnswerRepository $answerRepository): Response
    {
        $form = $this->createForm(BestAnswerType::class, $answer, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answerRepository->save($answer);

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_successfully')
            );

            return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
        }

        return $this->render(
            'answer/best.html.twig',
            [
                'form' => $form->createView(),
                'answer' => $answer,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Answer  $answer
     *
     * @return Response HTTP response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete', name: 'answer_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Answer $answer): Response
    {
        $id = $answer->getQuestion()->getId();
        $form = $this->createForm(FormType::class, $answer, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('answer_delete', ['id' => $answer->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->answerService->delete($answer);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('question_show', ['id' => $id]);
        }

        return $this->render('answer/delete.html.twig', [
            'form' => $form->createView(),
            'answer' => $answer,
        ]);
    }
}
