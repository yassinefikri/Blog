<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default')]
    public function index(ArticleRepository $repository, ChartBuilderInterface $chartBuilder): Response
    {
        $data = $repository->getLastYearCountsPerMonths();
        $labels = [];
        $counts = [];
        foreach($data as $element){
            $labels[] = $element['formatted_date'];
            $counts[] = $element['count'];
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Articles published per month during last year',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $counts,
                ],
            ],
        ]);

        return $this->render('default/index.html.twig', [
            'chart' => $chart
        ]);
    }
}
