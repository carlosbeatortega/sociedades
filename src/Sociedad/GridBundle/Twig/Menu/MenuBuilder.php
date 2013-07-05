<?php
namespace Sociedad\GridBundle\Twig\Menu;
use Liip\ThemeBundle\ActiveTheme;

use Symfony\Component\HttpFoundation\Request;
use Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder;

/**
 * An example howto inject a default KnpMenu to the Navbar
 * see also Resources/config/example_menu.yml
 * and example_navbar.yml
 * @author phiamo
 *
 */
class MenuBuilder extends AbstractNavbarMenuBuilder
{

    public function createMainMenu(Request $request)
    {
        $menu = $this->createNavbarMenuItem('navbar navbar-fixed-top');
        $menu->setChildrenAttribute('class', 'nav pull-left');

        $menu->addChild('Organización', array('route' => 'sociedades'));
        $menu->addChild('Sociedades', array('route' => 'sociedades'));

        // para que se vea una flechita hacia abajo
        $dropdown = $this->createDropdownMenuItem($menu,'Gente', true, array('icon' => 'caret'),array('label'=>'Gente', 'extras'=>array('safe_label'=>true)));
        //$dropdown = $this->createDropdownMenuItem($menu,'Gente', true);
        $dropdown->addChild('Socios', array('route' => 'register'));
        $dropdown->addChild('Contactos', array('route' => 'contactos'));
        $dropdown->addChild('Invitados', array('route' => 'invitadosIndex'));
        // ... add more children
        // para que se vea una flechita hacia abajo
        //$dropdown2 = $this->createDropdownMenuItem($menu, 'Herramientas', true, array('icon' => 'caret'));
        $dropdown2 = $this->createDropdownMenuItem($menu, 'Herramientas', true);
        $dropdown2->addChild('Symfony', array('uri' => 'http://www.symfony.com'));
        $dropdown2->addChild('bootstrap', array('uri' => 'http://twitter.github.com/bootstrap/'));
        $dropdown2->addChild('node.js', array('uri' => 'http://nodejs.org/'));
        $dropdown2->addChild('less', array('uri' => 'http://lesscss.org/'));

        //adding a nice divider
        $this->addDivider($dropdown2);
        $dropdown2->addChild('google', array('uri' => 'http://www.google.com/'));
        $dropdown2->addChild('node.js', array('uri' => 'http://nodejs.org/'));

        //adding a nice divider
        $this->addDivider($dropdown2);
        $dropdown2->addChild('Mohrenweiser & Partner', array('uri' => 'http://www.mohrenweiserpartner.de'));
        return $menu;
    }

    public function createRightSideDropdownMenu(Request $request, ActiveTheme $activeTheme)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav pull-right');

        // ... add theme change

        $dropdown = $this->createDropdownMenuItem($menu, "Change Theme", true, array('icon' => 'caret'));
        foreach ($activeTheme->getThemes() as $theme) {
            $themeDropdown = $dropdown->addChild($theme, array('route' => 'liip_theme_switch', 'routeParameters' => array('theme' => $theme)));
            if ($activeTheme->getName() === $theme) {
                $themeDropdown->setCurrent(true);
            }

        }

        $dropdown = $this->createDropdownMenuItem($menu, "Tools Menu", true, array('icon' => 'caret'));
        $dropdown->addChild('Symfony', array('uri' => 'http://www.symfony.com'));
        $dropdown->addChild('bootstrap', array('uri' => 'http://twitter.github.com/bootstrap/'));
        $dropdown->addChild('node.js', array('uri' => 'http://nodejs.org/'));
        $dropdown->addChild('less', array('uri' => 'http://lesscss.org/'));

        //adding a nice divider
        $this->addDivider($dropdown);
        $dropdown->addChild('google', array('uri' => 'http://www.google.com/'));
        $dropdown->addChild('node.js', array('uri' => 'http://nodejs.org/'));

        //adding a nice divider
        $this->addDivider($dropdown);
        $dropdown->addChild('Mohrenweiser & Partner', array('uri' => 'http://www.mohrenweiserpartner.de'));

        // ... add more children
        return $menu;
    }

    public function createNavbarsSubnavMenu(Request $request)
    {
        $menu = $this->createSubnavbarMenuItem();
        $menu->addChild('Top', array('uri' => '#top'));
        $menu->addChild('Navbars', array('uri' => '#navbars'));
        $menu->addChild('Template', array('uri' => '#template'));
        $menu->addChild('Menus', array('uri' => '#menus'));
        // ... add more children
        return $menu;
    }

    public function createComponentsSubnavMenu(Request $request)
    {
        $menu = $this->createSubnavbarMenuItem();
        $menu->addChild('Top', array('uri' => '#top'));
        $menu->addChild('Flashs', array('uri' => '#flashs'));
        $menu->addChild('Session Flashs', array('uri' => '#session-flashes'));
        $menu->addChild('Labels & Badges', array('uri' => '#labels-badges'));
        // ... add more children
        return $menu;
    }
}
