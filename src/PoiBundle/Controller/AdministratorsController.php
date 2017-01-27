<?php

namespace PoiBundle\Controller;

use PoiBundle\Additional\PaginationHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use PoiBundle\Entity\Administrators;
use PoiBundle\Form\AdministratorsType;

class AdministratorsController extends Controller
{

    /**
     * @Security("is_granted('VIEW')")
     */
    public function indexAction($page = 1)
    {
        $query = $this->getDoctrine()->getRepository('PoiBundle:Administrators')->findAllQuery();
        $paginationHelper = new PaginationHelper($query, $this->getParameter("administrators_index_elements"), $page);
        $paginationHelper->makePagination();
        return $this->render('administrators/index.html.twig', array(
            'administrators' => $paginationHelper,
            'page' => $page,
        ));
    }

    /**
     * @Security("is_granted('VIEW', administrator)")
     */
    public function showAction(Administrators $administrator)
    {
        $deleteForm = $this->createDeleteForm($administrator);
        return $this->render('administrators/show.html.twig', array(
            'administrator' => $administrator,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Security("is_granted('EDIT', administrator)")
     */
    public function editAction(Request $request, Administrators $administrator)
    {
        $deleteForm = $this->createDeleteForm($administrator);
        $editForm = $this->createForm('PoiBundle\Form\AdministratorsType', $administrator);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($administrator);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Administrator edytowany prawidłowo');

            }catch(\Doctrine\ORM\ORMException $e){
                var_dump("kupa");
                $this->addFlash(
                    'error',
                    'Błąd bazy danych podczas edycji danych administratora');
            }
            catch (\Exception $e){
                $this->addFlash(
                    'error',
                    'Błąd podczas edycji danych administratora');
            }
            return $this->redirectToRoute('administrators_edit', array('id' => $administrator->getId()));
        }
        elseif($editForm->isSubmitted() && !$editForm->isValid()){
            $this->addFlash(
                'error',
                'Wypełnij wszystkie pola prawidłowo');
        }

        return $this->render('administrators/edit.html.twig', array(
            'administrator' => $administrator,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Security("is_granted('EDIT', administrator)")
     */
    public function blockAction(Administrators $administrator, $page){
        $user = $this->getUser();
        if($administrator->getId() === $user->getId()){
            $this->addFlash(
                'error',
                'Nie możesz zablokować sam siebie'
            );
        }
        else{
            try{
                $administrator->setUnblocked(0);
                $em = $this->getDoctrine()->getManager();
                $em->persist($administrator);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Administrator zablokowany prawidłowo'
                );
            }catch (\Exception $e){
                $this->addFlash(
                    'error',
                    'Błąd podczas blokowania administratora');
            }
        }
        return $this->redirectToRoute('administrators_index', array('page' => $page));
    }

    /**
     * @Security("is_granted('EDIT', administrator)")
     */
    public function unblockAction(Administrators $administrator, $page){
        try{
            $administrator->setUnblocked(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($administrator);
            $em->flush();
            $this->addFlash(
                'success',
                'Administrator odblokowany prawidłowo'
            );
        }catch (\Exception $e){
            $this->addFlash(
                'error',
                'Błąd podczas odblokowywania administratora');
        }
        return $this->redirectToRoute('administrators_index', array('page' => $page));
    }

    /**
     * @Security("is_granted('DELETE', administrator)")
     */
    public function deleteAction(Request $request, Administrators $administrator)
    {
        $form = $this->createDeleteForm($administrator);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if($administrator->getId() === $user->getId()){
                $this->addFlash(
                    'error',
                    'Nie możesz usunąć sam siebie');
            }
            else{
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($administrator);
                    $em->flush();
                    $this->addFlash(
                        'success',
                        'Administrator usunięty prawidłowo');
                }catch (\Exception $e){
                    $this->addFlash(
                        'error',
                        'Błąd podczas usuwania administratora');
                }
            }
        }
        return $this->redirectToRoute('administrators_index');
    }

    private function createDeleteForm(Administrators $administrator)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('administrators_delete', array('id' => $administrator->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}