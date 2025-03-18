<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/employee')]
final class EmployeeController extends AbstractController
{
    #[Route('/employee/data', name: 'app_employee_data', methods: ['GET'])]
    public function getData(EmployeeRepository $employeeRepository, Request $request): JsonResponse
    {
        $query = $employeeRepository->createQueryBuilder('e');

        $salary = $request->query->get('salary');
        if (!empty($salary)) {
            $query->andWhere('e.salary = :salary')->setParameter('salary', $salary);
        }

        $columns = [
            'e.id',
            'e.profile_image',
            'e.first_name',
            'e.last_name',
            'e.age',
            'e.hobby',
            'e.gender',
            'e.about_me',
            'e.salary',
            'e.roles',
            'e.city'
        ];

        $orderData = $request->query->all()['order'] ?? [];
        $orderColumnIndex = (int)$orderData[0]['column'];
        $orderDirection = !empty($orderData[0]['dir']) ? $orderData[0]['dir'] : 'asc';

        if ($orderColumnIndex !== null && isset($columns[$orderColumnIndex])) {
            $query->orderBy($columns[$orderColumnIndex], $orderDirection);
        }

        $totalRecords = count($query->getQuery()->getResult());

        $query->setFirstResult($request->query->get('start'))
            ->setMaxResults($request->query->get('length'));

        $employees = $query->getQuery()->getResult();

        $data = [];
        foreach ($employees as $employee) {
            $data[] = [
                $employee->getId(),
                '<img width="100" src="' . $employee->getProfileImage() . '"/>',
                $employee->getFirstName(),
                $employee->getLastName(),
                $employee->getAge(),
                $employee->getHobbyNames(),
                $employee->getGender()->name,
                $employee->getAboutMe(),
                $employee->getSalary(),
                $employee->getRoles()->name,
                $employee->getCity(),
                '<div class="col-md-12 d-flex justify-content-center gap-2">
                    <a class="btn btn-info" href="' . $this->generateUrl('app_employee_show', ['id' => $employee->getId()]) . '">Show</a> 
                    <a class="btn btn-info" href="' . $this->generateUrl('app_employee_edit', ['id' => $employee->getId()]) . '">Edit</a>
                </div>'
            ];
        }

        return new JsonResponse([
            "draw" => $request->query->get('draw'),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data
        ]);
    }


    #[Route(name: 'app_employee_index', methods: ['GET'])]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        return $this->render('employee/index.html.twig');
    }

    #[Route('/new', name: 'app_employee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('profile_image')->getData();
            if ($image) {
                $newFilename = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('profile_images_directory'), $newFilename);
                $employee->setProfileImage($newFilename);
            }
            $entityManager->persist($employee);
            $entityManager->flush();

            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_employee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employee $employee, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('profile_image')->getData();
            if ($image) {
                $newFilename = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('profile_images_directory'), $newFilename);
                $employee->setProfileImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_delete', methods: ['POST'])]
    public function delete(Request $request, Employee $employee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $employee->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($employee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
    }
}
