<?php

/* SociedadSociedadesBundle:Default:portada.html.twig */
class __TwigTemplate_85739d99c4cae414ecf4a6c2d9856cd1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SociedadSociedadesBundle::layoutconplano.html.twig");

        $this->blocks = array(
            'menusuperior' => array($this, 'block_menusuperior'),
            'content' => array($this, 'block_content'),
            'content_sidebar' => array($this, 'block_content_sidebar'),
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SociedadSociedadesBundle::layoutconplano.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_menusuperior($context, array $blocks = array())
    {
        // line 4
        echo "       ";
        echo $this->env->getExtension('knp_menu')->render("menu");
        echo "
";
    }

    // line 6
    public function block_content($context, array $blocks = array())
    {
        // line 7
        echo "             <div class=\"span6\">
                <img alt=\"Anagrama\" src=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sociedad/uploads/images/chiquitero.png"), "html", null, true);
        echo "\">
    
                <h3><a href=\"#\"> RELACION DE SOCIEDADES </a></h3>

                   ";
        // line 12
        if ($this->getContext($context, "columnas")) {
            // line 13
            echo "                       ";
            $this->env->loadTemplate("SociedadGridBundle::cadaGridColumnas.html.twig")->display($context);
            // line 14
            echo "                   ";
        } else {
            echo "    
                        <table id=\"tablaprincipal\" class=\"tablagrid sociedadportada table table-striped\" cellspacing=\"0\">
                        <thead>
                            <tr>
                                ";
            // line 18
            echo $this->env->getExtension('grid')->linkInterno($this->env, $context, "", array("id" => "colEditarFoto", "class" => "ancho1", "texto" => "Foto"), "", "", "th");
            echo "
                                ";
            // line 19
            echo $this->env->getExtension('grid')->linkInterno($this->env, $context, "", array("id" => "colNombre", "class" => "ancho25", "texto" => "Nombre"), "", "", "th");
            echo "
                                ";
            // line 20
            echo $this->env->getExtension('grid')->linkInterno($this->env, $context, "", array("id" => "colDireccion", "class" => "ancho15", "texto" => "Direccion"), "", "", "th");
            echo "
                            </tr>
                        </thead>
                        <tbody>
                        ";
            // line 24
            $context["linea"] = 0;
            // line 25
            echo "                        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "entities"));
            $context['_iterated'] = false;
            foreach ($context['_seq'] as $context["_key"] => $context["entity"]) {
                // line 26
                echo "                             ";
                if ((0 == $this->getContext($context, "linea") % 2)) {
                    // line 27
                    echo "                               <tr>
                             ";
                } else {
                    // line 29
                    echo "                                <tr class=\"info\">
                             ";
                }
                // line 31
                echo "                                ";
                echo $this->env->getExtension('grid')->linkInterno($this->env, $context, "sociedades_show", array("entity_id" => "entity.id", "name" => "Sociedades", "classa" => "'edit imageGrid'", "scra" => "entity.foto"));
                echo "
                                ";
                // line 32
                echo $this->env->getExtension('grid')->linkInterno($this->env, $context, "", array("name" => "nombre", "class" => "posicionr", "texto" => "entity.nombre"));
                echo " 
                                ";
                // line 33
                echo $this->env->getExtension('grid')->linkInterno($this->env, $context, "", array("entity_id" => "entity.id", "name" => "direccion", "class" => "plano", "texto" => "entity.direccion"));
                echo " 
                            </tr>
                                ";
                // line 35
                $context["linea"] = ($this->getContext($context, "linea") + 1);
                // line 36
                echo "                            ";
                $context['_iterated'] = true;
            }
            if (!$context['_iterated']) {
                // line 37
                echo "                                <tr>
                                    <td colspan=\"4\">";
                // line 38
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("No hay Sociedades"), "html", null, true);
                echo "</td>
                                </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['entity'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 41
            echo "                        </tbody>
                        </table>
                  ";
        }
        // line 44
        echo "                </div>
            ";
        // line 45
        $this->displayBlock('content_sidebar', $context, $blocks);
        // line 57
        echo "

  ";
    }

    // line 45
    public function block_content_sidebar($context, array $blocks = array())
    {
        // line 46
        echo "             <div id=\"edicioncabecera\" class=\"span3\">
                <div id=\"map_canvas\" class=\"mapainvisible\" style=\"width:300px;height:200px\"></div>
             </div>                
            <div class=\"span3\">
               <p>  
               ";
        // line 51
        echo $this->env->getExtension('grid')->linkInterno($this->env, $context, "registro", array("texto" => "REGISTRO", "classa" => "'btn btn-large btn-primary'"));
        echo "
               </p>
                <h1><a href=\"#\">RECETAS</a></h1>
                <img alt=\"Anagrama\" src=\"";
        // line 54
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sociedad/uploads/images/recetas.gif"), "html", null, true);
        echo "\">
             </div>
            ";
    }

    // line 60
    public function block_footer($context, array $blocks = array())
    {
        // line 61
        echo "<aside>
<section id=\"nosotros\">
<h2>Sobre nosotros</h2>
<p>Lorem ipsum dolor sit amet...</p>
</section>
</aside>
<footer>
&copy; 2012 - CarlosSociedades
<a href=\"#\">Ayuda</a>
<a href=\"#\">Contacto</a>
<a href=\"#\">Privacidad</a>
<a href=\"#\">Sobre nosotros</a>
</footer>
";
    }

    public function getTemplateName()
    {
        return "SociedadSociedadesBundle:Default:portada.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  176 => 61,  173 => 60,  166 => 54,  160 => 51,  153 => 46,  150 => 45,  144 => 57,  142 => 45,  139 => 44,  134 => 41,  125 => 38,  122 => 37,  117 => 36,  115 => 35,  110 => 33,  106 => 32,  101 => 31,  97 => 29,  93 => 27,  90 => 26,  84 => 25,  82 => 24,  75 => 20,  71 => 19,  67 => 18,  59 => 14,  56 => 13,  54 => 12,  47 => 8,  44 => 7,  41 => 6,  34 => 4,  31 => 3,);
    }
}
