<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/task")
 *
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/todo", name="task_todo", methods={"GET","POST"})
     */
    public function index_todo(TaskRepository $taskRepository): Response
    {

        if (!$this->isGranted('browser_user', $this->getUser())) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécéssaire pour visualiser cette page');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findBy(['isDone' => 0])
        ]);
    }

    /**
     * @Route("/ended", name="task_ended", methods={"GET"})
     */
    public function index_ended(TaskRepository $taskRepository): Response
    {
        if (!$this->isGranted('browser_user', $this->getUser())) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécéssaire pour visualiser cette page');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findBy(['isDone' => 1])
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if (!$this->isGranted('create_task', $this->getUser())) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécéssaire pour visualiser cette page');
            return $this->redirectToRoute('app_login');
        }

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()  && $this->isCsrfTokenValid('task_entity' . $task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $task->setUser($this->getUser());
            $entityManager->persist($task);
            $entityManager->flush();


            $this->addFlash('success', 'La tâche à bien été crée');
            return $this->redirectToRoute('task_todo', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     */
    public function show(Task $task): Response
    {
        if (!$this->isGranted('view_task', $task)) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécéssaire pour visualiser cette page');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Task $task): Response
    {

        if (!$this->isGranted('edit_task', $task)) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécéssaire pour editer ce TODO');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isCsrfTokenValid('task_entity', $request->request->get('_token'))) {
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
     * @Route("/delete/{id}", name="task_delete", methods={"POST"})
     */
    public function delete(Request $request, Task $task): Response
    {
        if (!$this->isGranted('delete_task', $task)) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécéssaire pour éffacer ce TODO');
            return $this->redirectToRoute('app_login');
        }

        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
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
