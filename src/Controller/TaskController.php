<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class TaskController extends AbstractController
{
    #[Route('/', name: 'task_index')]
    public function index(TaskRepository $repository): Response
    {
        $tasks = $repository->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/add', name:'task_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
    $taskTitle = $request->request->get('title');

    $task = new Task();

    $task->setTitle($taskTitle);
    $task->setCreatedAt(new \DateTimeImmutable());
    $task->setIsDone(false);

    $entityManagerInterface->persist($task);
    $entityManagerInterface->flush();

    return $this->redirectToRoute('task_index');
    }

    #[Route('/toogle/{id}', name:'task_toogle', methods: ['POST'])]
    public function toggle(Task $task, EntityManagerInterface $entityManagerInterface): Response
    {
        if ($task->isDone()) {
            $task->setIsDone(false);
        } else {
            $task->setIsDone(true);
        }
    $entityManagerInterface->flush();

        return $this->redirectToRoute('task_index');
    }

}
