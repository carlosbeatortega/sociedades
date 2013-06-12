<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appdevUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appdevUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);

        // _welcome
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', '_welcome');
            }

            return array (  '_controller' => 'Acme\\DemoBundle\\Controller\\WelcomeController::indexAction',  '_route' => '_welcome',);
        }

        // _demo_login
        if ($pathinfo === '/demo/secured/login') {
            return array (  '_controller' => 'Acme\\DemoBundle\\Controller\\SecuredController::loginAction',  '_route' => '_demo_login',);
        }

        // _demo_logout
        if ($pathinfo === '/demo/secured/logout') {
            return array (  '_controller' => 'Acme\\DemoBundle\\Controller\\SecuredController::logoutAction',  '_route' => '_demo_logout',);
        }

        // acme_demo_secured_hello
        if ($pathinfo === '/demo/secured/hello') {
            return array (  'name' => 'World',  '_controller' => 'Acme\\DemoBundle\\Controller\\SecuredController::helloAction',  '_route' => 'acme_demo_secured_hello',);
        }

        // _demo_secured_hello
        if (0 === strpos($pathinfo, '/demo/secured/hello') && preg_match('#^/demo/secured/hello/(?<name>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Acme\\DemoBundle\\Controller\\SecuredController::helloAction',)), array('_route' => '_demo_secured_hello'));
        }

        // _demo_secured_hello_admin
        if (0 === strpos($pathinfo, '/demo/secured/hello/admin') && preg_match('#^/demo/secured/hello/admin/(?<name>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Acme\\DemoBundle\\Controller\\SecuredController::helloadminAction',)), array('_route' => '_demo_secured_hello_admin'));
        }

        // _demo
        if (rtrim($pathinfo, '/') === '/demo') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', '_demo');
            }

            return array (  '_controller' => 'Acme\\DemoBundle\\Controller\\DemoController::indexAction',  '_route' => '_demo',);
        }

        // _demo_hello
        if (0 === strpos($pathinfo, '/demo/hello') && preg_match('#^/demo/hello/(?<name>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Acme\\DemoBundle\\Controller\\DemoController::helloAction',)), array('_route' => '_demo_hello'));
        }

        // _demo_contact
        if ($pathinfo === '/demo/contact') {
            return array (  '_controller' => 'Acme\\DemoBundle\\Controller\\DemoController::contactAction',  '_route' => '_demo_contact',);
        }

        // _wdt
        if (0 === strpos($pathinfo, '/_wdt') && preg_match('#^/_wdt/(?<token>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::toolbarAction',)), array('_route' => '_wdt'));
        }

        if (0 === strpos($pathinfo, '/_profiler')) {
            // _profiler_search
            if ($pathinfo === '/_profiler/search') {
                return array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::searchAction',  '_route' => '_profiler_search',);
            }

            // _profiler_purge
            if ($pathinfo === '/_profiler/purge') {
                return array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::purgeAction',  '_route' => '_profiler_purge',);
            }

            // _profiler_info
            if (0 === strpos($pathinfo, '/_profiler/info') && preg_match('#^/_profiler/info/(?<about>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::infoAction',)), array('_route' => '_profiler_info'));
            }

            // _profiler_import
            if ($pathinfo === '/_profiler/import') {
                return array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::importAction',  '_route' => '_profiler_import',);
            }

            // _profiler_export
            if (0 === strpos($pathinfo, '/_profiler/export') && preg_match('#^/_profiler/export/(?<token>[^/\\.]+)\\.txt$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::exportAction',)), array('_route' => '_profiler_export'));
            }

            // _profiler_phpinfo
            if ($pathinfo === '/_profiler/phpinfo') {
                return array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::phpinfoAction',  '_route' => '_profiler_phpinfo',);
            }

            // _profiler_search_results
            if (preg_match('#^/_profiler/(?<token>[^/]+)/search/results$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::searchResultsAction',)), array('_route' => '_profiler_search_results'));
            }

            // _profiler
            if (preg_match('#^/_profiler/(?<token>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::panelAction',)), array('_route' => '_profiler'));
            }

            // _profiler_redirect
            if (rtrim($pathinfo, '/') === '/_profiler') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', '_profiler_redirect');
                }

                return array (  '_controller' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\RedirectController::redirectAction',  'route' => '_profiler_search_results',  'token' => 'empty',  'ip' => '',  'url' => '',  'method' => '',  'limit' => '10',  '_route' => '_profiler_redirect',);
            }

        }

        if (0 === strpos($pathinfo, '/_configurator')) {
            // _configurator_home
            if (rtrim($pathinfo, '/') === '/_configurator') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', '_configurator_home');
                }

                return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::checkAction',  '_route' => '_configurator_home',);
            }

            // _configurator_step
            if (0 === strpos($pathinfo, '/_configurator/step') && preg_match('#^/_configurator/step/(?<index>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::stepAction',)), array('_route' => '_configurator_step'));
            }

            // _configurator_final
            if ($pathinfo === '/_configurator/final') {
                return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::finalAction',  '_route' => '_configurator_final',);
            }

        }

        // borrareservamesa
        if (rtrim($pathinfo, '/') === '/borrareservamesa') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'borrareservamesa');
            }

            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\DefaultController::borrareservamesaAction',  '_route' => 'borrareservamesa',);
        }

        // reservarmesa
        if (rtrim($pathinfo, '/') === '/reservarmesa') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'reservarmesa');
            }

            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\DefaultController::reservarmesaAction',  '_route' => 'reservarmesa',);
        }

        // grabamesa
        if (rtrim($pathinfo, '/') === '/grabamesa') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'grabamesa');
            }

            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\DefaultController::grabamesaAction',  '_route' => 'grabamesa',);
        }

        // borramesa
        if (rtrim($pathinfo, '/') === '/borramesa') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'borramesa');
            }

            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\DefaultController::borramesaAction',  '_route' => 'borramesa',);
        }

        // portada
        if ($pathinfo === '/portada') {
            return array (  '_controller' => 'Sociedad\\SociedadesBundle\\Controller\\DefaultController::portadaAction',  '_route' => 'portada',);
        }

        // plano
        if (0 === strpos($pathinfo, '/plano') && preg_match('#^/plano/(?<id>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociedadesBundle\\Controller\\DefaultController::mapaGoogleAction',)), array('_route' => 'plano'));
        }

        // friends
        if ($pathinfo === '/friends') {
            return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\DefaultController::friendsFacebookAction',  '_route' => 'friends',);
        }

        // registro
        if ($pathinfo === '/registro') {
            return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\RegistrationController::registerAction',  '_route' => 'registro',);
        }

        // register
        if ($pathinfo === '/register') {
            return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\RegistrationController::registerAction',  '_route' => 'register',);
        }

        // contactos_show
        if (0 === strpos($pathinfo, '/contactos') && preg_match('#^/contactos/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\ContactosController::showAction',)), array('_route' => 'contactos_show'));
        }

        // contactos_new
        if ($pathinfo === '/contactos/new') {
            return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\ContactosController::newAction',  '_route' => 'contactos_new',);
        }

        // contactos_create
        if ($pathinfo === '/contactos/create') {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_contactos_create;
            }

            return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\ContactosController::createAction',  '_route' => 'contactos_create',);
        }
        not_contactos_create:

        // contactos_edit
        if (0 === strpos($pathinfo, '/contactos') && preg_match('#^/contactos/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\ContactosController::editAction',)), array('_route' => 'contactos_edit'));
        }

        // contactos_update
        if (0 === strpos($pathinfo, '/contactos') && preg_match('#^/contactos/(?<id>[^/]+)/update$#s', $pathinfo, $matches)) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_contactos_update;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\ContactosController::updateAction',)), array('_route' => 'contactos_update'));
        }
        not_contactos_update:

        // contactos_delete
        if (0 === strpos($pathinfo, '/contactos') && preg_match('#^/contactos/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_contactos_delete;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\ContactosController::deleteAction',)), array('_route' => 'contactos_delete'));
        }
        not_contactos_delete:

        // sociedad_socios_default_index
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?<name>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\DefaultController::indexAction',)), array('_route' => 'sociedad_socios_default_index'));
        }

        // socios
        if (rtrim($pathinfo, '/') === '/socios') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'socios');
            }

            return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\SociosController::indexAction',  '_route' => 'socios',);
        }

        // socios_show
        if (0 === strpos($pathinfo, '/socios') && preg_match('#^/socios/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\SociosController::showAction',)), array('_route' => 'socios_show'));
        }

        // socios_new
        if ($pathinfo === '/socios/new') {
            return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\SociosController::newAction',  '_route' => 'socios_new',);
        }

        // socios_create
        if ($pathinfo === '/socios/create') {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_socios_create;
            }

            return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\SociosController::createAction',  '_route' => 'socios_create',);
        }
        not_socios_create:

        // socios_edit
        if (0 === strpos($pathinfo, '/socios') && preg_match('#^/socios/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\SociosController::editAction',)), array('_route' => 'socios_edit'));
        }

        // socios_update
        if (0 === strpos($pathinfo, '/socios') && preg_match('#^/socios/(?<id>[^/]+)/update$#s', $pathinfo, $matches)) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_socios_update;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\SociosController::updateAction',)), array('_route' => 'socios_update'));
        }
        not_socios_update:

        // socios_delete
        if (0 === strpos($pathinfo, '/socios') && preg_match('#^/socios/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_socios_delete;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\SociosController::deleteAction',)), array('_route' => 'socios_delete'));
        }
        not_socios_delete:

        // socios_activa
        if ($pathinfo === '/socios/activa') {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_socios_activa;
            }

            return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\SociosController::activaAction',  '_route' => 'socios_activa',);
        }
        not_socios_activa:

        // socios_activa_sociedades
        if (0 === strpos($pathinfo, '/socios/activaSociedades') && preg_match('#^/socios/activaSociedades/(?<socios>[^/]+)$#s', $pathinfo, $matches)) {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_socios_activa_sociedades;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\SociosController::activaSociedadesAction',)), array('_route' => 'socios_activa_sociedades'));
        }
        not_socios_activa_sociedades:

        // sociedad_sociedades_default_portada
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?<name>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociedadesBundle\\Controller\\DefaultController::portadaAction',)), array('_route' => 'sociedad_sociedades_default_portada'));
        }

        // sociedades
        if (rtrim($pathinfo, '/') === '/sociedades') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'sociedades');
            }

            return array (  '_controller' => 'Sociedad\\SociedadesBundle\\Controller\\SociedadesController::indexAction',  '_route' => 'sociedades',);
        }

        // sociedades_show
        if (0 === strpos($pathinfo, '/sociedades') && preg_match('#^/sociedades/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociedadesBundle\\Controller\\SociedadesController::showAction',)), array('_route' => 'sociedades_show'));
        }

        // sociedades_new
        if ($pathinfo === '/sociedades/new') {
            return array (  '_controller' => 'Sociedad\\SociedadesBundle\\Controller\\SociedadesController::newAction',  '_route' => 'sociedades_new',);
        }

        // sociedades_create
        if ($pathinfo === '/sociedades/create') {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_sociedades_create;
            }

            return array (  '_controller' => 'Sociedad\\SociedadesBundle\\Controller\\SociedadesController::createAction',  '_route' => 'sociedades_create',);
        }
        not_sociedades_create:

        // sociedades_edit
        if (0 === strpos($pathinfo, '/sociedades') && preg_match('#^/sociedades/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociedadesBundle\\Controller\\SociedadesController::editAction',)), array('_route' => 'sociedades_edit'));
        }

        // sociedades_update
        if (0 === strpos($pathinfo, '/sociedades') && preg_match('#^/sociedades/(?<id>[^/]+)/update$#s', $pathinfo, $matches)) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_sociedades_update;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociedadesBundle\\Controller\\SociedadesController::updateAction',)), array('_route' => 'sociedades_update'));
        }
        not_sociedades_update:

        // sociedades_delete
        if (0 === strpos($pathinfo, '/sociedades') && preg_match('#^/sociedades/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_sociedades_delete;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociedadesBundle\\Controller\\SociedadesController::deleteAction',)), array('_route' => 'sociedades_delete'));
        }
        not_sociedades_delete:

        // sociedad_recetas_default_index
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?<name>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\RecetasBundle\\Controller\\DefaultController::indexAction',)), array('_route' => 'sociedad_recetas_default_index'));
        }

        // sociedad_comandas_default_index
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?<name>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ComandasBundle\\Controller\\DefaultController::indexAction',)), array('_route' => 'sociedad_comandas_default_index'));
        }

        // sociedad_reservas_default_index
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?<name>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\DefaultController::indexAction',)), array('_route' => 'sociedad_reservas_default_index'));
        }

        // mesas
        if (rtrim($pathinfo, '/') === '/mesas') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'mesas');
            }

            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\MesasController::indexAction',  '_route' => 'mesas',);
        }

        // mesas_show
        if (0 === strpos($pathinfo, '/mesas') && preg_match('#^/mesas/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\MesasController::showAction',)), array('_route' => 'mesas_show'));
        }

        // mesas_new
        if ($pathinfo === '/mesas/new') {
            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\MesasController::newAction',  '_route' => 'mesas_new',);
        }

        // mesas_create
        if ($pathinfo === '/mesas/create') {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_mesas_create;
            }

            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\MesasController::createAction',  '_route' => 'mesas_create',);
        }
        not_mesas_create:

        // mesas_edit
        if (0 === strpos($pathinfo, '/mesas') && preg_match('#^/mesas/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\MesasController::editAction',)), array('_route' => 'mesas_edit'));
        }

        // mesas_update
        if (0 === strpos($pathinfo, '/mesas') && preg_match('#^/mesas/(?<id>[^/]+)/update$#s', $pathinfo, $matches)) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_mesas_update;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\MesasController::updateAction',)), array('_route' => 'mesas_update'));
        }
        not_mesas_update:

        // mesas_delete
        if (0 === strpos($pathinfo, '/mesas') && preg_match('#^/mesas/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_mesas_delete;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\MesasController::deleteAction',)), array('_route' => 'mesas_delete'));
        }
        not_mesas_delete:

        // mesasplanta
        if (0 === strpos($pathinfo, '/mesas') && preg_match('#^/mesas/(?<plantaid>[^/]+)/?$#s', $pathinfo, $matches)) {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'mesasplanta');
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\MesasController::mesasplantaAction',)), array('_route' => 'mesasplanta'));
        }

        // plantas
        if (rtrim($pathinfo, '/') === '/plantas') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'plantas');
            }

            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\PlantasController::indexAction',  '_route' => 'plantas',);
        }

        // plantas_show
        if (0 === strpos($pathinfo, '/plantas') && preg_match('#^/plantas/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\PlantasController::showAction',)), array('_route' => 'plantas_show'));
        }

        // plantas_new
        if ($pathinfo === '/plantas/new') {
            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\PlantasController::newAction',  '_route' => 'plantas_new',);
        }

        // plantas_create
        if ($pathinfo === '/plantas/create') {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_plantas_create;
            }

            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\PlantasController::createAction',  '_route' => 'plantas_create',);
        }
        not_plantas_create:

        // plantas_edit
        if (0 === strpos($pathinfo, '/plantas') && preg_match('#^/plantas/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\PlantasController::editAction',)), array('_route' => 'plantas_edit'));
        }

        // plantas_update
        if (0 === strpos($pathinfo, '/plantas') && preg_match('#^/plantas/(?<id>[^/]+)/update$#s', $pathinfo, $matches)) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_plantas_update;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\PlantasController::updateAction',)), array('_route' => 'plantas_update'));
        }
        not_plantas_update:

        // plantas_delete
        if (0 === strpos($pathinfo, '/plantas') && preg_match('#^/plantas/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_plantas_delete;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\PlantasController::deleteAction',)), array('_route' => 'plantas_delete'));
        }
        not_plantas_delete:

        // reservas
        if (rtrim($pathinfo, '/') === '/reservas') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'reservas');
            }

            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\ReservasController::indexAction',  '_route' => 'reservas',);
        }

        // reservas_show
        if (0 === strpos($pathinfo, '/reservas') && preg_match('#^/reservas/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\ReservasController::showAction',)), array('_route' => 'reservas_show'));
        }

        // reservas_new
        if ($pathinfo === '/reservas/new') {
            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\ReservasController::newAction',  '_route' => 'reservas_new',);
        }

        // reservas_create
        if ($pathinfo === '/reservas/create') {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_reservas_create;
            }

            return array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\ReservasController::createAction',  '_route' => 'reservas_create',);
        }
        not_reservas_create:

        // reservas_edit
        if (0 === strpos($pathinfo, '/reservas') && preg_match('#^/reservas/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\ReservasController::editAction',)), array('_route' => 'reservas_edit'));
        }

        // reservas_update
        if (0 === strpos($pathinfo, '/reservas') && preg_match('#^/reservas/(?<id>[^/]+)/update$#s', $pathinfo, $matches)) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_reservas_update;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\ReservasController::updateAction',)), array('_route' => 'reservas_update'));
        }
        not_reservas_update:

        // reservas_delete
        if (0 === strpos($pathinfo, '/reservas') && preg_match('#^/reservas/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_reservas_delete;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\ReservasController::deleteAction',)), array('_route' => 'reservas_delete'));
        }
        not_reservas_delete:

        // reservasplanta
        if (0 === strpos($pathinfo, '/reservas') && preg_match('#^/reservas/(?<plantaid>[^/]+)/?$#s', $pathinfo, $matches)) {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'reservasplanta');
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\ReservasBundle\\Controller\\ReservasController::reservasplantaAction',)), array('_route' => 'reservasplanta'));
        }

        // sociedad_almacen_default_index
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?<name>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\AlmacenBundle\\Controller\\DefaultController::indexAction',)), array('_route' => 'sociedad_almacen_default_index'));
        }

        // cabeceras
        if (rtrim($pathinfo, '/') === '/cabeceras') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'cabeceras');
            }

            return array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\CabecerasController::indexAction',  '_route' => 'cabeceras',);
        }

        // cabeceras_show
        if (0 === strpos($pathinfo, '/cabeceras') && preg_match('#^/cabeceras/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\CabecerasController::showAction',)), array('_route' => 'cabeceras_show'));
        }

        // cabeceras_new
        if ($pathinfo === '/cabeceras/new') {
            return array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\CabecerasController::newAction',  '_route' => 'cabeceras_new',);
        }

        // cabeceras_create
        if ($pathinfo === '/cabeceras/create') {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_cabeceras_create;
            }

            return array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\CabecerasController::createAction',  '_route' => 'cabeceras_create',);
        }
        not_cabeceras_create:

        // cabeceras_edit
        if (0 === strpos($pathinfo, '/cabeceras') && preg_match('#^/cabeceras/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\CabecerasController::editAction',)), array('_route' => 'cabeceras_edit'));
        }

        // cabeceras_update
        if (0 === strpos($pathinfo, '/cabeceras') && preg_match('#^/cabeceras/(?<id>[^/]+)/update$#s', $pathinfo, $matches)) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_cabeceras_update;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\CabecerasController::updateAction',)), array('_route' => 'cabeceras_update'));
        }
        not_cabeceras_update:

        // cabeceras_delete
        if (0 === strpos($pathinfo, '/cabeceras') && preg_match('#^/cabeceras/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_cabeceras_delete;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\CabecerasController::deleteAction',)), array('_route' => 'cabeceras_delete'));
        }
        not_cabeceras_delete:

        // sociedad_grid_default_index
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?<name>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\DefaultController::indexAction',)), array('_route' => 'sociedad_grid_default_index'));
        }

        // fos_user_security_login
        if ($pathinfo === '/login') {
            return array (  '_controller' => 'FOS\\UserBundle\\Controller\\SecurityController::loginAction',  '_route' => 'fos_user_security_login',);
        }

        // fos_user_security_check
        if ($pathinfo === '/login_check') {
            return array (  '_controller' => 'FOS\\UserBundle\\Controller\\SecurityController::checkAction',  '_route' => 'fos_user_security_check',);
        }

        // fos_user_security_logout
        if ($pathinfo === '/logout') {
            return array (  '_controller' => 'FOS\\UserBundle\\Controller\\SecurityController::logoutAction',  '_route' => 'fos_user_security_logout',);
        }

        if (0 === strpos($pathinfo, '/profile')) {
            // fos_user_profile_show
            if (rtrim($pathinfo, '/') === '/profile') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_profile_show;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'fos_user_profile_show');
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ProfileController::showAction',  '_route' => 'fos_user_profile_show',);
            }
            not_fos_user_profile_show:

            // fos_user_profile_edit
            if ($pathinfo === '/profile/edit') {
                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ProfileController::editAction',  '_route' => 'fos_user_profile_edit',);
            }

        }

        if (0 === strpos($pathinfo, '/register')) {
            // fos_user_registration_register
            if (rtrim($pathinfo, '/') === '/register') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'fos_user_registration_register');
                }

                return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\RegistrationController::registerAction',  '_route' => 'fos_user_registration_register',);
            }

            // fos_user_registration_check_email
            if ($pathinfo === '/register/check-email') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_registration_check_email;
                }

                return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\RegistrationController::checkEmailAction',  '_route' => 'fos_user_registration_check_email',);
            }
            not_fos_user_registration_check_email:

            // fos_user_registration_confirm
            if (0 === strpos($pathinfo, '/register/confirm') && preg_match('#^/register/confirm/(?<token>[^/]+)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_registration_confirm;
                }

                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\RegistrationController::confirmAction',)), array('_route' => 'fos_user_registration_confirm'));
            }
            not_fos_user_registration_confirm:

            // fos_user_registration_confirmed
            if ($pathinfo === '/register/confirmed') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_registration_confirmed;
                }

                return array (  '_controller' => 'Sociedad\\SociosBundle\\Controller\\RegistrationController::confirmedAction',  '_route' => 'fos_user_registration_confirmed',);
            }
            not_fos_user_registration_confirmed:

        }

        if (0 === strpos($pathinfo, '/resetting')) {
            // fos_user_resetting_request
            if ($pathinfo === '/resetting/request') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_resetting_request;
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::requestAction',  '_route' => 'fos_user_resetting_request',);
            }
            not_fos_user_resetting_request:

            // fos_user_resetting_send_email
            if ($pathinfo === '/resetting/send-email') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_fos_user_resetting_send_email;
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::sendEmailAction',  '_route' => 'fos_user_resetting_send_email',);
            }
            not_fos_user_resetting_send_email:

            // fos_user_resetting_check_email
            if ($pathinfo === '/resetting/check-email') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_resetting_check_email;
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::checkEmailAction',  '_route' => 'fos_user_resetting_check_email',);
            }
            not_fos_user_resetting_check_email:

            // fos_user_resetting_reset
            if (0 === strpos($pathinfo, '/resetting/reset') && preg_match('#^/resetting/reset/(?<token>[^/]+)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_fos_user_resetting_reset;
                }

                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::resetAction',)), array('_route' => 'fos_user_resetting_reset'));
            }
            not_fos_user_resetting_reset:

        }

        // fos_user_change_password
        if ($pathinfo === '/profile/change-password') {
            if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                goto not_fos_user_change_password;
            }

            return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ChangePasswordController::changePasswordAction',  '_route' => 'fos_user_change_password',);
        }
        not_fos_user_change_password:

        // _security_check
        if ($pathinfo === '/login_check') {
            return array('_route' => '_security_check');
        }

        // _security_logout
        if ($pathinfo === '/logout') {
            return array('_route' => '_security_logout');
        }

        // calendario
        if ($pathinfo === '/externo') {
            return array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\DefaultController::externoAction',  '_route' => 'calendario',);
        }

        // calendarios
        if ($pathinfo === '/calendarios') {
            return array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\DefaultController::listacalendariosAction',  '_route' => 'calendarios',);
        }

        // borravisita
        if ($pathinfo === '/borravisita') {
            return array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\DefaultController::borraVisitaAction',  '_route' => 'borravisita',);
        }

        // contactos
        if ($pathinfo === '/contactos') {
            return array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\DefaultController::clientAction',  '_route' => 'contactos',);
        }

        // contactosprowin
        if ($pathinfo === '/contactosprowin') {
            return array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\DefaultController::setClientAction',  '_route' => 'contactosprowin',);
        }

        // limpia
        if ($pathinfo === '/limpia') {
            return array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\DefaultController::limpiaAction',  '_route' => 'limpia',);
        }

        // cerrarsesion
        if ($pathinfo === '/cerrarsesion') {
            return array (  '_controller' => 'Sociedad\\GridBundle\\Controller\\DefaultController::cerrarsesionAction',  '_route' => 'cerrarsesion',);
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
