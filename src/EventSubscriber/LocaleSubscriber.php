<?php

namespace App\EventSubscriber;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    private array $domainLocales;
    private string $defaultLocale;
    private TranslatorInterface $translator;
    public function __construct(ParameterBagInterface $params, TranslatorInterface $translator)
    {
        $this->domainLocales = $params->get("app.domain_locales");
        $this->defaultLocale = 'en';
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $host = $request->getHost();

        if(isset($this->domainLocales[$host])){
            $request->setLocale($this->domainLocales[$host]);
            $request->getSession()->set('_locale', $this->domainLocales[$host]);
            $this->translator->setLocale($this->domainLocales[$host]);
        } else {
            $request->setLocale($this->defaultLocale);
        }
    }

}
