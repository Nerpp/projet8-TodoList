<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/task")
 * 
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/todo", name="task_todo", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index_todo(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findBy(['isDone'=>0])
        ]);
    }

    /**
     * @Route("/ended", name="task_ended", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index_ended(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findBy(['isDone'=>1])
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isGranted('ROLE_USER')) {
            $entityManager = $this->getDoctrine()->getManager();
            $task->setUser($this->getUser());
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche à bien été crée');
            return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            
            if ($this->getUser() === $task->getUser() || $this->isGranted('ROLE_ADMIN')) {
                $this->getDoctrine()->getManager()->flush();
            }
            if ($task->getIsDone()) {
                return $this->redirectToRoute('task_ended');
            }
            return $this->redirectToRoute('task_todo');
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="task_delete", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Task $task): Response
    {
        if ($this->getUser() === $task->getUser() && $this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token')) || $this->isGranted('ROLE_ADMIN') &&  $this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche à bien été supprimé');
        }

        if (!$task->getIsDone()) {
            return $this->redirectToRoute('task_todo');
        }
        return $this->redirectToRoute('task_ended');
    }
}
