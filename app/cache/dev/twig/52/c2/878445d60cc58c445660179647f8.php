<?php

/* SociedadSociedadesBundle::layoutconplano.html.twig */
class __TwigTemplate_52c2878445d60cc58c445660179647f8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("MopaBootstrapBundle::base.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'head_style' => array($this, 'block_head_style'),
            'foot_script' => array($this, 'block_foot_script'),
            'navbar' => array($this, 'block_navbar'),
            'menusuperior' => array($this, 'block_menusuperior'),
            'fos_user_content' => array($this, 'block_fos_user_content'),
            'headline' => array($this, 'block_headline'),
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "MopaBootstrapBundle::base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo "Sociedades";
    }

    // line 4
    public function block_head_style($context, array $blocks = array())
    {
        // line 5
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/bootstrap/css/bootstrap.min.css"), "html", null, true);
        echo "\" type=\"text/css\" rel=\"stylesheet\" />
    <link href=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/bootstrap/css/bootstrap-responsive.min.css"), "html", null, true);
        echo "\" type=\"text/css\" rel=\"stylesheet\" />
    <link href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/bootstrap/css/bootstrap-image-gallery.min.css"), "html", null, true);
        echo "\" type=\"text/css\" rel=\"stylesheet\" />
    <link href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/bootstrap/css/start/jquery-ui-1.9.2.custom.min.css"), "html", null, true);
        echo "\" type=\"text/css\" rel=\"stylesheet\" />
    <link href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sociedad/css/sociedad.css"), "html", null, true);
        echo "\" type=\"text/css\" rel=\"stylesheet\" />
";
    }

    // line 11
    public function block_foot_script($context, array $blocks = array())
    {
        // line 12
        echo "        <script src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/bootstrap/js/jquery.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        <script src=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/bootstrap/js/jquery-ui-1.9.2.custom.min.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        <script src=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/bootstrap/js/bootstrap.min.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        <script src=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/bootstrap/js/bootstrap-dropdown.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        <script src=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/bootstrap/js/bootstrap-button.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        <script src=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/bootstrap/js/bootstrap-image-gallery.min.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        <script src=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("js/comunes2.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        <script src=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("js/plano.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        <script src=\"http://maps.google.com/maps/api/js?sensor=false\"></script>
        <script src=\"";
        // line 21
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("js/google-map.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
";
    }

    // line 24
    public function block_navbar($context, array $blocks = array())
    {
        // line 25
        echo "    <div class='navbar navbar-fixed-top'>
        <div class='navbar-inner'>
            <div class='container'>
                <div class='nav-collapse'>
                    ";
        // line 29
        $this->displayBlock('menusuperior', $context, $blocks);
        // line 31
        echo "                </div>

                <div >
                    ";
        // line 34
        if ($this->env->getExtension('security')->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            // line 35
            echo "                        ";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("layout.logged_in_as", array("%username%" => $this->getAttribute($this->getAttribute($this->getContext($context, "app"), "user"), "username")), "FOSUserBundle"), "html", null, true);
            echo " |
                        <a href=\"";
            // line 36
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fos_user_security_logout"), "html", null, true);
            echo "\">
                            ";
            // line 37
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("layout.logout", array(), "FOSUserBundle"), "html", null, true);
            echo "
                        </a>
                    ";
        } else {
            // line 40
            echo "                        <a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fos_user_security_login"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("layout.login", array(), "FOSUserBundle"), "html", null, true);
            echo "</a>
                    ";
        }
        // line 42
        echo "                </div>

                ";
        // line 44
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getContext($context, "app"), "session"), "getFlashes", array(), "method"));
        foreach ($context['_seq'] as $context["key"] => $context["message"]) {
            // line 45
            echo "                <div class=\"";
            echo twig_escape_filter($this->env, $this->getContext($context, "key"), "html", null, true);
            echo "\">
                    ";
            // line 46
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "message"), array(), "FOSUserBundle"), "html", null, true);
            echo "
                </div>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 49
        echo "            <div>
                ";
        // line 50
        $this->displayBlock('fos_user_content', $context, $blocks);
        // line 52
        echo "            </div>

            </div>
        </div>
    </div>
";
    }

    // line 29
    public function block_menusuperior($context, array $blocks = array())
    {
        // line 30
        echo "                    ";
    }

    // line 50
    public function block_fos_user_content($context, array $blocks = array())
    {
        // line 51
        echo "                ";
    }

    // line 61
    public function block_headline($context, array $blocks = array())
    {
        // line 62
        echo "    ";
    }

    // line 64
    public function block_footer($context, array $blocks = array())
    {
        // line 65
        echo "    ";
    }

    public function getTemplateName()
    {
        return "SociedadSociedadesBundle::layoutconplano.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  211 => 65,  208 => 64,  204 => 62,  201 => 61,  197 => 51,  194 => 50,  190 => 30,  187 => 29,  178 => 52,  164 => 46,  159 => 45,  155 => 44,  151 => 42,  143 => 40,  137 => 37,  133 => 36,  128 => 35,  126 => 34,  121 => 31,  119 => 29,  113 => 25,  104 => 21,  99 => 19,  95 => 18,  91 => 17,  87 => 16,  83 => 15,  79 => 14,  70 => 12,  61 => 9,  57 => 8,  53 => 7,  49 => 6,  35 => 2,  176 => 50,  173 => 49,  166 => 54,  160 => 51,  153 => 46,  150 => 45,  144 => 57,  142 => 45,  139 => 44,  134 => 41,  125 => 38,  122 => 37,  117 => 36,  115 => 35,  110 => 24,  106 => 32,  101 => 31,  97 => 29,  93 => 27,  90 => 26,  84 => 25,  82 => 24,  75 => 13,  71 => 19,  67 => 11,  59 => 14,  56 => 13,  54 => 12,  47 => 8,  44 => 5,  41 => 4,  34 => 4,  31 => 3,);
    }
}
