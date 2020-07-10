<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocumentationController extends AbstractController
{
    use CodeReflectionTrait;

    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    protected function parameterBag() : ParameterBagInterface
    {
        return $this->parameterBag;
    }

    /**
     * @Route("/docs", name="documentation")
     */
    public function index()
    {
        return $this->render('documentation/introduction.html.twig', [
            'activeSection' => 'introduction',
            'calendarClasses' => $this->calendarClasses('1.x'),
            'calendarVersion' => '1.x',
            'calendarTwigClasses' => $this->calendarTwigClasses('1.x'),
            'calendarTwigVersion' => '1.x',
            'calendarDoctrineClasses' => $this->calendarDoctrineClasses('1.x'),
            'calendarDoctrineVersion' => '1.x',
            'calendarHolidaysClasses' => $this->calendarHolidaysClasses('1.x'),
            'calendarHolidaysVersion' => '1.x',
            'processClasses' => $this->processClasses('1.x'),
            'processVersion' => '1.x',
            'retryClasses' => $this->retryClasses('1.x'),
            'retryVersion' => '1.x',
        ]);
    }

    /**
     * @Route("/docs/getting-started", name="docs_getting_started")
     */
    public function gettingStarted() : Response
    {
        return $this->render('documentation/getting_started.html.twig', [
            'activeSection' => 'introduction',
        ]);
    }

    public function navigation(?string $activeSection = null) : Response
    {
        return $this->render('documentation/_navigation.html.twig', [
            'activeSection' => $activeSection,
            'calendarVersions' => $this->calendarVersions(),
            'calendarDoctrineVersions' => $this->calendarDoctrineVersions(),
            'calendarTwigVersions' => $this->calendarTwigVersions(),
            'calendarHolidaysVersions' => $this->calendarHolidaysVersions(),
            'processVersions' => $this->processVersions(),
            'retryVersions' => $this->retryVersions(),
        ]);
    }
}
