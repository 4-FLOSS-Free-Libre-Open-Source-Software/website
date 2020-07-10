<?php

declare(strict_types=1);

namespace App\Controller\Documentation;

use App\Controller\CodeReflectionTrait;
use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symplify\SymfonyStaticDumper\Contract\ControllerWithDataProviderInterface;

final class CalendarTwigClassMethodController extends AbstractController implements ControllerWithDataProviderInterface
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
     * @Route("/docs/calendar-twig/{version}/{classSlug}/method/{methodSlug}", name="docs_calendar_twig_class_method")
     */
    public function calendarTwigClassMethod(string $version, string $classSlug, string $methodSlug) : Response
    {
        foreach ($classes = $this->calendarTwigClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'calendar-twig',
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Calendar Twig'
                        ]);
                    }
                }

                throw $this->createNotFoundException("Class ". $classSlug . " method " . $methodSlug ." does not exists");
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }

    public function getControllerClass() : string
    {
        return __CLASS__;
    }

    public function getControllerMethod() : string
    {
        return 'calendarTwigClassMethod';
    }

    public function getArguments() : array
    {
        $arguments = [];
        foreach ($this->calendarTwigVersions() as $version => $srv) {
            foreach ($this->calendarTwigClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $arguments[] = [$version, SlugGenerator::forPHPClass($phpClass), SlugGenerator::forClassMethod($method)];
                }
            }
        }

        return $arguments;
    }
}
