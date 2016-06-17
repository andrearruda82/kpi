<?php

namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class IndicadorAction
{
    private $view;
    private $router;
    private $indicador_service;
    private $indicador_form;

    public function __construct(\Slim\Views\Twig $view, \Slim\Router $router, \App\Service\IndicadorService $indicador_service, \App\Form\IndicadorForm $indicador_form)
    {
        $this->view = $view;
        $this->router = $router;
        $this->setIndicadorService($indicador_service);
        $this->setIndicadorForm($indicador_form);
    }

    public function index(Request $request, Response $response, $args)
    {
        $kpis = $this->getIndicadorService()->getList();
        $this->view->render($response, 'indicador/index.twig', [
            'kpis' => $kpis
        ]);
        return $response;
    }

    public function add(Request $request, Response $response, $args)
    {
        $form = $this->getIndicadorForm();

        if($request->isPost())
        {
            $data_form = $request->getParsedBody();

            $data_form = array(
                'fieldset_informacoes' => array(
                    'date' => $request->getParam('date'),
                    'responsible' => $request->getParam('responsible')
                ),
                'fieldset_periodo' => array(
                    'period_first_initial' => '01/' . $request->getParam('period_first_initial'),
                    'period_first_end' => '01/' . $request->getParam('period_first_end'),
                    'period_second_initial' => '01/' . $request->getParam('period_second_initial'),
                    'period_second_end' => '01/' . $request->getParam('period_second_end'),
                )
            );

            $form->setData($data_form);
            if($form->isValid())
            {

            }
            else
            {
                $data_form['fieldset_periodo'] = array(
                    'period_first_initial' => $request->getParam('period_first_initial'),
                    'period_first_end' => $request->getParam('period_first_end'),
                    'period_second_initial' => $request->getParam('period_second_initial'),
                    'period_second_end' => $request->getParam('period_second_end'),
                );

                $form->setData($data_form);
            }
        }

        $this->view->render($response, 'indicador/add.twig', array(
            'form' => $form
        ));
        return $response;
    }

    /**
     * @return \App\Service\IndicadorService
     */
    public function getIndicadorService()
    {
        return $this->indicador_service;
    }

    /**
     * @param mixed $indicador_service
     */
    public function setIndicadorService($indicador_service)
    {
        $this->indicador_service = $indicador_service;
    }

    /**
     * @return \App\Form\IndicadorForm
     */
    public function getIndicadorForm()
    {
        return $this->indicador_form;
    }

    /**
     * @param mixed $indicador_form
     */
    public function setIndicadorForm($indicador_form)
    {
        $this->indicador_form = $indicador_form;
    }


}